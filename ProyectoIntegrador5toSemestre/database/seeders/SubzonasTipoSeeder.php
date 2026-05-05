<?php

namespace Database\Seeders;

use App\Models\SubzonasTipo;
use Illuminate\Database\Seeder;

/**
 * Subtipos: cómo se divide una Zona internamente.
 *   - Las Comunas se dividen en Barrios.
 *   - Los Corregimientos se dividen en Veredas.
 */
class SubzonasTipoSeeder extends Seeder
{
    public function run(): void
    {
        $subtipos = [
            ['id_subtipo' => 1, 'subtipo' => 'Barrio'],
            ['id_subtipo' => 2, 'subtipo' => 'Vereda'],
            ['id_subtipo' => 3, 'subtipo' => 'Sector'],
        ];

        foreach ($subtipos as $s) {
            SubzonasTipo::updateOrCreate(
                ['id_subtipo' => $s['id_subtipo']],
                ['subtipo' => $s['subtipo']]
            );
        }
    }
}
