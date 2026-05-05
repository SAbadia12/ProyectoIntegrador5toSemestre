<?php

namespace Database\Seeders;

use App\Models\ZonasTipo;
use Illuminate\Database\Seeder;

/**
 * Tipos de zona según el MER del proyecto.
 * IDs fijos para que las FKs en ZonasSeeder coincidan.
 */
class ZonasTipoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['id_tipo' => 1, 'tipo' => 'Comuna'],
            ['id_tipo' => 2, 'tipo' => 'Corregimiento'],
        ];

        foreach ($tipos as $t) {
            ZonasTipo::updateOrCreate(
                ['id_tipo' => $t['id_tipo']],
                ['tipo' => $t['tipo']]
            );
        }
    }
}
