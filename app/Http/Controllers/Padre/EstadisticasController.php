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

        // Historial de monedas: tareas validadas + juegos (últimas 8 semanas)
        $monedasTareas = DB::table('tarea_instancias as ti')
            ->join('tareas as t', 'ti.id_tarea', '=', 't.id_tarea')
            ->join('hijos as h', 'ti.id_hijo', '=', 'h.id_hijo')
            ->selectRaw("t.monedas_recompensa as cantidad, t.titulo as descripcion, h.nombre as hijo_nombre, 'TAREA' as tipo, ti.fecha_validada as fecha, YEARWEEK(ti.fecha_validada, 1) as semana")
            ->whereIn('ti.id_hijo', $hijoIds)
            ->where('ti.estado', 'VALIDADA')
            ->where('t.monedas_recompensa', '>', 0)
            ->whereNotNull('ti.fecha_validada')
            ->where('ti.fecha_validada', '>=', now()->subWeeks(8))
            ->get();

        $monedasJuegos = DB::table('partidas as p')
            ->join('hijos as h', 'p.id_hijo', '=', 'h.id_hijo')
            ->selectRaw("p.monedas_ganadas as cantidad, p.juego as descripcion, h.nombre as hijo_nombre, 'JUEGO' as tipo, p.created_at as fecha, YEARWEEK(p.created_at, 1) as semana")
            ->whereIn('p.id_hijo', $hijoIds)
            ->where('p.monedas_ganadas', '>', 0)
            ->where('p.created_at', '>=', now()->subWeeks(8))
            ->get();

        $historialMonedas = $monedasTareas->concat($monedasJuegos)
            ->sortByDesc('fecha')
            ->groupBy('semana');

        // Historial de validaciones (últimas 8 semanas)
        $historialValidaciones = DB::table('tarea_instancias as ti')
            ->join('tareas as t', 'ti.id_tarea', '=', 't.id_tarea')
            ->join('hijos as h', 'ti.id_hijo', '=', 'h.id_hijo')
            ->selectRaw("t.titulo, h.nombre as hijo_nombre, t.monedas_recompensa as monedas, ti.fecha_validada as fecha, YEARWEEK(ti.fecha_validada, 1) as semana")
            ->whereIn('ti.id_hijo', $hijoIds)
            ->where('ti.estado', 'VALIDADA')
            ->whereNotNull('ti.fecha_validada')
            ->where('ti.fecha_validada', '>=', now()->subWeeks(8))
            ->orderByDesc('ti.fecha_validada')
            ->get()
            ->groupBy('semana');

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
            ->selectRaw('DATE_FORMAT(fecha_programada, "%Y-%m-%d") as fecha_dia, estado, COUNT(*) as n')
            ->groupBy('fecha_dia', 'estado')
            ->get()
            ->groupBy('fecha_dia');

        return view('padre.estadisticas', compact(
            'hijos', 'historialMonedas', 'historialValidaciones', 'tasaCompletado',
            'juegosMasJugados', 'partidasRecientes', 'instanciasMes', 'mesActual'
        ));
    }
}
