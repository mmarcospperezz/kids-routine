<?php

namespace App\Http\Controllers\Hijo;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class PerfilController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpg,jpeg,png,gif,webp,heic,heif|max:10240',
        ]);

        $hijo = Hijo::findOrFail(session('hijo_id'));
        $hijo->update(['avatar' => $this->toBase64($request->file('avatar'))]);

        return back()->with('exito', '¡Foto actualizada!');
    }

    public function eliminarAvatar()
    {
        Hijo::findOrFail(session('hijo_id'))->update(['avatar' => null]);
        return back()->with('exito', 'Foto eliminada.');
    }

    private function toBase64($file): string
    {
        $img = Image::read($file)->cover(300, 300);
        $encoded = $img->toWebp(80);
        return 'data:image/webp;base64,' . base64_encode($encoded);
    }
}
