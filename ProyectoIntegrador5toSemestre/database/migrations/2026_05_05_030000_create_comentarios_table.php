<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla 'comentarios' - RF5, RF11, RF13
 *
 * Permite que los visitantes dejen comentarios sobre la plataforma.
 * El moderador los puede aprobar/rechazar/eliminar.
 *
 * estado:
 *   - pendiente (default, recién creado)
 *   - aprobado  (visible públicamente)
 *   - rechazado (oculto)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id('id_comentario');
            $table->string('nombre', 100);
            $table->string('email', 150)->nullable();
            $table->text('contenido');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->timestamps();

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
