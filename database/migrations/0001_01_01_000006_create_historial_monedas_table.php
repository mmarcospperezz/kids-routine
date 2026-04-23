<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_monedas', function (Blueprint $table) {
            $table->id('id_historia');
            $table->unsignedBigInteger('id_hijo');
            $table->integer('cantidad');
            $table->integer('saldo_anterior');
            $table->integer('saldo_posterior');
            $table->enum('motivo', ['TAREA', 'ACTIVIDAD', 'CANJE', 'AJUSTE_PADRE', 'BONIFICACION']);
            $table->unsignedBigInteger('id_referencia')->nullable();
            $table->datetime('fecha')->useCurrent();

            $table->foreign('id_hijo')
                  ->references('id_hijo')
                  ->on('hijos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_monedas');
    }
};
