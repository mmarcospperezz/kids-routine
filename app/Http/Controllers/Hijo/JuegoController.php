<?php

namespace App\Http\Controllers\Hijo;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionJuego;
use App\Models\HistorialMonedas;
use App\Models\Hijo;
use App\Models\Partida;
use App\Support\Juegos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JuegoController extends Controller
{
    private function hijoActual(): Hijo
    {
        return Hijo::findOrFail(session('hijo_id'));
    }

    public function index()
    {
        $hijo = $this->hijoActual();

        $configs = ConfiguracionJuego::where('id_padre', $hijo->id_padre)
            ->get()
            ->keyBy('juego');

        $partidasHoy = Partida::where('id_hijo', $hijo->id_hijo)
            ->whereDate('created_at', today())
            ->get()
            ->groupBy('juego')
            ->map(fn($g) => $g->count());

        return view('hijo.juegos.index', compact('hijo', 'configs', 'partidasHoy'));
    }

    public function jugar(string $juego)
    {
        if (!array_key_exists($juego, Juegos::LIST)) {
            abort(404);
        }

        $hijo = $this->hijoActual();
        $config = ConfiguracionJuego::where('id_padre', $hijo->id_padre)
            ->where('juego', $juego)
            ->first();

        if ($config && !$config->activo) {
            return redirect()->route('hijo.juegos')->with('error', 'Este juego no está disponible.');
        }

        $jugadasHoy = Partida::where('id_hijo', $hijo->id_hijo)
            ->where('juego', $juego)
            ->whereDate('created_at', today())
            ->count();

        if ($jugadasHoy >= 3) {
            return redirect()->route('hijo.juegos')->with('error', '¡Ya jugaste 3 veces hoy! Vuelve mañana. 😴');
        }

        $monedas = $config ? $config->monedas_por_partida : 5;

        return view("hijo.juegos.{$juego}", compact('hijo', 'monedas'));
    }

    public function completar(Request $request, string $juego)
    {
        if (!array_key_exists($juego, Juegos::LIST)) {
            abort(404);
        }

        $hijo = $this->hijoActual();

        $jugadasHoy = Partida::where('id_hijo', $hijo->id_hijo)
            ->where('juego', $juego)
            ->whereDate('created_at', today())
            ->count();

        if ($jugadasHoy >= 3) {
            return redirect()->route('hijo.juegos')->with('error', 'Ya completaste este juego 3 veces hoy.');
        }

        $config = ConfiguracionJuego::where('id_padre', $hijo->id_padre)
            ->where('juego', $juego)
            ->first();

        $monedasBase  = $config ? $config->monedas_por_partida : 5;
        $saldoActual  = $hijo->monedas;
        $monedasGanar = $monedasBase;

        if ($hijo->monedas_tope && ($saldoActual + $monedasBase > $hijo->monedas_tope)) {
            $monedasGanar = max(0, $hijo->monedas_tope - $saldoActual);
        }

        $saldoNuevo = $saldoActual + $monedasGanar;

        DB::transaction(function () use ($hijo, $juego, $monedasGanar, $saldoActual, $saldoNuevo) {
            $hijo->update(['monedas' => $saldoNuevo]);

            $partida = Partida::create([
                'id_hijo'        => $hijo->id_hijo,
                'juego'          => $juego,
                'monedas_ganadas' => $monedasGanar,
            ]);

            if ($monedasGanar > 0) {
                HistorialMonedas::create([
                    'id_hijo'        => $hijo->id_hijo,
                    'cantidad'       => $monedasGanar,
                    'saldo_anterior' => $saldoActual,
                    'saldo_posterior' => $saldoNuevo,
                    'motivo'         => 'JUEGO',
                    'id_referencia'  => $partida->id,
                ]);
            }
        });

        $nombre = Juegos::LIST[$juego]['nombre'];
        $msg = $monedasGanar > 0
            ? "¡Fantástico! Completaste «{$nombre}» y ganaste {$monedasGanar} monedas. 🪙"
            : "¡Completaste el juego! Ya alcanzaste tu límite de monedas.";

        return redirect()->route('hijo.juegos')->with('exito', $msg);
    }
}
