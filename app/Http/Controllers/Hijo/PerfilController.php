<?php

namespace App\Http\Controllers\Hijo;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpg,jpeg,png,gif,webp,heic,heif|max:10240',
        ]);

        $hijo = Hijo::findOrFail(session('hijo_id'));

        if ($hijo->avatar) {
            Storage::disk('public')->delete('avatars/' . $hijo->avatar);
        }

        $nombre = basename($request->file('avatar')->store('avatars', 'public'));
        $hijo->update(['avatar' => $nombre]);

        return back()->with('exito', '¡Foto actualizada!');
    }

    public function eliminarAvatar()
    {
        $hijo = Hijo::findOrFail(session('hijo_id'));

        if ($hijo->avatar) {
            Storage::disk('public')->delete('avatars/' . $hijo->avatar);
            $hijo->update(['avatar' => null]);
        }

        return back()->with('exito', 'Foto eliminada.');
    }
}
