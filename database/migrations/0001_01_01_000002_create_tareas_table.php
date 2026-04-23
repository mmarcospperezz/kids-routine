<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id('id_tarea');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->integer('monedas_recompensa')->default(0);
            $table->enum('tipo', ['PUNTUAL', 'RECURRENTE']);
            $table->enum('recurrencia', ['DIARIA', 'SEMANAL', 'PERSONALIZADA'])->nullable();
            $table->string('dias_semana', 20)->nullable();
            $table->enum('estado', ['ACTIVA', 'ARCHIVADA'])->default('ACTIVA');
            $table->datetime('fecha_creacion')->useCurrent();
            $table->date('fecha_fin')->nullable();
            $table->unsignedBigInteger('id_hijo');

            $table->foreign('id_hijo')
                  ->references('id_hijo')
                  ->on('hijos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
