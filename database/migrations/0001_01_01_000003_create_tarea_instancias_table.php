<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarea_instancias', function (Blueprint $table) {
            $table->id('id_instancia');
            $table->unsignedBigInteger('id_tarea');
            $table->unsignedBigInteger('id_hijo');
            $table->date('fecha_programada');
            $table->enum('estado', ['PENDIENTE', 'COMPLETADA', 'VALIDADA', 'RECHAZADA', 'CADUCADA'])
                  ->default('PENDIENTE');
            $table->datetime('fecha_completada')->nullable();
            $table->datetime('fecha_validada')->nullable();
            $table->string('comentario_padre', 500)->nullable();

            $table->foreign('id_tarea')
                  ->references('id_tarea')
                  ->on('tareas')
                  ->onDelete('cascade');

            $table->foreign('id_hijo')
                  ->references('id_hijo')
                  ->on('hijos')
                  ->onDelete('cascade');

            $table->unique(['id_tarea', 'id_hijo', 'fecha_programada'], 'uk_tarea_hijo_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarea_instancias');
    }
};
