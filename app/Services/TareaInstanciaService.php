<?php

namespace App\Services;

use App\Models\Hijo;
use App\Models\Tarea;
use App\Models\TareaInstancia;

class TareaInstanciaService
{
    public function generarInstanciasHoy(Hijo $hijo): void
    {
        $hoy       = today();
        $diaSemana = (string) $hoy->dayOfWeekIso;

        $tareas = Tarea::where('id_hijo', $hijo->id_hijo)
            ->where('estado', 'ACTIVA')
            ->where('tipo', 'RECURRENTE')
            ->where(function ($q) use ($hoy) {
                $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $hoy);
            })
            ->get();

        foreach ($tareas as $tarea) {
            $debeGenerarse = match ($tarea->recurrencia) {
                'DIARIA'        => true,
                'SEMANAL'       => $diaSemana === '1',
                'PERSONALIZADA' => in_array($diaSemana, $tarea->diasSemanaArray()),
                default         => false,
            };

            if ($debeGenerarse) {
                TareaInstancia::firstOrCreate(
                    [
                        'id_tarea'         => $tarea->id_tarea,
                        'id_hijo'          => $hijo->id_hijo,
                        'fecha_programada' => $hoy,
                    ],
                    ['estado' => 'PENDIENTE']
                );
            }
        }
    }
}
