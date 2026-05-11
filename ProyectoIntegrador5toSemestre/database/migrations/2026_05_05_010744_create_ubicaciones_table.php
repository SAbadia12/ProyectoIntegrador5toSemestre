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
     *   - 1 Nivel de riesgo    (FK id_nivel → nivel_riesgos.id_nivel_riesgo)
     *   - 1 Punto Cardinal     (FK id_punto_cardinal → puntos_cardinales.id_punto_cardinal)
     *   - 1 Subzona            (FK id_subzona → subzonas.id_subzona)
     *
     * Y puede tener 1:N con Delitos (many-to-many).
     */
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id('id_ubicacion');
            $table->string('direccion');
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);

            $table->unsignedBigInteger('id_nivel');
            $table->unsignedBigInteger('id_punto_cardinal');
            $table->unsignedBigInteger('id_subzona');

            $table->timestamps();

            $table->foreign('id_nivel')
                ->references('id_nivel_riesgo')->on('nivel_riesgos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('id_punto_cardinal')
                ->references('id_punto_cardinal')->on('puntos_cardinales')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('id_subzona')
                ->references('id_subzona')->on('subzonas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};
