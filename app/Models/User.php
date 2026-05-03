<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'password_hash',
        'rol',
        'avatar',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function avatarUrl(): ?string
    {
        if (!$this->avatar) return null;
        // Base64 guardado en BD (nuevo sistema)
        if (str_starts_with($this->avatar, 'data:')) return $this->avatar;
        // Ruta de archivo (sistema antiguo, compatibilidad)
        return asset('storage/avatars/' . $this->avatar);
    }

    public function hijos()
    {
        return $this->hasMany(Hijo::class, 'id_padre', 'id_usuario');
    }

    public function recompensas()
    {
        return $this->hasMany(Recompensa::class, 'id_padre', 'id_usuario');
    }
}
