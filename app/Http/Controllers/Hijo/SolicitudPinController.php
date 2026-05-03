<?php

namespace App\Http\Controllers\Hijo;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use App\Models\SolicitudPin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SolicitudPinController extends Controller
{
    public function solicitar(Request $request)
    {
        $request->validate([
            'nuevo_pin'         => 'required|digits:4',
            'nuevo_pin_confirm' => 'required|same:nuevo_pin',
        ], [
            'nuevo_pin.digits'         => 'El PIN debe tener exactamente 4 dígitos.',
            'nuevo_pin_confirm.same'   => 'Los PINs no coinciden.',
        ]);

        $hijo = Hijo::findOrFail(session('hijo_id'));

        // Cancelar solicitudes anteriores pendientes
        SolicitudPin::where('id_hijo', $hijo->id_hijo)
            ->where('estado', 'PENDIENTE')
            ->delete();

        SolicitudPin::create([
            'id_hijo'       => $hijo->id_hijo,
            'nuevo_pin_hash'=> Hash::make($request->nuevo_pin),
            'estado'        => 'PENDIENTE',
        ]);

        return back()->with('exito', 'Solicitud enviada. Tu padre tiene que aprobarla.');
    }
}
