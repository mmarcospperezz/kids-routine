<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas';
    protected $fillable = ['id_hijo', 'juego', 'monedas_ganadas'];

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'id_hijo', 'id_hijo');
    }
}
