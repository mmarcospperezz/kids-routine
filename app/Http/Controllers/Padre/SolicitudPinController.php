<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\SolicitudPin;
use Illuminate\Support\Facades\Auth;

class SolicitudPinController extends Controller
{
    public function index()
    {
        $hijoIds = Auth::user()->hijos()->pluck('id_hijo');

        $solicitudes = SolicitudPin::with('hijo')
            ->whereIn('id_hijo', $hijoIds)
            ->where('estado', 'PENDIENTE')
            ->latest()
            ->get();

        return view('padre.solicitudes_pin', compact('solicitudes'));
    }

    public function aprobar(SolicitudPin $solicitud)
    {
        $this->autorizar($solicitud);

        $solicitud->hijo->update(['pin_hash' => $solicitud->nuevo_pin_hash]);
        $solicitud->update(['estado' => 'APROBADA']);

        return back()->with('exito', "PIN de {$solicitud->hijo->nombre} actualizado.");
    }

    public function rechazar(SolicitudPin $solicitud)
    {
        $this->autorizar($solicitud);
        $solicitud->update(['estado' => 'RECHAZADA']);
        return back()->with('exito', "Solicitud de {$solicitud->hijo->nombre} rechazada.");
    }

    private function autorizar(SolicitudPin $solicitud): void
    {
        if ($solicitud->hijo->id_padre !== Auth::id()) abort(403);
        if ($solicitud->estado !== 'PENDIENTE') abort(400);
    }
}
