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

    public function avatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : null;
    }

    public function avatarColor(): string
    {
        $gradients = [
            'linear-gradient(135deg,#a855f7,#ec4899)',
            'linear-gradient(135deg,#3b82f6,#06b6d4)',
            'linear-gradient(135deg,#10b981,#34d399)',
            'linear-gradient(135deg,#f59e0b,#f97316)',
            'linear-gradient(135deg,#ef4444,#f43f5e)',
            'linear-gradient(135deg,#8b5cf6,#6366f1)',
        ];
        return $gradients[$this->id_hijo % count($gradients)];
    }
}
