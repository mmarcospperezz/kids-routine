<?php

namespace App\Support;

class Juegos
{
    const LIST = [
        'sumas' => [
            'nombre'      => 'Operaciones Matemáticas',
            'icono'       => '➕',
            'descripcion' => 'Resuelve sumas y restas para demostrar tus habilidades',
            'dificultad'  => 'Fácil',
            'color_from'  => '#6366f1',
            'color_to'    => '#8b5cf6',
        ],
        'memoria' => [
            'nombre'      => 'Juego de Memoria',
            'icono'       => '🃏',
            'descripcion' => 'Encuentra todas las parejas de cartas ocultas',
            'dificultad'  => 'Medio',
            'color_from'  => '#a855f7',
            'color_to'    => '#ec4899',
        ],
        'ahorcado' => [
            'nombre'      => 'Adivina la Palabra',
            'icono'       => '📝',
            'descripcion' => 'Adivina la palabra secreta antes de quedarte sin intentos',
            'dificultad'  => 'Medio',
            'color_from'  => '#ec4899',
            'color_to'    => '#f97316',
        ],
        'ordenar' => [
            'nombre'      => 'Ordena los Números',
            'icono'       => '🔢',
            'descripcion' => 'Toca los números del 1 al 10 en el orden correcto',
            'dificultad'  => 'Fácil',
            'color_from'  => '#f59e0b',
            'color_to'    => '#ef4444',
        ],
        'quiz' => [
            'nombre'      => 'Quiz de Conocimiento',
            'icono'       => '🧠',
            'descripcion' => 'Responde preguntas de animales, geografía y ciencia',
            'dificultad'  => 'Difícil',
            'color_from'  => '#10b981',
            'color_to'    => '#3b82f6',
        ],
    ];
}
