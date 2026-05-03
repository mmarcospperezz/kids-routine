<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Hijos: rachas, niveles, monedas históricas, dark_mode ───────────
        if (!Schema::hasColumn('hijos', 'racha_actual')) {
            Schema::table('hijos', function (Blueprint $table) {
                $table->unsignedSmallInteger('racha_actual')->default(0)->after('activo');
                $table->unsignedSmallInteger('racha_max')->default(0)->after('racha_actual');
                $table->date('racha_ultimo_dia')->nullable()->after('racha_max');
                $table->unsignedInteger('monedas_historicas')->default(0)->after('racha_ultimo_dia');
            });
        }

        // ─── Usuarios: modo oscuro ────────────────────────────────────────────
        if (!Schema::hasColumn('usuarios', 'dark_mode')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->boolean('dark_mode')->default(false)->after('activo');
            });
        }

        // ─── Tareas: franja horaria y categoría ───────────────────────────────
        if (!Schema::hasColumn('tareas', 'franja')) {
            Schema::table('tareas', function (Blueprint $table) {
                $table->enum('franja', ['CUALQUIERA', 'MANANA', 'TARDE', 'NOCHE'])
                      ->default('CUALQUIERA')->after('dias_semana');
                $table->string('categoria', 60)->nullable()->after('franja');
            });
        }

        // ─── Tarea instancias: foto prueba ────────────────────────────────────
        if (!Schema::hasColumn('tarea_instancias', 'foto_prueba')) {
            Schema::table('tarea_instancias', function (Blueprint $table) {
                $table->mediumText('foto_prueba')->nullable()->after('comentario_padre');
            });
        }

        // ─── Recompensas: tipo + recompensas virtuales ────────────────────────
        if (!Schema::hasColumn('recompensas', 'tipo')) {
            Schema::table('recompensas', function (Blueprint $table) {
                $table->enum('tipo', ['FISICA', 'VIRTUAL'])->default('FISICA')->after('activa');
                $table->boolean('recurrente')->default(false)->after('tipo');
            });
        }

        // ─── Canjes: fecha de caducidad ───────────────────────────────────────
        if (!Schema::hasColumn('canjes', 'fecha_caducidad')) {
            Schema::table('canjes', function (Blueprint $table) {
                $table->date('fecha_caducidad')->nullable()->after('estado');
            });
        }

        // ─── Tabla logros ─────────────────────────────────────────────────────
        if (!Schema::hasTable('logros')) {
            Schema::create('logros', function (Blueprint $table) {
                $table->id('id_logro');
                $table->string('clave', 60)->unique();
                $table->string('titulo', 100);
                $table->string('descripcion', 255);
                $table->string('icono', 10)->default('🏅');
                $table->unsignedSmallInteger('bonus_monedas')->default(0);
            });

            // Semilla de logros predefinidos
            DB::table('logros')->insert([
                ['clave' => 'TAREAS_10',     'titulo' => '10 tareas',         'descripcion' => 'Completa 10 tareas',                   'icono' => '✅', 'bonus_monedas' => 20],
                ['clave' => 'TAREAS_50',     'titulo' => '50 tareas',         'descripcion' => 'Completa 50 tareas',                   'icono' => '🌟', 'bonus_monedas' => 50],
                ['clave' => 'TAREAS_100',    'titulo' => '100 tareas',        'descripcion' => 'Completa 100 tareas',                  'icono' => '👑', 'bonus_monedas' => 100],
                ['clave' => 'JUEGOS_5',      'titulo' => '5 juegos ganados',  'descripcion' => 'Gana 5 partidas de juegos',            'icono' => '🎮', 'bonus_monedas' => 15],
                ['clave' => 'JUEGOS_20',     'titulo' => '20 juegos ganados', 'descripcion' => 'Gana 20 partidas de juegos',           'icono' => '🕹️', 'bonus_monedas' => 40],
                ['clave' => 'RACHA_3',       'titulo' => '3 días seguidos',   'descripcion' => 'Mantén una racha de 3 días',           'icono' => '🔥', 'bonus_monedas' => 10],
                ['clave' => 'RACHA_7',       'titulo' => 'Semana perfecta',   'descripcion' => 'Mantén una racha de 7 días',           'icono' => '🔥', 'bonus_monedas' => 30],
                ['clave' => 'RACHA_30',      'titulo' => 'Mes perfecto',      'descripcion' => 'Mantén una racha de 30 días',          'icono' => '💎', 'bonus_monedas' => 100],
                ['clave' => 'PRIMER_CANJE',  'titulo' => 'Primer canje',      'descripcion' => 'Canjea tu primera recompensa',         'icono' => '🎁', 'bonus_monedas' => 5],
                ['clave' => 'NIVEL_5',       'titulo' => 'Nivel 5',           'descripcion' => 'Alcanza el nivel 5',                   'icono' => '⭐', 'bonus_monedas' => 25],
            ]);
        }

        // ─── Tabla hijo_logros ────────────────────────────────────────────────
        if (!Schema::hasTable('hijo_logros')) {
            Schema::create('hijo_logros', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_hijo');
                $table->unsignedBigInteger('id_logro');
                $table->datetime('fecha_obtenido')->useCurrent();

                $table->unique(['id_hijo', 'id_logro']);
                $table->foreign('id_hijo')->references('id_hijo')->on('hijos')->onDelete('cascade');
                $table->foreign('id_logro')->references('id_logro')->on('logros')->onDelete('cascade');
            });
        }

        // ─── Tabla solicitudes_pin ────────────────────────────────────────────
        if (!Schema::hasTable('solicitudes_pin')) {
            Schema::create('solicitudes_pin', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_hijo');
                $table->string('nuevo_pin_hash', 255);
                $table->enum('estado', ['PENDIENTE', 'APROBADA', 'RECHAZADA'])->default('PENDIENTE');
                $table->timestamps();

                $table->foreign('id_hijo')->references('id_hijo')->on('hijos')->onDelete('cascade');
            });
        }

        // ─── historial_monedas: añadir motivo RACHA y LOGRO ──────────────────
        try {
            DB::statement("ALTER TABLE historial_monedas MODIFY COLUMN motivo ENUM('TAREA','ACTIVIDAD','CANJE','AJUSTE_PADRE','BONIFICACION','JUEGO','RACHA','LOGRO') NOT NULL");
        } catch (\Throwable) {}
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_pin');
        Schema::dropIfExists('hijo_logros');
        Schema::dropIfExists('logros');

        if (Schema::hasColumn('canjes', 'fecha_caducidad')) {
            Schema::table('canjes', fn(Blueprint $t) => $t->dropColumn('fecha_caducidad'));
        }
        if (Schema::hasColumn('recompensas', 'tipo')) {
            Schema::table('recompensas', fn(Blueprint $t) => $t->dropColumn(['tipo', 'recurrente']));
        }
        if (Schema::hasColumn('tarea_instancias', 'foto_prueba')) {
            Schema::table('tarea_instancias', fn(Blueprint $t) => $t->dropColumn('foto_prueba'));
        }
        if (Schema::hasColumn('tareas', 'franja')) {
            Schema::table('tareas', fn(Blueprint $t) => $t->dropColumn(['franja', 'categoria']));
        }
        if (Schema::hasColumn('usuarios', 'dark_mode')) {
            Schema::table('usuarios', fn(Blueprint $t) => $t->dropColumn('dark_mode'));
        }
        if (Schema::hasColumn('hijos', 'racha_actual')) {
            Schema::table('hijos', fn(Blueprint $t) => $t->dropColumn(['racha_actual', 'racha_max', 'racha_ultimo_dia', 'monedas_historicas']));
        }
    }
};
