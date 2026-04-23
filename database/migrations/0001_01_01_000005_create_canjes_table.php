<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canjes', function (Blueprint $table) {
            $table->id('id_canje');
            $table->unsignedBigInteger('id_hijo');
            $table->unsignedBigInteger('id_recompensa');
            $table->integer('monedas_gastadas');
            $table->datetime('fecha_solicitud')->useCurrent();
            $table->datetime('fecha_resolucion')->nullable();
            $table->enum('estado', ['PENDIENTE', 'APROBADO', 'RECHAZADO', 'ENTREGADO'])
                  ->default('PENDIENTE');
            $table->string('comentario', 500)->nullable();

            $table->foreign('id_hijo')
                  ->references('id_hijo')
                  ->on('hijos')
                  ->onDelete('cascade');

            $table->foreign('id_recompensa')
                  ->references('id_recompensa')
                  ->on('recompensas')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canjes');
    }
};
