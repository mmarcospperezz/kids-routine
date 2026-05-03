<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\TareaInstancia;
use App\Models\HistorialMonedas;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidacionController extends Controller
{
    public function index()
    {
        $hijoIds = Auth::user()->hijos()->where('activo', true)->pluck('id_hijo');

        $instancias = TareaInstancia::with(['tarea', 'hijo'])
            ->whereIn('id_hijo', $hijoIds)
            ->where('estado', 'COMPLETADA')
            ->orderBy('fecha_completada', 'desc')
            ->get();

        return view('padre.validaciones', compact('instancias'));
    }

    public function validar(Request $request, TareaInstancia $instancia)
    {
        $this->autorizarInstancia($instancia);

        DB::transaction(function () use ($instancia) {
            $hijo = $instancia->hijo;
            $monedas = $instancia->tarea->monedas_recompensa;
            $saldoAnterior = $hijo->monedas;
            $saldoPosterior = $saldoAnterior + $monedas;

            if ($hijo->monedas_tope !== null) {
                $saldoPosterior = min($saldoPosterior, $hijo->monedas_tope);
                $monedas = $saldoPosterior - $saldoAnterior;
            }

            $hijo->update([
                'monedas'            => $saldoPosterior,
                'monedas_historicas' => ($hijo->monedas_historicas ?? 0) + $monedas,
            ]);

            HistorialMonedas::create([
                'id_hijo'        => $hijo->id_hijo,
                'cantidad'       => $monedas,
                'saldo_anterior' => $saldoAnterior,
                'saldo_posterior'=> $saldoPosterior,
                'motivo'         => 'TAREA',
                'id_referencia'  => $instancia->id_instancia,
            ]);

            $instancia->update([
                'estado'         => 'VALIDADA',
                'fecha_validada' => now(),
            ]);
        });

        // Logros y racha (fuera de la transacción principal para no bloquear)
        try {
            app(AchievementService::class)->onTareaValidada($instancia->hijo);
        } catch (\Throwable) {}

        return back()->with('exito', "✅ Tarea validada. Se han añadido las monedas.");
    }

    public function rechazar(Request $request, TareaInstancia $instancia)
    {
        $this->autorizarInstancia($instancia);

        $request->validate(['comentario' => 'nullable|string|max:500']);

        $instancia->update([
            'estado'           => 'RECHAZADA',
            'fecha_validada'   => now(),
            'comentario_padre' => $request->comentario,
        ]);

        return back()->with('exito', 'Tarea rechazada.');
    }

    private function autorizarInstancia(TareaInstancia $instancia): void
    {
        if ($instancia->hijo->id_padre !== Auth::id()) {
            abort(403);
        }
        if ($instancia->estado !== 'COMPLETADA') {
            abort(400, 'Esta tarea no está en estado COMPLETADA.');
        }
    }
}
