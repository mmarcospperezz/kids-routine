<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canje extends Model
{
    protected $table = 'canjes';
    protected $primaryKey = 'id_canje';
    public $timestamps = false;

    protected $fillable = [
        'id_hijo', 'id_recompensa', 'monedas_gastadas',
        'estado', 'fecha_resolucion', 'comentario', 'fecha_caducidad',
    ];

    protected $casts = [
        'fecha_solicitud'  => 'datetime',
        'fecha_resolucion' => 'datetime',
        'fecha_caducidad'  => 'date',
    ];

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'id_hijo', 'id_hijo');
    }

    public function recompensa()
    {
        return $this->belongsTo(Recompensa::class, 'id_recompensa', 'id_recompensa');
    }

    public function estadoColor(): string
    {
        return match ($this->estado) {
            'PENDIENTE' => 'bg-yellow-100 text-yellow-800',
            'APROBADO' => 'bg-blue-100 text-blue-800',
            'ENTREGADO' => 'bg-green-100 text-green-800',
            'RECHAZADO' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
