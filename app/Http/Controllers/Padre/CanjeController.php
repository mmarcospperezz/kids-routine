<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\Canje;
use App\Models\HistorialMonedas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CanjeController extends Controller
{
    public function index()
    {
        $hijoIds = Auth::user()->hijos()->where('activo', true)->pluck('id_hijo');

        $canjes = Canje::with(['hijo', 'recompensa'])
            ->whereIn('id_hijo', $hijoIds)
            ->orderByRaw("FIELD(estado, 'PENDIENTE', 'APROBADO', 'RECHAZADO', 'ENTREGADO')")
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        return view('padre.canjes', compact('canjes'));
    }

    public function aprobar(Canje $canje)
    {
        $this->autorizarCanje($canje);

        $canje->update([
            'estado' => 'APROBADO',
            'fecha_resolucion' => now(),
        ]);

        return back()->with('exito', '¡Canje aprobado! Recuerda entregar la recompensa.');
    }

    public function rechazar(Request $request, Canje $canje)
    {
        $this->autorizarCanje($canje);
        $request->validate(['comentario' => 'nullable|string|max:500']);

        DB::transaction(function () use ($canje, $request) {
            $hijo = $canje->hijo;
            $saldoAnterior = $hijo->monedas;
            $saldoPosterior = $saldoAnterior + $canje->monedas_gastadas;

            $hijo->update(['monedas' => $saldoPosterior]);

            HistorialMonedas::create([
                'id_hijo' => $hijo->id_hijo,
                'cantidad' => $canje->monedas_gastadas,
                'saldo_anterior' => $saldoAnterior,
                'saldo_posterior' => $saldoPosterior,
                'motivo' => 'AJUSTE_PADRE',
                'id_referencia' => $canje->id_canje,
            ]);

            $canje->update([
                'estado' => 'RECHAZADO',
                'fecha_resolucion' => now(),
                'comentario' => $request->comentario,
            ]);
        });

        return back()->with('exito', 'Canje rechazado. Las monedas han sido devueltas.');
    }

    public function entregar(Canje $canje)
    {
        $this->autorizarCanje($canje, 'APROBADO');

        $canje->update([
            'estado' => 'ENTREGADO',
            'fecha_resolucion' => now(),
        ]);

        return back()->with('exito', '¡Recompensa marcada como entregada!');
    }

    private function autorizarCanje(Canje $canje, string $estadoRequerido = 'PENDIENTE'): void
    {
        if ($canje->hijo->id_padre !== Auth::id()) {
            abort(403);
        }
        if ($canje->estado !== $estadoRequerido) {
            abort(400);
        }
    }
}
