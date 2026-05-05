<?php

namespace Database\Seeders;

use App\Models\Comuna;
use Illuminate\Database\Seeder;

/**
 * Seeder con las 22 comunas urbanas de Cali.
 * Coordenadas aproximadas del centroide de cada comuna (lat, lng en WGS84).
 * Niveles de riesgo iniciales (1=Bajo, 2=Medio, 3=Alto) basados en datos
 * generales públicos. El moderador podrá ajustarlos desde la app.
 */
class ComunaSeeder extends Seeder
{
    public function run(): void
    {
        $comunas = [
            ['numero' =>  1, 'lat' => 3.4783, 'lng' => -76.5483, 'nivel' => 3, 'desc' => 'Norte-occidente, ladera (Terrón Colorado, Aguacatal).'],
            ['numero' =>  2, 'lat' => 3.4830, 'lng' => -76.5320, 'nivel' => 1, 'desc' => 'Norte (Granada, Centenario, Versalles).'],
            ['numero' =>  3, 'lat' => 3.4530, 'lng' => -76.5380, 'nivel' => 2, 'desc' => 'Centro histórico de Cali.'],
            ['numero' =>  4, 'lat' => 3.4640, 'lng' => -76.5180, 'nivel' => 2, 'desc' => 'Nor-oriente (San Pedro Claver, Salomia).'],
            ['numero' =>  5, 'lat' => 3.4700, 'lng' => -76.5050, 'nivel' => 2, 'desc' => 'Norte-oriente (Chiminangos, Los Andes).'],
            ['numero' =>  6, 'lat' => 3.4870, 'lng' => -76.4960, 'nivel' => 3, 'desc' => 'Nor-oriente (Floralia, Petecuy, Calimío).'],
            ['numero' =>  7, 'lat' => 3.4690, 'lng' => -76.4880, 'nivel' => 3, 'desc' => 'Oriente (Alfonso López, Andrés Sanín).'],
            ['numero' =>  8, 'lat' => 3.4480, 'lng' => -76.5000, 'nivel' => 2, 'desc' => 'Centro-oriente (Saavedra Galindo, La Floresta).'],
            ['numero' =>  9, 'lat' => 3.4400, 'lng' => -76.5180, 'nivel' => 2, 'desc' => 'Centro (Alameda, Sucre, Bretaña).'],
            ['numero' => 10, 'lat' => 3.4290, 'lng' => -76.5260, 'nivel' => 1, 'desc' => 'Sur-oriente (El Lido, San Judas, Departamental).'],
            ['numero' => 11, 'lat' => 3.4400, 'lng' => -76.4970, 'nivel' => 3, 'desc' => 'Oriente (San Carlos, La Esperanza, Aranjuez).'],
            ['numero' => 12, 'lat' => 3.4480, 'lng' => -76.4880, 'nivel' => 3, 'desc' => 'Oriente (Asturias, Fenalco-Kennedy).'],
            ['numero' => 13, 'lat' => 3.4360, 'lng' => -76.4720, 'nivel' => 3, 'desc' => 'Distrito de Aguablanca (El Poblado, Marroquín).'],
            ['numero' => 14, 'lat' => 3.4220, 'lng' => -76.4750, 'nivel' => 3, 'desc' => 'Distrito de Aguablanca (Alfonso Bonilla Aragón, Manuela Beltrán).'],
            ['numero' => 15, 'lat' => 3.4080, 'lng' => -76.4790, 'nivel' => 3, 'desc' => 'Distrito de Aguablanca (El Retiro, Mojica, Comuneros).'],
            ['numero' => 16, 'lat' => 3.4060, 'lng' => -76.5040, 'nivel' => 2, 'desc' => 'Sur-oriente (Mariano Ramos, República de Israel).'],
            ['numero' => 17, 'lat' => 3.3920, 'lng' => -76.5310, 'nivel' => 1, 'desc' => 'Sur (Ciudad Jardín, Capri, Caney).'],
            ['numero' => 18, 'lat' => 3.3880, 'lng' => -76.5500, 'nivel' => 2, 'desc' => 'Sur (Buenos Aires, Meléndez, Caldas).'],
            ['numero' => 19, 'lat' => 3.4150, 'lng' => -76.5470, 'nivel' => 1, 'desc' => 'Sur (San Fernando, Tequendama, El Lido).'],
            ['numero' => 20, 'lat' => 3.4250, 'lng' => -76.5650, 'nivel' => 3, 'desc' => 'Ladera (Siloé, Tierra Blanca).'],
            ['numero' => 21, 'lat' => 3.4030, 'lng' => -76.4850, 'nivel' => 3, 'desc' => 'Distrito de Aguablanca (Pizamos, Potrero Grande).'],
            ['numero' => 22, 'lat' => 3.3690, 'lng' => -76.5500, 'nivel' => 1, 'desc' => 'Sur (Pance, La Riverita, Ciudad Capri).'],
        ];

        foreach ($comunas as $c) {
            Comuna::updateOrCreate(
                ['numero' => $c['numero']],
                [
                    'nombre'          => 'Comuna ' . $c['numero'],
                    'latitud'         => $c['lat'],
                    'longitud'        => $c['lng'],
                    'id_nivel_riesgo' => $c['nivel'],
                    'descripcion'     => $c['desc'],
                ]
            );
        }
    }
}
