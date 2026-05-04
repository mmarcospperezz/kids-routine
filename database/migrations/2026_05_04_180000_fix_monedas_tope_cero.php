<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // monedas_tope = 0 es semánticamente incorrecto (nunca 0 monedas).
        // Lo convertimos a NULL (sin tope) para no bloquear la suma de monedas.
        DB::statement("UPDATE hijos SET monedas_tope = NULL WHERE monedas_tope = 0");
    }

    public function down(): void {}
};
