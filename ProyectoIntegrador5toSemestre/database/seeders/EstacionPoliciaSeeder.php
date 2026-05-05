<?php

namespace Database\Seeders;

use App\Models\EstacionPolicia;
use Illuminate\Database\Seeder;

/**
 * Estaciones de Policía Nacional en Cali.
 * Coordenadas aproximadas; el moderador puede corregirlas desde el CRUD.
 * Línea Nacional Policía: 123 (uniforme en todas).
 */
class EstacionPoliciaSeeder extends Seeder
{
    public function run(): void
    {
        $estaciones = [
            ['nombre' => 'Comando Metropolitano de Cali (MECAL)',  'dir' => 'Cra. 3 #28-91, Granada',                  'tel' => '(602) 660 7777', 'lat' => 3.4625, 'lng' => -76.5350, 'comuna' => 3],
            ['nombre' => 'Estación de Policía San Fernando',       'dir' => 'Cl. 5 #34-50, San Fernando',              'tel' => '123',            'lat' => 3.4400, 'lng' => -76.5400, 'comuna' => 19],
            ['nombre' => 'Estación de Policía Norte',              'dir' => 'Av. 3N #45-20, Calima',                   'tel' => '123',            'lat' => 3.4830, 'lng' => -76.5180, 'comuna' => 4],
            ['nombre' => 'Estación de Policía El Centro',          'dir' => 'Cl. 13 #5-22, Centro',                    'tel' => '123',            'lat' => 3.4530, 'lng' => -76.5340, 'comuna' => 3],
            ['nombre' => 'Estación de Policía Aguablanca',         'dir' => 'Cl. 73 #28C-15, Aguablanca',              'tel' => '123',            'lat' => 3.4350, 'lng' => -76.4720, 'comuna' => 13],
            ['nombre' => 'Estación de Policía Mariano Ramos',      'dir' => 'Cl. 48 #41-40, Mariano Ramos',            'tel' => '123',            'lat' => 3.4080, 'lng' => -76.5040, 'comuna' => 16],
            ['nombre' => 'Estación de Policía Siloé',              'dir' => 'Cra. 50 #2-10, Siloé',                    'tel' => '123',            'lat' => 3.4210, 'lng' => -76.5660, 'comuna' => 20],
            ['nombre' => 'Estación de Policía Floralia',           'dir' => 'Cl. 70N #1N-50, Floralia',                'tel' => '123',            'lat' => 3.4880, 'lng' => -76.4990, 'comuna' => 6],
            ['nombre' => 'Estación de Policía Ciudad Jardín',      'dir' => 'Cra. 105 #14-300, Ciudad Jardín',         'tel' => '123',            'lat' => 3.3900, 'lng' => -76.5310, 'comuna' => 17],
            ['nombre' => 'Estación de Policía Meléndez',           'dir' => 'Cra. 94 #5A-10, Meléndez',                'tel' => '123',            'lat' => 3.3900, 'lng' => -76.5500, 'comuna' => 18],
            ['nombre' => 'Estación de Policía Alfonso López',      'dir' => 'Cl. 72 #28-30, Alfonso López',            'tel' => '123',            'lat' => 3.4690, 'lng' => -76.4880, 'comuna' => 7],
            ['nombre' => 'Estación de Policía Manuela Beltrán',    'dir' => 'Cl. 84 #28D-20, Manuela Beltrán',         'tel' => '123',            'lat' => 3.4220, 'lng' => -76.4750, 'comuna' => 14],
            ['nombre' => 'Estación de Policía Mojica',             'dir' => 'Cl. 100 #28D-50, Mojica',                 'tel' => '123',            'lat' => 3.4080, 'lng' => -76.4790, 'comuna' => 15],
            ['nombre' => 'Estación de Policía Versalles',          'dir' => 'Av. 6N #25-50, Versalles',                'tel' => '123',            'lat' => 3.4830, 'lng' => -76.5320, 'comuna' => 2],
            ['nombre' => 'Estación de Policía Terrón Colorado',    'dir' => 'Cl. 1 Oeste #50-30, Terrón Colorado',     'tel' => '123',            'lat' => 3.4790, 'lng' => -76.5500, 'comuna' => 1],
            ['nombre' => 'CAI Granada',                            'dir' => 'Av. 9N #15-40, Granada',                  'tel' => '123',            'lat' => 3.4660, 'lng' => -76.5300, 'comuna' => 2],
            ['nombre' => 'CAI Caney',                              'dir' => 'Cra. 70 #14-50, Caney',                   'tel' => '123',            'lat' => 3.3920, 'lng' => -76.5260, 'comuna' => 17],
            ['nombre' => 'CAI El Lido',                            'dir' => 'Cra. 50 #5-20, El Lido',                  'tel' => '123',            'lat' => 3.4290, 'lng' => -76.5260, 'comuna' => 10],
            ['nombre' => 'CAI Aranjuez',                           'dir' => 'Cl. 36 #25-30, Aranjuez',                 'tel' => '123',            'lat' => 3.4400, 'lng' => -76.4970, 'comuna' => 11],
            ['nombre' => 'CAI Pance',                              'dir' => 'Av. La María, Pance',                     'tel' => '123',            'lat' => 3.3690, 'lng' => -76.5500, 'comuna' => 22],
        ];

        foreach ($estaciones as $e) {
            EstacionPolicia::updateOrCreate(
                ['nombre' => $e['nombre']],
                [
                    'direccion' => $e['dir'],
                    'telefono'  => $e['tel'],
                    'latitud'   => $e['lat'],
                    'longitud'  => $e['lng'],
                    'id_comuna' => $e['comuna'],
                ]
            );
        }
    }
}
