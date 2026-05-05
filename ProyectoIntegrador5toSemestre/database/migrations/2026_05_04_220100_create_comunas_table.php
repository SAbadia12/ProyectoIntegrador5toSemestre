<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabla 'comunas' - representa las 22 comunas urbanas de Cali.
     * Base para el mapa de calor (RF6).
     *
     * - latitud / longitud: centro aproximado de la comuna (para markers).
     * - geojson: polígono GeoJSON opcional para colorear el área completa
     *   en el mapa (Leaflet GeoJSON). Si está vacío, se muestra solo un círculo.
     */
    public function up(): void
    {
        Schema::create('comunas', function (Blueprint $table) {
            $table->id('id_comuna');
            $table->string('nombre');                       // Ej: "Comuna 1"
            $table->integer('numero')->unique();            // 1..22
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->longText('geojson')->nullable();        // polígono geo (opcional)
            $table->unsignedBigInteger('id_nivel_riesgo')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('id_nivel_riesgo')
                ->references('id_nivel_riesgo')->on('nivel_riesgos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunas');
    }
};
