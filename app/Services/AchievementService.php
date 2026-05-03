<?php

namespace App\Services;

use App\Models\Hijo;
use App\Models\Logro;
use App\Models\HistorialMonedas;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    public function onTareaValidada(Hijo $hijo): array
    {
        $nuevos = [];
        $hijo->refresh();

        $nuevos = array_merge($nuevos, $this->comprobarRacha($hijo));
        $nuevos = array_merge($nuevos, $this->comprobarLogrosTareas($hijo));
        $nuevos = array_merge($nuevos, $this->comprobarNivel($hijo));

        return $nuevos;
    }

    public function onPartidaGanada(Hijo $hijo): array
    {
        $hijo->refresh();
        return $this->comprobarLogrosJuegos($hijo);
    }

    public function onPrimerCanje(Hijo $hijo): array
    {
        $hijo->refresh();
        return $this->otorgarLogro($hijo, 'PRIMER_CANJE');
    }

    private function comprobarRacha(Hijo $hijo): array
    {
        $hoy = today();
        $nuevos = [];

        $ayer = $hoy->copy()->subDay();
        $ultimoDia = $hijo->racha_ultimo_dia;

        if ($ultimoDia && $ultimoDia->eq($hoy)) {
            return [];
        }

        $rachaActual = ($ultimoDia && $ultimoDia->eq($ayer))
            ? $hijo->racha_actual + 1
            : 1;

        $rachaMax = max($hijo->racha_max ?? 0, $rachaActual);

        DB::transaction(function () use ($hijo, $rachaActual, $rachaMax, $hoy, &$nuevos) {
            $hijo->update([
                'racha_actual'    => $rachaActual,
                'racha_max'       => $rachaMax,
                'racha_ultimo_dia'=> $hoy,
            ]);

            foreach ([3 => 'RACHA_3', 7 => 'RACHA_7', 30 => 'RACHA_30'] as $dias => $clave) {
                if ($rachaActual === $dias) {
                    $nuevos = array_merge($nuevos, $this->otorgarLogro($hijo, $clave));
                }
            }

            // Bonus automático en días 7 y 30
            if (in_array($rachaActual, [7, 30])) {
                $bonus = $rachaActual === 7 ? 10 : 30;
                $this->darMonedas($hijo, $bonus, 'RACHA', null,
                    "Bonus racha {$rachaActual} días");
            }
        });

        return $nuevos;
    }

    private function comprobarLogrosTareas(Hijo $hijo): array
    {
        $total = $hijo->instancias()
            ->where('estado', 'VALIDADA')
            ->count();

        $nuevos = [];
        foreach ([10 => 'TAREAS_10', 50 => 'TAREAS_50', 100 => 'TAREAS_100'] as $n => $clave) {
            if ($total >= $n) {
                $nuevos = array_merge($nuevos, $this->otorgarLogro($hijo, $clave));
            }
        }
        return $nuevos;
    }

    private function comprobarLogrosJuegos(Hijo $hijo): array
    {
        $total = $hijo->partidas()->count();
        $nuevos = [];
        foreach ([5 => 'JUEGOS_5', 20 => 'JUEGOS_20'] as $n => $clave) {
            if ($total >= $n) {
                $nuevos = array_merge($nuevos, $this->otorgarLogro($hijo, $clave));
            }
        }
        return $nuevos;
    }

    private function comprobarNivel(Hijo $hijo): array
    {
        $hijo->refresh();
        if ($hijo->nivel() >= 5) {
            return $this->otorgarLogro($hijo, 'NIVEL_5');
        }
        return [];
    }

    private function otorgarLogro(Hijo $hijo, string $clave): array
    {
        $logro = Logro::where('clave', $clave)->first();
        if (!$logro) return [];

        $yaObtenido = DB::table('hijo_logros')
            ->where('id_hijo', $hijo->id_hijo)
            ->where('id_logro', $logro->id_logro)
            ->exists();

        if ($yaObtenido) return [];

        DB::table('hijo_logros')->insert([
            'id_hijo'        => $hijo->id_hijo,
            'id_logro'       => $logro->id_logro,
            'fecha_obtenido' => now(),
        ]);

        if ($logro->bonus_monedas > 0) {
            $this->darMonedas($hijo, $logro->bonus_monedas, 'LOGRO', $logro->id_logro,
                "Logro: {$logro->titulo}");
        }

        return [$logro];
    }

    private function darMonedas(Hijo $hijo, int $cantidad, string $motivo, ?int $ref, string $desc): void
    {
        $hijo->refresh();
        $anterior = $hijo->monedas;
        $posterior = $anterior + $cantidad;
        $historicas = ($hijo->monedas_historicas ?? 0) + $cantidad;

        $hijo->update([
            'monedas'            => $posterior,
            'monedas_historicas' => $historicas,
        ]);

        HistorialMonedas::create([
            'id_hijo'       => $hijo->id_hijo,
            'cantidad'      => $cantidad,
            'saldo_anterior'  => $anterior,
            'saldo_posterior' => $posterior,
            'motivo'        => $motivo,
            'id_referencia' => $ref,
        ]);
    }
}
