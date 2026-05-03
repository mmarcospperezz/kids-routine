<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PerfilController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $base64 = $this->resizeToBase64($request->file('avatar')->getRealPath());
        Auth::user()->update(['avatar' => $base64]);

        return back()->with('exito', 'Foto de perfil actualizada.');
    }

    public function eliminarAvatar()
    {
        Auth::user()->update(['avatar' => null]);
        return back()->with('exito', 'Foto de perfil eliminada.');
    }

    public function eliminarCuenta(Request $request)
    {
        $request->validate([
            'confirmacion' => ['required', 'in:eliminar'],
        ], [
            'confirmacion.in' => 'Debes escribir exactamente la palabra "eliminar".',
        ]);

        $user = Auth::user();
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        $user->delete();

        return redirect('/')->with('exito', 'Tu cuenta ha sido eliminada.');
    }

    private function resizeToBase64(string $path): string
    {
        $src = imagecreatefromstring(file_get_contents($path));

        // Corregir rotación EXIF (fotos de móvil)
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($path);
            $orientation = $exif['Orientation'] ?? 1;
            $src = match ($orientation) {
                3 => imagerotate($src, 180, 0),
                6 => imagerotate($src, -90, 0),
                8 => imagerotate($src, 90, 0),
                default => $src,
            };
        }

        $w    = imagesx($src);
        $h    = imagesy($src);
        $size = min($w, $h);
        $x    = intdiv($w - $size, 2);
        $y    = intdiv($h - $size, 2);

        $dst = imagecreatetruecolor(300, 300);
        imagecopyresampled($dst, $src, 0, 0, $x, $y, 300, 300, $size, $size);

        ob_start();
        imagejpeg($dst, null, 85);
        $jpeg = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return 'data:image/jpeg;base64,' . base64_encode($jpeg);
    }
}
