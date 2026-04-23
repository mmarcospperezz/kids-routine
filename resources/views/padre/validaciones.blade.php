@extends('layouts.app')

@section('title', 'Validaciones')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Validaciones</h1>
    <p class="text-gray-500 text-sm">Tareas completadas por tus hijos que esperan tu revisión</p>
</div>

@if($instancias->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <span class="text-5xl block mb-4">✅</span>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">¡Todo al día!</h3>
        <p class="text-gray-500">No hay tareas pendientes de validación.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($instancias as $instancia)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                        {{ $instancia->hijo->avatarEmoji() }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $instancia->tarea->titulo }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $instancia->hijo->nombre }} ·
                                    Completada {{ $instancia->fecha_completada?->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1 text-yellow-600 font-bold text-sm flex-shrink-0">
                                <span>🪙</span>
                                <span>+{{ $instancia->tarea->monedas_recompensa }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-4">
                            <form action="{{ route('padre.instancias.validar', $instancia) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                                    ✅ Validar
                                </button>
                            </form>

                            <form action="{{ route('padre.instancias.rechazar', $instancia) }}" method="POST" class="flex gap-2 flex-1">
                                @csrf
                                <input type="text" name="comentario" placeholder="Motivo del rechazo (opcional)" maxlength="500"
                                       class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium px-4 py-2 rounded-xl transition">
                                    ❌ Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
