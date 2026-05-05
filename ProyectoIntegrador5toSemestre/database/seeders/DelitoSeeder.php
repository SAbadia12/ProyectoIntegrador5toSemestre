<?php

namespace Database\Seeders;

use App\Models\Comuna;
use App\Models\Delito;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Genera ~270 delitos ficticios pero realistas para alimentar el dashboard.
 *
 * Distribución por comuna basada en su nivel_riesgo:
 *   Alto  (3) → 18-25 delitos
 *   Medio (2) → 10-15 delitos
 *   Bajo  (1) → 3-7 delitos
 *
 * Distribución de tipo basada en estadísticas reales de Cali
 * (el hurto representa ~70% de los casos reportados).
 *
 * Idempotente: limpia la tabla antes de generar.
 */
class DelitoSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla para que el seeder sea reproducible
        DB::table('delitos')->delete();

        $rangos = [
            1 => [3, 7],     // Bajo
            2 => [10, 15],   // Medio
            3 => [18, 25],   // Alto
        ];

        $tiposPesos = [
            'Hurto a personas'        => 28,
            'Hurto a residencias'     => 12,
            'Hurto a comercio'        => 10,
            'Hurto de motos'          => 14,
            'Hurto de vehículos'      => 8,
            'Lesiones personales'     => 10,
            'Violencia intrafamiliar' => 8,
            'Homicidio'               => 5,
            'Extorsión'               => 4,
            'Secuestro'               => 1,
        ];

        $gravedadPorTipo = [
            'Hurto a personas'        => 1,
            'Hurto a residencias'     => 2,
            'Hurto a comercio'        => 2,
            'Hurto de motos'          => 2,
            'Hurto de vehículos'      => 2,
            'Lesiones personales'     => 2,
            'Violencia intrafamiliar' => 3,
            'Homicidio'               => 3,
            'Extorsión'               => 3,
            'Secuestro'               => 3,
        ];

        // Construir bag de selección ponderada
        $bag = [];
        foreach ($tiposPesos as $tipo => $peso) {
            for ($i = 0; $i < $peso; $i++) {
                $bag[] = $tipo;
            }
        }

        $hoy = Carbon::today();
        $hace12Meses = $hoy->copy()->subMonths(12);

        $registros = [];
        $comunas = Comuna::all();

        if ($comunas->isEmpty()) {
            $this->command->warn('No hay comunas. Corre ComunaSeeder primero.');
            return;
        }

        foreach ($comunas as $comuna) {
            $nivel = (int) ($comuna->id_nivel_riesgo ?? 2);
            $rango = $rangos[$nivel] ?? $rangos[2];
            $cantidad = random_int($rango[0], $rango[1]);

            for ($i = 0; $i < $cantidad; $i++) {
                $tipo = $bag[array_rand($bag)];
                $diasAtras = random_int(0, $hace12Meses->diffInDays($hoy));
                $fecha = $hoy->copy()->subDays($diasAtras);

                $registros[] = [
                    'tipo'        => $tipo,
                    'id_comuna'   => $comuna->id_comuna,
                    'fecha'       => $fecha->toDateString(),
                    'gravedad'    => $gravedadPorTipo[$tipo] ?? 1,
                    'descripcion' => null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        foreach (array_chunk($registros, 100) as $chunk) {
            DB::table('delitos')->insert($chunk);
        }

        $this->command->info('Delitos creados: ' . count($registros));
    }
}
