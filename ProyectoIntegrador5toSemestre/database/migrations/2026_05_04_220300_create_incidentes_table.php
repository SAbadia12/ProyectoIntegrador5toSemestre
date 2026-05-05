<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla 'incidentes' - registros de delitos reportados.
     * Base para el Dashboard del Analista (RF10).
     *
     * Tipos de delito (campo 'tipo') manejados como string para simplicidad:
     *   - Hurto a personas
     *   - Hurto a residencias
     *   - Hurto a comercio
     *   - Hurto de vehículos
     *   - Hurto de motos
     *   - Homicidio
     *   - Lesiones personales
     *   - Violencia intrafamiliar
     *   - Extorsión
     *   - Secuestro
     *
     * Gravedad: 1 (leve) a 3 (grave)
     */
    public function up(): void
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id('id_incidente');
            $table->string('tipo');
            $table->unsignedBigInteger('id_comuna');
            $table->date('fecha');
            $table->tinyInteger('gravedad')->default(1);   // 1=leve, 2=medio, 3=grave
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('id_comuna')
                ->references('id_comuna')->on('comunas')
                ->cascadeOnDelete();

            $table->index('tipo');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
