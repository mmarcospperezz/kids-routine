<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use App\Models\HistorialMonedas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonedaController extends Controller
{
    public function ajustar(Request $request, Hijo $hijo)
    {
        if ($hijo->id_padre !== Auth::id()) abort(403);

        $request->validate([
            'cantidad' => 'required|integer|not_in:0|min:-9999|max:9999',
            'motivo'   => 'required|string|max:200',
        ]);

        $cantidad = (int) $request->cantidad;

        $anterior  = $hijo->monedas;
        $posterior = max(0, $anterior + $cantidad);
        $real      = $posterior - $anterior;

        DB::transaction(function () use ($hijo, $real, $anterior, $posterior) {
            $hijo->update(['monedas' => $posterior]);

            HistorialMonedas::create([
                'id_hijo'         => $hijo->id_hijo,
                'cantidad'        => $real,
                'saldo_anterior'  => $anterior,
                'saldo_posterior' => $posterior,
                'motivo'          => 'AJUSTE_PADRE',
                'id_referencia'   => null,
            ]);
        });

        // monedas_historicas solo si fue un ingreso (puede no existir en BD antigua)
        try {
            if ($real > 0) $hijo->increment('monedas_historicas', $real);
        } catch (\Throwable) {}

        $signo = $cantidad > 0 ? '+' : '';
        return back()->with('exito', "Ajuste de monedas aplicado ({$signo}{$cantidad}): {$request->motivo}");
    }
}
