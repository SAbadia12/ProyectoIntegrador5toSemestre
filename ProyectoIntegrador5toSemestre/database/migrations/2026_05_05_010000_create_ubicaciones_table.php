<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla 'ubicaciones' - sigue el MER del proyecto.
     *
     * Una Ubicación pertenece a:
     *   - 1 Nivel de riesgo  (FK id_nivel → nivel_riesgos.id_nivel_riesgo)
     *   - 1 Punto Cardinal   (FK id_punto_cardinal → puntos_cardinales.id_punto_cardinal)
     *   - 1 Zona             (FK id_zona → zonas.id_zona)
     *
     * Y puede tener 1:N con Lugares de Denuncia (siguiente entidad del MER).
     */
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id('id_ubicacion');
            $table->string('direccion');

            $table->unsignedBigInteger('id_nivel');
            $table->unsignedBigInteger('id_punto_cardinal');
            $table->unsignedBigInteger('id_zona');

            $table->timestamps();

            $table->foreign('id_nivel')
                ->references('id_nivel_riesgo')->on('nivel_riesgos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('id_punto_cardinal')
                ->references('id_punto_cardinal')->on('puntos_cardinales')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('id_zona')
                ->references('id_zona')->on('zonas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};
