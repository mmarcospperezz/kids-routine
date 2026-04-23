<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialMonedas extends Model
{
    protected $table = 'historial_monedas';
    protected $primaryKey = 'id_historia';
    public $timestamps = false;

    protected $fillable = [
        'id_hijo', 'cantidad', 'saldo_anterior',
        'saldo_posterior', 'motivo', 'id_referencia',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'id_hijo', 'id_hijo');
    }
}
