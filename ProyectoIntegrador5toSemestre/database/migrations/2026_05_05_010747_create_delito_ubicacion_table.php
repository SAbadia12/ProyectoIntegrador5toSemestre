<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tabla pivot 'delito_ubicacion' para la relación many-to-many
     * entre Delitos y Ubicaciones.
     *
     * Un delito puede ocurrir en múltiples ubicaciones
     * Una ubicación puede tener múltiples delitos
     */
    public function up(): void
    {
        Schema::create('delito_ubicacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_delito');
            $table->unsignedBigInteger('id_ubicacion');
            $table->timestamps();

            // Relaciones foráneas
            $table->foreign('id_delito')
                ->references('id_delito')->on('delitos')
                ->cascadeOnDelete();

            $table->foreign('id_ubicacion')
                ->references('id_ubicacion')->on('ubicaciones')
                ->cascadeOnDelete();

            // Índices para mejorar rendimiento
            $table->index(['id_delito', 'id_ubicacion']);
            $table->unique(['id_delito', 'id_ubicacion']);
        });

        // Migrar datos existentes desde delitos.id_ubicacion a la tabla pivot
        if (Schema::hasColumn('delitos', 'id_ubicacion')) {
            DB::statement('
                INSERT INTO delito_ubicacion (id_delito, id_ubicacion, created_at, updated_at)
                SELECT id_delito, id_ubicacion, NOW(), NOW()
                FROM delitos
                WHERE id_ubicacion IS NOT NULL
            ');
        }

        // Remover la FK y columna id_ubicacion de delitos
        if (Schema::hasColumn('delitos', 'id_ubicacion')) {
            Schema::table('delitos', function (Blueprint $table) {
                $table->dropForeign(['id_ubicacion']);
                $table->dropColumn('id_ubicacion');
            });
        }
    }

    public function down(): void
    {
        // Recrear la columna id_ubicacion en delitos
        Schema::table('delitos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ubicacion')->after('tipo')->nullable();

            $table->foreign('id_ubicacion')
                ->references('id_ubicacion')->on('ubicaciones')
                ->cascadeOnDelete();
        });

        // Migrar datos de vuelta desde la tabla pivot
        DB::statement('
            UPDATE delitos d
            SET d.id_ubicacion = (
                SELECT id_ubicacion FROM delito_ubicacion
                WHERE id_delito = d.id_delito
                LIMIT 1
            )
        ');

        // Eliminar tabla pivot
        Schema::dropIfExists('delito_ubicacion');
    }
};
