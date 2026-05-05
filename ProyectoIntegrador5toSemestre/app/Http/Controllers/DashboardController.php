<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\Delito;
use App\Models\EstacionPolicia;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * RF10 - Dashboard del Analista de Seguridad.
 *
 * Reúne todas las métricas del sistema y las pasa a la vista
 * resources/views/dashboard/index.blade.php.
 *
 * Trabaja sobre la tabla 'delitos' (antes llamada 'incidentes').
 */
class DashboardController extends Controller
{
    public function index()
    {
        // ──────────────────────────────────────────────
        // KPIs (tarjetas de número)
        // ──────────────────────────────────────────────
        $kpis = [
            'total_delitos'       => Delito::count(),
            'total_comunas'       => Comuna::count(),
            'total_estaciones'    => EstacionPolicia::count(),
            'total_usuarios'      => Usuario::count(),
            'comunas_alto_riesgo' => Comuna::where('id_nivel_riesgo', 3)->count(),
            'delitos_mes'         => Delito::whereMonth('fecha', now()->month)
                                           ->whereYear('fecha', now()->year)
                                           ->count(),
        ];

        // ──────────────────────────────────────────────
        // Gráfico 1: Delitos por comuna (Bar)
        // ──────────────────────────────────────────────
        $delitosPorComuna = DB::table('delitos')
            ->join('comunas', 'delitos.id_comuna', '=', 'comunas.id_comuna')
            ->select('comunas.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('comunas.id_comuna', 'comunas.nombre', 'comunas.numero')
            ->orderBy('comunas.numero')
            ->get();

        // ──────────────────────────────────────────────
        // Gráfico 2: Distribución por tipo de delito (Pie/Doughnut)
        // ──────────────────────────────────────────────
        $delitosPorTipo = DB::table('delitos')
            ->select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

        // ──────────────────────────────────────────────
        // Gráfico 3: Delitos por mes (Line - últimos 12 meses)
        // ──────────────────────────────────────────────
        $delitosPorMes = collect();
        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $total = Delito::whereYear('fecha', $fecha->year)
                           ->whereMonth('fecha', $fecha->month)
                           ->count();
            $delitosPorMes->push([
                'mes'   => $fecha->locale('es')->isoFormat('MMM YY'),
                'total' => $total,
            ]);
        }

        // ──────────────────────────────────────────────
        // Gráfico 4: Distribución de niveles de riesgo (Pie)
        // ──────────────────────────────────────────────
        $distribucionNiveles = DB::table('comunas')
            ->leftJoin('nivel_riesgos', 'comunas.id_nivel_riesgo', '=', 'nivel_riesgos.id_nivel_riesgo')
            ->select(
                DB::raw("COALESCE(nivel_riesgos.nivel, 'Sin clasificar') as nivel"),
                DB::raw("COALESCE(nivel_riesgos.color, '#94a3b8') as color"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('nivel_riesgos.nivel', 'nivel_riesgos.color')
            ->get();

        // ──────────────────────────────────────────────
        // Tabla: Top 5 comunas con más delitos
        // ──────────────────────────────────────────────
        $topComunas = DB::table('delitos')
            ->join('comunas', 'delitos.id_comuna', '=', 'comunas.id_comuna')
            ->leftJoin('nivel_riesgos', 'comunas.id_nivel_riesgo', '=', 'nivel_riesgos.id_nivel_riesgo')
            ->select(
                'comunas.nombre',
                'comunas.numero',
                'nivel_riesgos.nivel as nivel_riesgo',
                'nivel_riesgos.color as color',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('comunas.id_comuna', 'comunas.nombre', 'comunas.numero', 'nivel_riesgos.nivel', 'nivel_riesgos.color')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ──────────────────────────────────────────────
        // Gráfico 5: Delitos por gravedad (Bar horizontal)
        // ──────────────────────────────────────────────
        $porGravedad = DB::table('delitos')
            ->select('gravedad', DB::raw('COUNT(*) as total'))
            ->groupBy('gravedad')
            ->orderBy('gravedad')
            ->get()
            ->map(function ($r) {
                $labels = [1 => 'Leve', 2 => 'Medio', 3 => 'Grave'];
                return [
                    'label' => $labels[$r->gravedad] ?? 'Desconocido',
                    'total' => $r->total,
                ];
            });

        return view('dashboard.index', [
            'kpis'               => $kpis,
            'delitosPorComuna'   => $delitosPorComuna,
            'delitosPorTipo'     => $delitosPorTipo,
            'delitosPorMes'      => $delitosPorMes,
            'distribucionNiveles'=> $distribucionNiveles,
            'topComunas'         => $topComunas,
            'porGravedad'        => $porGravedad,
        ]);
    }
}
