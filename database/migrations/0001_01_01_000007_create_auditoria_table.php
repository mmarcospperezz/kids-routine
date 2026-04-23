<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id('id_auditoria');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_hijo')->nullable();
            $table->string('accion', 100);
            $table->string('entidad', 50)->nullable();
            $table->unsignedBigInteger('id_entidad')->nullable();
            $table->text('detalle')->nullable();
            $table->string('ip_origen', 45)->nullable();
            $table->datetime('fecha')->useCurrent();

            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('set null');

            $table->foreign('id_hijo')
                  ->references('id_hijo')
                  ->on('hijos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};
