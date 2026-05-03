<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudPin extends Model
{
    protected $table = 'solicitudes_pin';

    protected $fillable = ['id_hijo', 'nuevo_pin_hash', 'estado'];

    protected $hidden = ['nuevo_pin_hash'];

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'id_hijo', 'id_hijo');
    }
}
