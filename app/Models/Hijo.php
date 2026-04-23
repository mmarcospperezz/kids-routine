<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hijo extends Model
{
    use HasFactory;

    protected $table = 'hijos';
    protected $primaryKey = 'id_hijo';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'edad', 'avatar', 'pin_hash',
        'monedas', 'monedas_tope', 'intentos_fallidos',
        'bloqueado_hasta', 'id_padre', 'activo',
    ];

    protected $hidden = ['pin_hash'];

    protected $casts = [
        'bloqueado_hasta' => 'datetime',
        'activo' => 'boolean',
    ];

    public function padre()
    {
        return $this->belongsTo(User::class, 'id_padre', 'id_usuario');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_hijo', 'id_hijo');
    }

    public function instancias()
    {
        return $this->hasMany(TareaInstancia::class, 'id_hijo', 'id_hijo');
    }

    public function canjes()
    {
        return $this->hasMany(Canje::class, 'id_hijo', 'id_hijo');
    }

    public function historialMonedas()
    {
        return $this->hasMany(HistorialMonedas::class, 'id_hijo', 'id_hijo');
    }

    public function estaBloqueado(): bool
    {
        return $this->bloqueado_hasta !== null && now()->lt($this->bloqueado_hasta);
    }

    public function avatarEmoji(): string
    {
        $emojis = ['🐱', '🐶', '🐸', '🦊', '🐼', '🐨', '🦁', '🐯', '🐮', '🐷'];
        return $emojis[$this->id_hijo % count($emojis)];
    }
}
