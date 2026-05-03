<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;

class PerfilController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpg,jpeg,png,gif,webp,heic,heif|max:10240',
        ]);

        $base64 = $this->toBase64($request->file('avatar'));
        Auth::user()->update(['avatar' => $base64]);

        return back()->with('exito', 'Foto de perfil actualizada.');
    }

    public function eliminarAvatar()
    {
        Auth::user()->update(['avatar' => null]);
        return back()->with('exito', 'Foto de perfil eliminada.');
    }

    private function toBase64($file): string
    {
        $img = Image::read($file)->cover(300, 300);
        $encoded = $img->toWebp(80);
        return 'data:image/webp;base64,' . base64_encode($encoded);
    }
}
