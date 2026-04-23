<?php

namespace App\Http\Controllers\Hijo;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use App\Models\Tarea;
use App\Models\TareaInstancia;
use App\Models\Recompensa;
use App\Models\Canje;
use App\Models\HistorialMonedas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function hijoActual(): Hijo
    {
        return Hijo::findOrFail(session('hijo_id'));
    }

    public function dashboard()
    {
        $hijo = $this->hijoActual();
        $this->generarInstanciasHoy($hijo);

        $instanciasHoy = TareaInstancia::with('tarea')
            ->where('id_hijo', $hijo->id_hijo)
            ->where('fecha_programada', today())
            ->whereIn('estado', ['PENDIENTE', 'COMPLETADA', 'RECHAZADA'])
            ->get();

        $tareasCompletadas = $instanciasHoy->where('estado', 'COMPLETADA')->count();
        $tareasTotal = $instanciasHoy->count();

        $canjesPendientes = Canje::with('recompensa')
            ->where('id_hijo', $hijo->id_hijo)
            ->whereIn('estado', ['PENDIENTE', 'APROBADO'])
            ->get();

        return view('hijo.dashboard', compact(
            'hijo', 'instanciasHoy', 'canjesPendientes', 'tareasCompletadas', 'tareasTotal'
        ));
    }

    public function completarTarea(TareaInstancia $instancia)
    {
        $hijo = $this->hijoActual();

        if ($instancia->id_hijo !== $hijo->id_hijo || $instancia->estado !== 'PENDIENTE') {
            abort(403);
        }

        $instancia->update([
            'estado' => 'COMPLETADA',
            'fecha_completada' => now(),
        ]);

        return back()->with('exito', '¡Genial! Has completado la tarea. Espera a que tu padre la valide.');
    }

    public function recompensas()
    {
        $hijo = $this->hijoActual();
        $recompensas = Recompensa::where('id_padre', $hijo->id_padre)
            ->where('activa', true)
            ->get();

        $canjesPendientes = Canje::where('id_hijo', $hijo->id_hijo)
            ->whereIn('estado', ['PENDIENTE', 'APROBADO'])
            ->with('recompensa')
            ->get();

        return view('hijo.recompensas', compact('hijo', 'recompensas', 'canjesPendientes'));
    }

    public function canjear(Recompensa $recompensa)
    {
        $hijo = $this->hijoActual();

        if ($recompensa->id_padre !== $hijo->id_padre || !$recompensa->activa) {
            abort(403);
        }

        if ($hijo->monedas < $recompensa->monedas_necesarias) {
            return back()->withErrors(['monedas' => 'No tienes suficientes monedas para esta recompensa.']);
        }

        DB::transaction(function () use ($hijo, $recompensa) {
            $saldoAnterior = $hijo->monedas;
            $saldoPosterior = $saldoAnterior - $recompensa->monedas_necesarias;

            $hijo->update(['monedas' => $saldoPosterior]);

            $canje = Canje::create([
                'id_hijo' => $hijo->id_hijo,
                'id_recompensa' => $recompensa->id_recompensa,
                'monedas_gastadas' => $recompensa->monedas_necesarias,
            ]);

            HistorialMonedas::create([
                'id_hijo' => $hijo->id_hijo,
                'cantidad' => -$recompensa->monedas_necesarias,
                'saldo_anterior' => $saldoAnterior,
                'saldo_posterior' => $saldoPosterior,
                'motivo' => 'CANJE',
                'id_referencia' => $canje->id_canje,
            ]);
        });

        return back()->with('exito', '¡Canje solicitado! Tu padre tiene que aprobarlo.');
    }

    private function generarInstanciasHoy(Hijo $hijo): void
    {
        $hoy = today();
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
                'DIARIA' => true,
                'SEMANAL' => $diaSemana === '1',
                'PERSONALIZADA' => in_array($diaSemana, $tarea->diasSemanaArray()),
                default => false,
            };

            if ($debeGenerarse) {
                TareaInstancia::firstOrCreate(
                    [
                        'id_tarea' => $tarea->id_tarea,
                        'id_hijo' => $hijo->id_hijo,
                        'fecha_programada' => $hoy,
                    ],
                    ['estado' => 'PENDIENTE']
                );
            }
        }
    }
}
