<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\TareaInstancia;
use App\Models\Partida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function index()
    {
        $padre = Auth::user();
        $hijos = $padre->hijos()->where('activo', true)->get();
        $hijoIds = $hijos->pluck('id_hijo');

        // Monedas ganadas por semana (últimas 8 semanas)
        $monedasSemana = DB::table('historial_monedas')
            ->selectRaw('YEARWEEK(fecha, 1) as semana, SUM(cantidad) as total')
            ->whereIn('id_hijo', $hijoIds)
            ->where('cantidad', '>', 0)
            ->where('fecha', '>=', now()->subWeeks(8))
            ->groupByRaw('YEARWEEK(fecha, 1)')
            ->orderBy('semana')
            ->get()
            ->mapWithKeys(fn($r) => [$r->semana => $r->total]);

        // Tasa de completado por hijo
        $tasaCompletado = $hijos->map(function ($hijo) {
            $total     = $hijo->instancias()->count();
            $validadas = $hijo->instancias()->where('estado', 'VALIDADA')->count();
            return [
                'nombre'    => $hijo->nombre,
                'total'     => $total,
                'validadas' => $validadas,
                'tasa'      => $total > 0 ? round($validadas / $total * 100) : 0,
                'racha'     => $hijo->racha_actual ?? 0,
                'nivel'     => $hijo->nivel(),
            ];
        });

        // Juegos más jugados
        $juegosMasJugados = Partida::whereIn('id_hijo', $hijoIds)
            ->selectRaw('juego, COUNT(*) as partidas, SUM(monedas_ganadas) as monedas')
            ->groupBy('juego')
            ->orderByDesc('partidas')
            ->limit(5)
            ->get();

        // Historial de partidas recientes
        $partidasRecientes = Partida::with('hijo')
            ->whereIn('id_hijo', $hijoIds)
            ->latest()
            ->limit(20)
            ->get();

        // Calendario: tareas del mes actual (día => estado más frecuente)
        $mesActual = now()->startOfMonth();
        $instanciasMes = TareaInstancia::whereIn('id_hijo', $hijoIds)
            ->whereBetween('fecha_programada', [$mesActual, now()->endOfMonth()])
            ->selectRaw('fecha_programada, estado, COUNT(*) as n')
            ->groupBy('fecha_programada', 'estado')
            ->get()
            ->groupBy('fecha_programada');

        return view('padre.estadisticas', compact(
            'hijos', 'monedasSemana', 'tasaCompletado',
            'juegosMasJugados', 'partidasRecientes', 'instanciasMes', 'mesActual'
        ));
    }
}
