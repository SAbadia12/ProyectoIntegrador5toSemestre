<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use App\Models\Zonas;
use Illuminate\Database\Seeder;

/**
 * Ubicaciones representativas de Cali con coordenadas reales.
 * Cada una con su nivel de riesgo, punto cardinal y zona.
 *
 * IDs FK:
 *   nivel_riesgo: 1=Bajo, 2=Medio, 3=Alto
 *   punto_cardinal: 1=Norte, 2=Sur, 3=Oriente, 4=Occidente, 5=Centro
 *   zona: por nombre (busca dinámicamente)
 */
class UbicacionSeeder extends Seeder
{
    public function run(): void
    {
        // [direccion, lat, lng, id_nivel, id_punto_cardinal, zona_nombre]
        $datos = [
            // ── Comunas con riesgo ALTO ──────────────────────────
            ['Cl. 73 #28C-15, El Poblado',          3.4350, -76.4720, 3, 3, 'Comuna 13'],
            ['Cl. 84 #28D-20, Manuela Beltrán',     3.4220, -76.4750, 3, 3, 'Comuna 14'],
            ['Cl. 100 #28D-50, Mojica',             3.4080, -76.4790, 3, 3, 'Comuna 15'],
            ['Cra. 50 #2-10, Siloé',                3.4210, -76.5660, 3, 4, 'Comuna 20'],
            ['Cl. 70N #1N-50, Floralia',            3.4880, -76.4990, 3, 1, 'Comuna 6'],
            ['Cl. 72 #28-30, Alfonso López',        3.4690, -76.4880, 3, 3, 'Comuna 7'],
            ['Cl. 1 Oeste #50-30, Terrón Colorado', 3.4790, -76.5500, 3, 4, 'Comuna 1'],
            ['Cl. 36 #25-30, Aranjuez',             3.4400, -76.4970, 3, 3, 'Comuna 11'],
            ['Cl. 48 #41-40, Asturias',             3.4480, -76.4880, 3, 3, 'Comuna 12'],
            ['Cl. 100 #29-15, Potrero Grande',      3.4030, -76.4850, 3, 2, 'Comuna 21'],

            // ── Comunas con riesgo MEDIO ─────────────────────────
            ['Cl. 13 #5-22, Centro',                3.4530, -76.5340, 2, 5, 'Comuna 3'],
            ['Av. 3N #45-20, Calima',               3.4830, -76.5180, 2, 1, 'Comuna 4'],
            ['Cra. 70 #14-50, Chiminangos',         3.4700, -76.5050, 2, 1, 'Comuna 5'],
            ['Cl. 25 #5-40, Saavedra Galindo',      3.4480, -76.5000, 2, 3, 'Comuna 8'],
            ['Cra. 28 #5-15, Alameda',              3.4400, -76.5180, 2, 5, 'Comuna 9'],
            ['Cra. 50 #5-20, El Lido',              3.4290, -76.5260, 2, 2, 'Comuna 10'],
            ['Cra. 80 #5A-30, Mariano Ramos',       3.4080, -76.5040, 2, 2, 'Comuna 16'],
            ['Cra. 80 #2-20, Buenos Aires',         3.3880, -76.5500, 2, 2, 'Comuna 18'],

            // ── Comunas con riesgo BAJO ──────────────────────────
            ['Av. 6N #25-50, Granada',              3.4660, -76.5300, 1, 1, 'Comuna 2'],
            ['Cra. 105 #14-300, Ciudad Jardín',     3.3900, -76.5310, 1, 2, 'Comuna 17'],
            ['Cra. 50 #5-20, San Fernando',         3.4400, -76.5400, 1, 2, 'Comuna 19'],
            ['Av. La María, Ciudad Capri',          3.3690, -76.5500, 1, 2, 'Comuna 22'],

            // ── Corregimientos ───────────────────────────────────
            ['Vereda San Francisco, Pance',         3.3450, -76.5650, 1, 2, 'Pance'],
            ['Las Ceibas, La Buitrera',             3.3720, -76.6250, 2, 4, 'La Buitrera'],
            ['Vereda El Otoño, Villacarmelo',       3.3520, -76.6100, 1, 4, 'Villacarmelo'],
            ['Felidia Centro, Felidia',             3.4150, -76.6500, 2, 4, 'Felidia'],
            ['Vereda Pichindé Alto',                3.4320, -76.6720, 1, 4, 'Pichindé'],
            ['Vereda Navarro',                      3.4200, -76.4500, 3, 3, 'Navarro'],
            ['Vereda El Hormiguero',                3.3700, -76.4350, 2, 2, 'El Hormiguero'],
            ['Vereda La Elvira',                    3.5350, -76.6200, 1, 4, 'La Elvira'],
        ];

        foreach ($datos as [$direccion, $lat, $lng, $idNivel, $idPunto, $zonaNombre]) {
            $zona = Zonas::where('zona', $zonaNombre)->first();
            if (! $zona) {
                $this->command->warn("Zona '{$zonaNombre}' no encontrada, salteando.");
                continue;
            }

            Ubicacion::updateOrCreate(
                ['direccion' => $direccion],
                [
                    'latitud'           => $lat,
                    'longitud'          => $lng,
                    'id_nivel'          => $idNivel,
                    'id_punto_cardinal' => $idPunto,
                    'id_subzona'           => $zona->id_subzona,
                ]
            );
        }

        $this->command->info('Ubicaciones creadas: ' . count($datos));
    }
}
