<?php

namespace Database\Seeders;

use App\Models\NivelRiesgo;
use Illuminate\Database\Seeder;

/**
 * Niveles de riesgo base. Idempotente — si ya existen, los actualiza.
 * IDs fijos para que las FKs en ComunaSeeder coincidan:
 *   1 = Bajo, 2 = Medio, 3 = Alto.
 */
class NivelRiesgoSeeder extends Seeder
{
    public function run(): void
    {
        $niveles = [
            ['id_nivel_riesgo' => 1, 'nivel' => 'Bajo',  'color' => '#22c55e'],
            ['id_nivel_riesgo' => 2, 'nivel' => 'Medio', 'color' => '#f97316'],
            ['id_nivel_riesgo' => 3, 'nivel' => 'Alto',  'color' => '#ef4444'],
        ];

        foreach ($niveles as $n) {
            NivelRiesgo::updateOrCreate(
                ['id_nivel_riesgo' => $n['id_nivel_riesgo']],
                ['nivel' => $n['nivel'], 'color' => $n['color']]
            );
        }
    }
}
