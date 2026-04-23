<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionJuego;
use App\Support\Juegos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JuegoController extends Controller
{
    public function index()
    {
        $configs = ConfiguracionJuego::where('id_padre', Auth::id())
            ->get()
            ->keyBy('juego');

        return view('padre.juegos.index', compact('configs'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'juegos'                          => 'required|array',
            'juegos.*.monedas_por_partida'    => 'required|integer|min:1|max:50',
        ]);

        foreach ($request->input('juegos', []) as $slug => $data) {
            if (!array_key_exists($slug, Juegos::LIST)) {
                continue;
            }
            ConfiguracionJuego::updateOrCreate(
                ['id_padre' => Auth::id(), 'juego' => $slug],
                [
                    'monedas_por_partida' => (int) ($data['monedas_por_partida'] ?? 5),
                    'activo'              => !empty($data['activo']),
                ]
            );
        }

        return back()->with('exito', 'Configuración de juegos guardada.');
    }
}
