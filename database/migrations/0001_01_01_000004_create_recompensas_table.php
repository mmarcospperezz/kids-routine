<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recompensas', function (Blueprint $table) {
            $table->id('id_recompensa');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->integer('monedas_necesarias');
            $table->string('imagen_url', 255)->nullable();
            $table->boolean('activa')->default(true);
            $table->unsignedBigInteger('id_padre');

            $table->foreign('id_padre')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recompensas');
    }
};
