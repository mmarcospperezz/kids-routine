<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'id_tarea';
    public $timestamps = false;

    protected $fillable = [
        'titulo', 'descripcion', 'monedas_recompensa',
        'tipo', 'recurrencia', 'dias_semana',
        'estado', 'fecha_fin', 'id_hijo',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_fin' => 'date',
    ];

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'id_hijo', 'id_hijo');
    }

    public function instancias()
    {
        return $this->hasMany(TareaInstancia::class, 'id_tarea', 'id_tarea');
    }

    public function diasSemanaArray(): array
    {
        return $this->dias_semana ? explode(',', $this->dias_semana) : [];
    }

    public function descripcionRecurrencia(): string
    {
        return match ($this->recurrencia) {
            'DIARIA' => 'Todos los días',
            'SEMANAL' => 'Una vez a la semana',
            'PERSONALIZADA' => 'Días personalizados',
            default => 'Puntual',
        };
    }
}
