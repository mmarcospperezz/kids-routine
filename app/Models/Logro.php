<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    protected $table = 'logros';
    protected $primaryKey = 'id_logro';
    public $timestamps = false;

    protected $fillable = ['clave', 'titulo', 'descripcion', 'icono', 'bonus_monedas'];

    public function hijos()
    {
        return $this->belongsToMany(Hijo::class, 'hijo_logros', 'id_logro', 'id_hijo')
                    ->withPivot('fecha_obtenido');
    }
}
