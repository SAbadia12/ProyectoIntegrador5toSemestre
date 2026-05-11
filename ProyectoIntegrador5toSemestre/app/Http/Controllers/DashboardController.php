<?php

namespace App\Http\Controllers;

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
            'total_estaciones'    => EstacionPolicia::count(),
            'total_usuarios'      => Usuario::count(),
            'delitos_mes_actual'  => DB::table('delito_ubicacion')
                                         ->whereMonth('fecha', Carbon::now()->month)
                                         ->whereYear('fecha', Carbon::now()->year)->count(),
            'delitos_mes_anterior' => DB::table('delito_ubicacion')
                                         ->whereMonth('fecha', Carbon::now()->subMonth()->month)
                                         ->whereYear('fecha', Carbon::now()->subMonth()->year)->count(),
            'promedio_delitos_mes' => round(DB::table('delito_ubicacion')->count() / 12, 1), // Aproximado
        ];

        // ──────────────────────────────────────────────
        // Gráfico 1: Delitos por Zona (Bar)
        // ──────────────────────────────────────────────
        $delitosPorZona = DB::table('delito_ubicacion as du')
            ->join('ubicaciones as u', 'du.id_ubicacion', '=', 'u.id_ubicacion')
            ->join('subzonas as s', 'u.id_subzona', '=', 's.id_subzona')
            ->join('zonas as z', 's.id_zona', '=', 'z.id_zona')
            ->select('z.zona as nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('z.id_zona', 'z.zona')
            ->orderByDesc('total')
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
        $delitosPorMes = DB::table('delito_ubicacion')
            ->select(
                DB::raw('DATE_FORMAT(fecha, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('fecha', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => Carbon::createFromFormat('Y-m', $item->mes)->format('M Y'),
                    'total' => $item->total
                ];
            });

        // ──────────────────────────────────────────────
        // Gráfico 4: Distribución de niveles de riesgo (Pie)
        // ──────────────────────────────────────────────
        $nivelesRiesgo = DB::table('ubicaciones as u')
            ->join('nivel_riesgos as n', 'u.id_nivel', '=', 'n.id_nivel_riesgo')
            ->select('n.nivel', 'n.color', DB::raw('COUNT(*) as total'))
            ->groupBy('n.id_nivel_riesgo', 'n.nivel', 'n.color')
            ->orderByDesc('total')
            ->get();

        // ──────────────────────────────────────────────
        // Tabla: Top 5 zonas con más delitos
        // ──────────────────────────────────────────────
        $topZonas = DB::table('delito_ubicacion as du')
            ->join('ubicaciones as u', 'du.id_ubicacion', '=', 'u.id_ubicacion')
            ->join('subzonas as s', 'u.id_subzona', '=', 's.id_subzona')
            ->join('zonas as z', 's.id_zona', '=', 'z.id_zona')
            ->select('z.zona', DB::raw('COUNT(*) as total'))
            ->groupBy('z.id_zona', 'z.zona')
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

        // ──────────────────────────────────────────────
        // Gráfico 6: Delitos por día de la semana
        // ──────────────────────────────────────────────
        $delitosPorDia = DB::table('delito_ubicacion')
            ->select(
                DB::raw('DAYOFWEEK(fecha) as dia_num'),
                DB::raw('COUNT(*) as total')
            )
            ->where('fecha', '>=', Carbon::now()->subMonths(6))
            ->groupBy('dia_num')
            ->orderBy('dia_num')
            ->get()
            ->map(function ($item) {
                $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                return [
                    'dia' => $dias[$item->dia_num - 1] ?? 'Desconocido',
                    'total' => $item->total
                ];
            });

        return view('dashboard.index', [
            'kpis'               => $kpis,
            'delitosPorZona'     => $delitosPorZona,
            'delitosPorTipo'     => $delitosPorTipo,
            'delitosPorMes'      => $delitosPorMes,
            'nivelesRiesgo'      => $nivelesRiesgo,
            'topZonas'           => $topZonas,
            'porGravedad'        => $porGravedad,
            'delitosPorDia'      => $delitosPorDia,
        ]);
    }
}
