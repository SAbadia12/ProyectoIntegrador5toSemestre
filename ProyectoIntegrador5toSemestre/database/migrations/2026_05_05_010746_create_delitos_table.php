<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla 'delitos' - registros de tipos de delitos reportados.
     * Base para el Dashboard del Analista (RF10).
     *
     * Gravedad: 1 (leve) a 3 (grave)
     */
    public function up(): void
    {
        Schema::create('delitos', function (Blueprint $table) {
            $table->id('id_delito');
            $table->string('tipo');
            $table->unsignedBigInteger('id_ubicacion');
            $table->date('fecha');
            $table->tinyInteger('gravedad')->default(1);   // 1=leve, 2=medio, 3=grave
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('id_ubicacion')
                ->references('id_ubicacion')->on('ubicaciones')
                ->cascadeOnDelete();

            $table->index('tipo');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delitos');
    }
};
