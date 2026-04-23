<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_juegos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_padre');
            $table->string('juego', 50);
            $table->unsignedSmallInteger('monedas_por_partida')->default(5);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['id_padre', 'juego']);
            $table->foreign('id_padre')
                  ->references('id')->on('usuarios')
                  ->onDelete('cascade');
        });

        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hijo');
            $table->string('juego', 50);
            $table->unsignedSmallInteger('monedas_ganadas')->default(0);
            $table->timestamps();

            $table->foreign('id_hijo')
                  ->references('id_hijo')->on('hijos')
                  ->onDelete('cascade');
        });

        // Añadir 'JUEGO' al enum motivo de historial_monedas
        DB::statement("ALTER TABLE historial_monedas MODIFY motivo ENUM('TAREA','ACTIVIDAD','CANJE','AJUSTE_PADRE','BONIFICACION','JUEGO')");
    }

    public function down(): void
    {
        Schema::dropIfExists('partidas');
        Schema::dropIfExists('configuracion_juegos');
        DB::statement("ALTER TABLE historial_monedas MODIFY motivo ENUM('TAREA','ACTIVIDAD','CANJE','AJUSTE_PADRE','BONIFICACION')");
    }
};
