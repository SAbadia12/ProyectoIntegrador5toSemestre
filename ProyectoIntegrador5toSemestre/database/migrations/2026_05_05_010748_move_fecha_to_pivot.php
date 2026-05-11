<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mover la fecha de delitos a la tabla pivot delito_ubicacion
     * Ahora cada relación delito-ubicación tiene su propia fecha
     */
    public function up(): void
    {
        // Agregar fecha a la tabla pivot
        Schema::table('delito_ubicacion', function (Blueprint $table) {
            $table->date('fecha')->after('id_ubicacion')->nullable();
            $table->index('fecha');
        });

        // Copiar las fechas desde delitos a delito_ubicacion
        DB::statement('
            UPDATE delito_ubicacion du
            SET du.fecha = (
                SELECT d.fecha FROM delitos d WHERE d.id_delito = du.id_delito
            )
        ');

        // Remover fecha de delitos
        Schema::table('delitos', function (Blueprint $table) {
            $table->dropIndex(['fecha']);
            $table->dropColumn('fecha');
        });
    }

    public function down(): void
    {
        // Restaurar fecha en delitos
        Schema::table('delitos', function (Blueprint $table) {
            $table->date('fecha')->after('tipo');
        });

        // Copiar fechas de vuelta desde delito_ubicacion a delitos
        DB::statement('
            UPDATE delitos d
            SET d.fecha = (
                SELECT du.fecha FROM delito_ubicacion du WHERE du.id_delito = d.id_delito LIMIT 1
            )
        ');

        // Remover fecha de pivot
        Schema::table('delito_ubicacion', function (Blueprint $table) {
            $table->dropColumn('fecha');
        });
    }
};
