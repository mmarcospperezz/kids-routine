<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionJuego extends Model
{
    protected $table = 'configuracion_juegos';
    protected $fillable = ['id_padre', 'juego', 'monedas_por_partida', 'activo'];
    protected $casts = ['activo' => 'boolean'];
}
