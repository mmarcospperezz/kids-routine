<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareaInstancia extends Model
{
    protected $table = 'tarea_instancias';
    protected $primaryKey = 'id_instancia';
    public $timestamps = false;

    protected $fillable = [
        'id_tarea', 'id_hijo', 'fecha_programada',
        'estado', 'fecha_completada', 'fecha_validada', 'comentario_padre',
        'foto_prueba',
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_completada' => 'datetime',
        'fecha_validada' => 'datetime',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'id_tarea', 'id_tarea');
    }

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'id_hijo', 'id_hijo');
    }

    public function estadoColor(): string
    {
        return match ($this->estado) {
            'PENDIENTE' => 'bg-yellow-100 text-yellow-800',
            'COMPLETADA' => 'bg-blue-100 text-blue-800',
            'VALIDADA' => 'bg-green-100 text-green-800',
            'RECHAZADA' => 'bg-red-100 text-red-800',
            'CADUCADA' => 'bg-gray-100 text-gray-600',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function estadoLabel(): string
    {
        return match ($this->estado) {
            'PENDIENTE' => 'Pendiente',
            'COMPLETADA' => 'Esperando validación',
            'VALIDADA' => 'Validada ✓',
            'RECHAZADA' => 'Rechazada',
            'CADUCADA' => 'Caducada',
            default => $this->estado,
        };
    }
}
