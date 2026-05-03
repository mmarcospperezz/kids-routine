<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecompensaController extends Controller
{
    public function index()
    {
        $recompensas = Auth::user()->recompensas()->where('activa', true)->get();
        return view('padre.recompensas.index', compact('recompensas'));
    }

    public function create()
    {
        return view('padre.recompensas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'             => 'required|string|max:150',
            'descripcion'        => 'nullable|string|max:500',
            'monedas_necesarias' => 'required|integer|min:1|max:99999',
            'tipo'               => 'nullable|in:FISICA,VIRTUAL',
            'recurrente'         => 'nullable|boolean',
        ]);
        $data['tipo']       = $data['tipo'] ?? 'FISICA';
        $data['recurrente'] = $request->boolean('recurrente');

        Auth::user()->recompensas()->create($data);

        return redirect()->route('padre.recompensas.index')
            ->with('exito', "Recompensa '{$data['nombre']}' creada correctamente.");
    }

    public function destroy(Recompensa $recompensa)
    {
        if ($recompensa->id_padre !== Auth::id()) {
            abort(403);
        }
        $recompensa->update(['activa' => false]);
        return back()->with('exito', 'Recompensa eliminada.');
    }
}
