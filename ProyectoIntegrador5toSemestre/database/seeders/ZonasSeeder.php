<?php

namespace Database\Seeders;

use App\Models\Zonas;
use Illuminate\Database\Seeder;

/**
 * Zonas de Cali: 22 comunas urbanas + corregimientos rurales principales.
 * Sigue el MER: cada Zona pertenece a un Tipo (Comuna / Corregimiento).
 */
class ZonasSeeder extends Seeder
{
    public function run(): void
    {
        // 22 Comunas urbanas (tipo_zona = 1 = Comuna)
        for ($i = 1; $i <= 22; $i++) {
            Zonas::updateOrCreate(
                ['zona' => 'Comuna ' . $i],
                ['tipo_zona' => 1]
            );
        }

        // Corregimientos principales (tipo_zona = 2 = Corregimiento)
        $corregimientos = [
            'Pance',
            'La Buitrera',
            'Villacarmelo',
            'Los Andes',
            'Pichindé',
            'La Leonera',
            'Felidia',
            'El Saladito',
            'La Castilla',
            'La Paz',
            'Montebello',
            'Golondrinas',
            'Navarro',
            'El Hormiguero',
            'La Elvira',
        ];

        foreach ($corregimientos as $c) {
            Zonas::updateOrCreate(
                ['zona' => $c],
                ['tipo_zona' => 2]
            );
        }
    }
}
