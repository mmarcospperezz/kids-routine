<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use App\Models\Tarea;
use App\Models\TareaInstancia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    public function index()
    {
        $hijos = Auth::user()->hijos()->where('activo', true)
            ->with(['tareas' => fn($q) => $q->where('estado', 'ACTIVA')])
            ->get();
        return view('padre.tareas.index', compact('hijos'));
    }

    public function create()
    {
        $hijos = Auth::user()->hijos()->where('activo', true)->get();
        return view('padre.tareas.create', compact('hijos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_hijo'            => 'required|exists:hijos,id_hijo',
            'titulo'             => 'required|string|max:200',
            'descripcion'        => 'nullable|string|max:500',
            'monedas_recompensa' => 'required|integer|min:0|max:9999',
            'tipo'               => 'required|in:PUNTUAL,RECURRENTE',
            'recurrencia'        => 'required_if:tipo,RECURRENTE|nullable|in:DIARIA,SEMANAL,PERSONALIZADA',
            'dias_semana'        => 'required_if:recurrencia,PERSONALIZADA|nullable',
            'fecha_fin'          => 'nullable|date|after:today',
            'franja'             => 'nullable|in:CUALQUIERA,MANANA,TARDE,NOCHE',
            'categoria'          => 'nullable|string|max:60',
        ]);

        $hijo = Hijo::findOrFail($data['id_hijo']);
        if ($hijo->id_padre !== Auth::id()) {
            abort(403);
        }

        $tarea = Tarea::create($data);

        if ($tarea->tipo === 'PUNTUAL') {
            TareaInstancia::create([
                'id_tarea' => $tarea->id_tarea,
                'id_hijo' => $tarea->id_hijo,
                'fecha_programada' => today(),
            ]);
        }

        return redirect()->route('padre.tareas.index')
            ->with('exito', "Tarea '{$tarea->titulo}' creada correctamente.");
    }

    public function destroy(Tarea $tarea)
    {
        if ($tarea->hijo->id_padre !== Auth::id()) {
            abort(403);
        }
        $tarea->update(['estado' => 'ARCHIVADA']);
        return back()->with('exito', 'Tarea archivada.');
    }
}
