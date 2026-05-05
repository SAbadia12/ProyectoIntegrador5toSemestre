<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subzonas', function (Blueprint $table) {
            $table->id('id_subzona');
            $table->string('subzona');
            $table->unsignedBigInteger('id_zona');
            $table->unsignedBigInteger('tipo_subzona');
            $table->foreign('id_zona')->references('id_zona')->on('zonas');
            $table->foreign('tipo_subzona')->references('id_subtipo')->on('subzonas_tipos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subzonas');
    }
};
