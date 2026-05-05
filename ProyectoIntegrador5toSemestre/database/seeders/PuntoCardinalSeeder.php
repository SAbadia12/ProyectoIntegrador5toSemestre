<?php

namespace Database\Seeders;

use App\Models\PuntoCardinal;
use Illuminate\Database\Seeder;

/**
 * Puntos cardinales base. Idempotente.
 * IDs fijos para que las FKs en seeders posteriores coincidan.
 */
class PuntoCardinalSeeder extends Seeder
{
    public function run(): void
    {
        $puntos = [
            ['id_punto_cardinal' => 1, 'nombre' => 'Norte'],
            ['id_punto_cardinal' => 2, 'nombre' => 'Sur'],
            ['id_punto_cardinal' => 3, 'nombre' => 'Oriente'],
            ['id_punto_cardinal' => 4, 'nombre' => 'Occidente'],
            ['id_punto_cardinal' => 5, 'nombre' => 'Centro'],
        ];

        foreach ($puntos as $p) {
            PuntoCardinal::updateOrCreate(
                ['id_punto_cardinal' => $p['id_punto_cardinal']],
                ['nombre' => $p['nombre']]
            );
        }
    }
}
