<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpg,jpeg,png,gif,webp,heic,heif|max:10240',
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $nombre = basename($request->file('avatar')->store('avatars', 'public'));
        $user->update(['avatar' => $nombre]);

        return back()->with('exito', 'Foto de perfil actualizada.');
    }

    public function eliminarAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('exito', 'Foto de perfil eliminada.');
    }
}
