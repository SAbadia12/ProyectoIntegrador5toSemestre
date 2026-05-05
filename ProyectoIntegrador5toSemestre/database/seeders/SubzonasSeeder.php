<?php

namespace Database\Seeders;

use App\Models\Subzonas;
use App\Models\Zonas;
use Illuminate\Database\Seeder;

/**
 * Subzonas (Barrios y Veredas) representativas de Cali.
 * - Cada barrio pertenece a su Comuna.
 * - Las veredas (rurales) van con corregimientos.
 */
class SubzonasSeeder extends Seeder
{
    public function run(): void
    {
        // [zona_padre, subzona, tipo_subzona] - Barrios = 1, Veredas = 2
        $datos = [
            // Comuna 2 (Norte)
            ['Comuna 2', 'Granada',          1],
            ['Comuna 2', 'Versalles',        1],
            ['Comuna 2', 'Centenario',       1],
            ['Comuna 2', 'Santa Mónica',     1],

            // Comuna 3 (Centro)
            ['Comuna 3', 'San Antonio',      1],
            ['Comuna 3', 'San Pascual',      1],
            ['Comuna 3', 'El Peñón',         1],

            // Comuna 9 (Centro-Sur)
            ['Comuna 9', 'Alameda',          1],
            ['Comuna 9', 'Bretaña',          1],
            ['Comuna 9', 'Sucre',            1],

            // Comuna 13 (Aguablanca)
            ['Comuna 13', 'El Poblado',      1],
            ['Comuna 13', 'Marroquín',       1],
            ['Comuna 13', 'Charco Azul',     1],

            // Comuna 14 (Aguablanca)
            ['Comuna 14', 'Manuela Beltrán', 1],
            ['Comuna 14', 'Alfonso Bonilla', 1],

            // Comuna 15 (Aguablanca)
            ['Comuna 15', 'Mojica',          1],
            ['Comuna 15', 'El Retiro',       1],
            ['Comuna 15', 'Comuneros',       1],

            // Comuna 17 (Sur)
            ['Comuna 17', 'Ciudad Jardín',   1],
            ['Comuna 17', 'Caney',           1],
            ['Comuna 17', 'El Ingenio',      1],

            // Comuna 19 (Sur)
            ['Comuna 19', 'San Fernando',    1],
            ['Comuna 19', 'Tequendama',      1],

            // Comuna 20 (Ladera - Siloé)
            ['Comuna 20', 'Siloé',           1],
            ['Comuna 20', 'Tierra Blanca',   1],
            ['Comuna 20', 'Lleras Camargo',  1],

            // Comuna 22 (Sur rural-urbano)
            ['Comuna 22', 'Ciudad Capri',    1],
            ['Comuna 22', 'La Riverita',     1],

            // Veredas en corregimientos
            ['Pance',         'San Francisco',     2],
            ['Pance',         'La Vorágine',       2],
            ['La Buitrera',   'Las Ceibas',        2],
            ['Villacarmelo',  'El Otoño',          2],
            ['Felidia',       'Felidia Centro',    2],
            ['Pichindé',      'Pichindé Alto',     2],
        ];

        foreach ($datos as [$zonaNombre, $subNombre, $tipo]) {
            $zona = Zonas::where('zona', $zonaNombre)->first();
            if (! $zona) continue;

            Subzonas::updateOrCreate(
                ['subzona' => $subNombre, 'id_zona' => $zona->id_zona],
                ['tipo_subzona' => $tipo]
            );
        }
    }
}
