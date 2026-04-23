<?php

namespace App\Http\Controllers\Hijo;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SesionController extends Controller
{
    public function seleccionar()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $hijos = Auth::user()->hijos()->where('activo', true)->get();
        return view('hijo.seleccionar', compact('hijos'));
    }

    public function mostrarPin(Request $request)
    {
        $request->validate(['id_hijo' => 'required|exists:hijos,id_hijo']);
        $hijo = Hijo::findOrFail($request->id_hijo);

        if ($hijo->id_padre !== Auth::id()) {
            abort(403);
        }

        return view('hijo.pin', compact('hijo'));
    }

    public function verificarPin(Request $request)
    {
        $request->validate([
            'id_hijo' => 'required|exists:hijos,id_hijo',
            'pin' => 'required|digits:4',
        ]);

        $hijo = Hijo::findOrFail($request->id_hijo);

        if ($hijo->id_padre !== Auth::id()) {
            abort(403);
        }

        if ($hijo->estaBloqueado()) {
            $minutos = (int) now()->diffInMinutes($hijo->bloqueado_hasta);
            return back()
                ->withErrors(['pin' => "Demasiados intentos fallidos. Espera {$minutos} minutos."])
                ->with('id_hijo', $hijo->id_hijo);
        }

        if (!Hash::check($request->pin, $hijo->pin_hash)) {
            $intentos = $hijo->intentos_fallidos + 1;
            $update = ['intentos_fallidos' => $intentos];

            if ($intentos >= 3) {
                $update['bloqueado_hasta'] = now()->addMinutes(15);
                $update['intentos_fallidos'] = 0;
            }

            $hijo->update($update);

            $restantes = max(0, 3 - $intentos);
            return back()
                ->withErrors(['pin' => "PIN incorrecto. Te quedan {$restantes} intentos."])
                ->with('id_hijo', $hijo->id_hijo);
        }

        $hijo->update(['intentos_fallidos' => 0, 'bloqueado_hasta' => null]);
        session(['hijo_id' => $hijo->id_hijo]);

        return redirect()->route('hijo.dashboard');
    }

    public function salir(Request $request)
    {
        $request->session()->forget('hijo_id');
        return redirect()->route('hijo.seleccionar');
    }
}
