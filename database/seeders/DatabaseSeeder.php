<?php

namespace Database\Seeders;

use App\Models\Hijo;
use App\Models\Recompensa;
use App\Models\Tarea;
use App\Models\TareaInstancia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Padre demo
        $padre = User::create([
            'nombre' => 'Padre Demo',
            'email' => 'demo@kidsroutine.com',
            'password_hash' => Hash::make('demo123'),
            'rol' => 'PADRE',
        ]);

        // Hija: Sofía
        $sofia = Hijo::create([
            'nombre' => 'Sofía',
            'edad' => 8,
            'pin_hash' => Hash::make('1234'),
            'monedas' => 30,
            'id_padre' => $padre->id_usuario,
        ]);

        // Hijo: Pablo
        $pablo = Hijo::create([
            'nombre' => 'Pablo',
            'edad' => 6,
            'pin_hash' => Hash::make('5678'),
            'monedas' => 15,
            'id_padre' => $padre->id_usuario,
        ]);

        // Tareas para Sofía
        $tareasSofia = [
            ['titulo' => 'Hacer la cama', 'monedas_recompensa' => 5, 'tipo' => 'RECURRENTE', 'recurrencia' => 'DIARIA'],
            ['titulo' => 'Estudiar 30 minutos', 'monedas_recompensa' => 10, 'tipo' => 'RECURRENTE', 'recurrencia' => 'DIARIA'],
            ['titulo' => 'Ordenar la habitación', 'monedas_recompensa' => 15, 'tipo' => 'RECURRENTE', 'recurrencia' => 'SEMANAL'],
        ];

        foreach ($tareasSofia as $t) {
            Tarea::create(array_merge($t, ['id_hijo' => $sofia->id_hijo]));
        }

        // Tareas para Pablo
        $tareasPablo = [
            ['titulo' => 'Lavarse los dientes', 'monedas_recompensa' => 5, 'tipo' => 'RECURRENTE', 'recurrencia' => 'DIARIA'],
            ['titulo' => 'Recoger los juguetes', 'monedas_recompensa' => 8, 'tipo' => 'RECURRENTE', 'recurrencia' => 'DIARIA'],
        ];

        foreach ($tareasPablo as $t) {
            Tarea::create(array_merge($t, ['id_hijo' => $pablo->id_hijo]));
        }

        // Recompensas compartidas
        $recompensas = [
            ['nombre' => '1 hora de tablet', 'monedas_necesarias' => 20, 'descripcion' => 'Una hora extra de tablet o videojuegos'],
            ['nombre' => 'Elegir la peli del viernes', 'monedas_necesarias' => 15, 'descripcion' => 'Tú eliges la película del viernes por la noche'],
            ['nombre' => 'Salida al parque', 'monedas_necesarias' => 25, 'descripcion' => 'Una tarde en el parque o en tu lugar favorito'],
            ['nombre' => 'Chuche especial', 'monedas_necesarias' => 10, 'descripcion' => 'Una golosina de tu elección'],
        ];

        foreach ($recompensas as $r) {
            Recompensa::create(array_merge($r, ['id_padre' => $padre->id_usuario]));
        }

        // Generar instancias de hoy para tareas diarias
        foreach ([$sofia, $pablo] as $hijo) {
            $tareas = Tarea::where('id_hijo', $hijo->id_hijo)
                ->where('recurrencia', 'DIARIA')
                ->get();
            foreach ($tareas as $tarea) {
                TareaInstancia::create([
                    'id_tarea' => $tarea->id_tarea,
                    'id_hijo' => $hijo->id_hijo,
                    'fecha_programada' => today(),
                    'estado' => 'PENDIENTE',
                ]);
            }
        }
    }
}
