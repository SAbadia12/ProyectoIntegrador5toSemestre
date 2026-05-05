<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla 'estaciones_policia' para RF7 (ubicar en mapa) y
     * RF12 (mostrar dirección y teléfono al hacer clic).
     */
    public function up(): void
    {
        Schema::create('estaciones_policia', function (Blueprint $table) {
            $table->id('id_estacion');
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->unsignedBigInteger('id_comuna')->nullable();
            $table->timestamps();

            $table->foreign('id_comuna')
                ->references('id_comuna')->on('comunas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estaciones_policia');
    }
};
