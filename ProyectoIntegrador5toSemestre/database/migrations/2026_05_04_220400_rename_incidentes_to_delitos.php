<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Renombra la tabla 'incidentes' a 'delitos' y la columna PK
 * 'id_incidente' a 'id_delito' para mantener consistencia.
 *
 * Funciona tanto para instalaciones existentes como frescas.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Renombrar tabla
        if (Schema::hasTable('incidentes') && ! Schema::hasTable('delitos')) {
            Schema::rename('incidentes', 'delitos');
        }

        // 2. Renombrar columna PK
        if (Schema::hasTable('delitos') && Schema::hasColumn('delitos', 'id_incidente')) {
            Schema::table('delitos', function (Blueprint $table) {
                $table->renameColumn('id_incidente', 'id_delito');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('delitos') && Schema::hasColumn('delitos', 'id_delito')) {
            Schema::table('delitos', function (Blueprint $table) {
                $table->renameColumn('id_delito', 'id_incidente');
            });
        }

        if (Schema::hasTable('delitos') && ! Schema::hasTable('incidentes')) {
            Schema::rename('delitos', 'incidentes');
        }
    }
};
