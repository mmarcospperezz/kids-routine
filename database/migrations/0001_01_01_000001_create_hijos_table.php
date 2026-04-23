<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hijos', function (Blueprint $table) {
            $table->id('id_hijo');
            $table->string('nombre', 100);
            $table->integer('edad');
            $table->string('avatar', 255)->nullable();
            $table->string('pin_hash', 255);
            $table->integer('monedas')->default(0);
            $table->integer('monedas_tope')->nullable();
            $table->integer('intentos_fallidos')->default(0);
            $table->datetime('bloqueado_hasta')->nullable();
            $table->unsignedBigInteger('id_padre');
            $table->boolean('activo')->default(true);

            $table->foreign('id_padre')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hijos');
    }
};
