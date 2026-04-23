@extends('layouts.app')

@section('title', 'Tareas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tareas</h1>
        <p class="text-gray-500 text-sm">Gestiona las tareas de tus hijos</p>
    </div>
    <a href="{{ route('padre.tareas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
        + Nueva tarea
    </a>
</div>

@if($hijos->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <span class="text-5xl block mb-4">👦</span>
        <p class="text-gray-500 mb-4">Primero debes añadir un hijo para poder crear tareas.</p>
        <a href="{{ route('padre.hijos.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-indigo-700 transition inline-block">
            Añadir hijo
        </a>
    </div>
@else
    @forelse($hijos as $hijo)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
            <div class="flex items-center gap-3 p-5 border-b border-gray-50">
                <span class="text-2xl">{{ $hijo->avatarEmoji() }}</span>
                <div>
                    <h2 class="font-semibold text-gray-800">{{ $hijo->nombre }}</h2>
                    <p class="text-xs text-gray-500">{{ $hijo->tareas->count() }} tareas activas</p>
                </div>
                <a href="{{ route('padre.tareas.create') }}?hijo={{ $hijo->id_hijo }}" class="ml-auto text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    + Añadir tarea
                </a>
            </div>

            @if($hijo->tareas->isEmpty())
                <div class="p-6 text-center text-gray-400 text-sm">
                    Sin tareas activas —
                    <a href="{{ route('padre.tareas.create') }}" class="text-indigo-600 hover:underline">crear una</a>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($hijo->tareas as $tarea)
                        <div class="flex items-center gap-4 px-5 py-4">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $tarea->titulo }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $tarea->tipo === 'RECURRENTE' ? $tarea->descripcionRecurrencia() : 'Puntual' }}
                                    @if($tarea->fecha_fin) · hasta {{ $tarea->fecha_fin->format('d/m/Y') }} @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1 text-yellow-600 font-semibold text-sm">
                                <span>🪙</span>
                                <span>{{ $tarea->monedas_recompensa }}</span>
                            </div>
                            <form action="{{ route('padre.tareas.destroy', $tarea) }}" method="POST"
                                  onsubmit="return confirm('¿Archivar la tarea «{{ $tarea->titulo }}»?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 text-sm transition px-2" title="Archivar">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-400">
            No hay hijos activos.
        </div>
    @endforelse
@endif
@endsection
