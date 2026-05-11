<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Orden importante:
     *  1. NivelRiesgo (lo necesitan las comunas como FK)
     *  2. Comunas    (las necesitan las estaciones como FK)
     *  3. Estaciones de Policía
     *
     * Todos los seeders son idempotentes (updateOrCreate),
     * así que se pueden correr múltiples veces sin duplicar.
     */
    public function run(): void
    {
        $this->call([
            // ── Catálogos base ──────────────────────────────
            NivelRiesgoSeeder::class,
            PuntoCardinalSeeder::class,
            ZonasTipoSeeder::class,
            SubzonasTipoSeeder::class,

            // ── Datos geográficos ───────────────────────────
            ZonasSeeder::class,
            SubzonasSeeder::class,
            UbicacionSeeder::class,

            // ── Legado (compatibilidad con dashboard) ───────
            EstacionPoliciaSeeder::class,

            // ── Usuarios y delitos ──────────────────────────
            UsuarioSeeder::class,
            DelitoSeeder::class,
        ]);
    }
}
