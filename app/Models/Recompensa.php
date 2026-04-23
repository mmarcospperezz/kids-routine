<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    protected $table = 'recompensas';
    protected $primaryKey = 'id_recompensa';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'monedas_necesarias',
        'imagen_url', 'activa', 'id_padre',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function padre()
    {
        return $this->belongsTo(User::class, 'id_padre', 'id_usuario');
    }

    public function canjes()
    {
        return $this->hasMany(Canje::class, 'id_recompensa', 'id_recompensa');
    }
}
