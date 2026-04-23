<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HijoController extends Controller
{
    public function index()
    {
        $hijos = Auth::user()->hijos()->where('activo', true)->get();
        return view('padre.hijos.index', compact('hijos'));
    }

    public function create()
    {
        return view('padre.hijos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:100',
            'edad'         => 'required|integer|min:1|max:18',
            'pin'          => 'required|digits:4',
            'monedas_tope' => 'nullable|integer|min:0',
            'avatar'       => 'nullable|image|max:2048',
        ]);

        $avatarNombre = null;
        if ($request->hasFile('avatar')) {
            $avatarNombre = $request->file('avatar')->store('avatars', 'public');
            $avatarNombre = basename($avatarNombre);
        }

        Auth::user()->hijos()->create([
            'nombre'       => $data['nombre'],
            'edad'         => (int) $data['edad'],
            'pin_hash'     => Hash::make($data['pin']),
            'monedas_tope' => $data['monedas_tope'] ?? null,
            'monedas'      => 0,
            'avatar'       => $avatarNombre,
        ]);

        return redirect()->route('padre.hijos.index')
            ->with('exito', "¡{$data['nombre']} ha sido añadido correctamente!");
    }

    public function edit(Hijo $hijo)
    {
        $this->autorizarHijo($hijo);
        return view('padre.hijos.edit', compact('hijo'));
    }

    public function update(Request $request, Hijo $hijo)
    {
        $this->autorizarHijo($hijo);

        $data = $request->validate([
            'nombre'       => 'required|string|max:100',
            'edad'         => 'required|integer|min:1|max:18',
            'pin'          => 'nullable|digits:4',
            'monedas_tope' => 'nullable|integer|min:0',
            'monedas'      => 'required|integer|min:0',
            'avatar'       => 'nullable|image|max:2048',
        ]);

        $update = [
            'nombre'       => $data['nombre'],
            'edad'         => (int) $data['edad'],
            'monedas_tope' => $data['monedas_tope'],
            'monedas'      => (int) $data['monedas'],
        ];

        if ($request->hasFile('avatar')) {
            if ($hijo->avatar) {
                Storage::disk('public')->delete('avatars/' . $hijo->avatar);
            }
            $update['avatar'] = basename($request->file('avatar')->store('avatars', 'public'));
        }

        if (!empty($data['pin'])) {
            $update['pin_hash'] = Hash::make($data['pin']);
            $update['intentos_fallidos'] = 0;
            $update['bloqueado_hasta'] = null;
        }

        $hijo->update($update);

        return redirect()->route('padre.hijos.index')
            ->with('exito', 'Perfil actualizado correctamente.');
    }

    public function destroy(Hijo $hijo)
    {
        $this->autorizarHijo($hijo);
        $hijo->update(['activo' => false]);
        return redirect()->route('padre.hijos.index')
            ->with('exito', 'Perfil eliminado.');
    }

    private function autorizarHijo(Hijo $hijo): void
    {
        if ($hijo->id_padre !== Auth::id()) {
            abort(403);
        }
    }
}
