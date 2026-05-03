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
        'racha_actual', 'racha_max', 'racha_ultimo_dia', 'monedas_historicas',
    ];

    protected $hidden = ['pin_hash'];

    protected $casts = [
        'bloqueado_hasta' => 'datetime',
        'activo' => 'boolean',
        'racha_ultimo_dia' => 'date',
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

    public function logros()
    {
        return $this->belongsToMany(Logro::class, 'hijo_logros', 'id_hijo', 'id_logro')
                    ->withPivot('fecha_obtenido');
    }

    public function solicitudesPin()
    {
        return $this->hasMany(SolicitudPin::class, 'id_hijo', 'id_hijo');
    }

    public function partidas()
    {
        return $this->hasMany(\App\Models\Partida::class, 'id_hijo', 'id_hijo');
    }

    public function nivel(): int
    {
        $historicas = $this->monedas_historicas ?? 0;
        if ($historicas < 50)  return 1;
        if ($historicas < 150) return 2;
        if ($historicas < 350) return 3;
        if ($historicas < 700) return 4;
        if ($historicas < 1200) return 5;
        if ($historicas < 2000) return 6;
        if ($historicas < 3000) return 7;
        if ($historicas < 4500) return 8;
        if ($historicas < 6500) return 9;
        return 10;
    }

    public function monedas_para_siguiente_nivel(): int
    {
        $umbrales = [50, 150, 350, 700, 1200, 2000, 3000, 4500, 6500, PHP_INT_MAX];
        $historicas = $this->monedas_historicas ?? 0;
        foreach ($umbrales as $umbral) {
            if ($historicas < $umbral) return $umbral - $historicas;
        }
        return 0;
    }

    public function estaBloqueado(): bool
    {
        return $this->bloqueado_hasta !== null && now()->lt($this->bloqueado_hasta);
    }

    public function avatarUrl(): ?string
    {
        if (!$this->avatar) return null;
        if (str_starts_with($this->avatar, 'data:')) return $this->avatar;
        return asset('storage/avatars/' . $this->avatar);
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
