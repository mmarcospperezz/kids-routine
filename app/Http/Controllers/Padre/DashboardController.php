<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\TareaInstancia;
use App\Models\Canje;
use App\Services\TareaInstanciaService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $padre = Auth::user();
        $hijos = $padre->hijos()->where('activo', true)->get();
        $hijoIds = $hijos->pluck('id_hijo');

        // Generar instancias del día para todos los hijos activos
        $service = app(TareaInstanciaService::class);
        foreach ($hijos as $hijo) {
            $service->generarInstanciasHoy($hijo);
        }

        $pendientesValidacion = TareaInstancia::whereIn('id_hijo', $hijoIds)
            ->where('estado', 'COMPLETADA')
            ->count();

        $pendientesCanjes = Canje::whereIn('id_hijo', $hijoIds)
            ->where('estado', 'PENDIENTE')
            ->count();

        $tareasHoy = TareaInstancia::whereIn('id_hijo', $hijoIds)
            ->where('fecha_programada', today())
            ->with(['tarea', 'hijo'])
            ->get();

        return view('padre.dashboard', compact(
            'padre', 'hijos', 'pendientesValidacion', 'pendientesCanjes', 'tareasHoy'
        ));
    }
}
