<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'monedas_tope' => 'nullable|integer|min:1',
            'avatar'       => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $avatar = null;
        if ($request->hasFile('avatar')) {
            $avatar = $this->resizeToBase64($request->file('avatar')->getRealPath());
        }

        Auth::user()->hijos()->create([
            'nombre'       => $data['nombre'],
            'edad'         => (int) $data['edad'],
            'pin_hash'     => Hash::make($data['pin']),
            'monedas_tope' => $data['monedas_tope'] ?? null,
            'monedas'      => 0,
            'avatar'       => $avatar,
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
            'monedas_tope' => 'nullable|integer|min:1',
            'monedas'      => 'required|integer|min:0',
            'avatar'       => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $update = [
            'nombre'       => $data['nombre'],
            'edad'         => (int) $data['edad'],
            'monedas_tope' => $data['monedas_tope'],
            'monedas'      => (int) $data['monedas'],
        ];

        if ($request->hasFile('avatar')) {
            $update['avatar'] = $this->resizeToBase64($request->file('avatar')->getRealPath());
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

    private function resizeToBase64(string $path): string
    {
        $original = imagecreatefromstring(file_get_contents($path));
        $ow = imagesx($original);
        $oh = imagesy($original);
        $size = 300;
        $ratio = max($size / $ow, $size / $oh);
        $nw = (int) round($ow * $ratio);
        $nh = (int) round($oh * $ratio);
        $tmp = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($tmp, $original, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
        $canvas = imagecreatetruecolor($size, $size);
        imagecopy($canvas, $tmp, 0, 0, (int)(($nw - $size) / 2), (int)(($nh - $size) / 2), $size, $size);
        imagedestroy($original);
        imagedestroy($tmp);
        ob_start();
        imagejpeg($canvas, null, 85);
        $jpeg = ob_get_clean();
        imagedestroy($canvas);
        return 'data:image/jpeg;base64,' . base64_encode($jpeg);
    }
}
