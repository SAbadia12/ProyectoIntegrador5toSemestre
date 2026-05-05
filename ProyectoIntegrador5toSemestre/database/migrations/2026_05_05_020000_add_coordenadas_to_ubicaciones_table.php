<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega latitud y longitud a la tabla 'ubicaciones'.
 * Necesarias para representar las ubicaciones en el mapa de calor (RF6).
 *
 * Esto extiende el MER del proyecto para soportar geolocalización,
 * lo cual es indispensable para cumplir el RF6 (mapa de calor).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->decimal('latitud', 10, 7)->nullable()->after('direccion');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
        });
    }

    public function down(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropColumn(['latitud', 'longitud']);
        });
    }
};
