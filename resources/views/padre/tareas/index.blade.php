@extends('layouts.app')

@section('title', 'Tareas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900">✅ Tareas</h1>
        <p class="text-slate-500 text-sm mt-0.5">Gestiona las tareas de tus hijos</p>
    </div>
    <a href="{{ route('padre.tareas.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-sm flex items-center gap-1.5">
        <span>+</span> Nueva tarea
    </a>
</div>

@if($hijos->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-14 text-center">
        <span class="text-5xl block mb-4">👦</span>
        <p class="text-slate-500 mb-4 text-sm">Primero debes añadir un hijo para poder crear tareas.</p>
        <a href="{{ route('padre.hijos.create') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-md">
            Añadir hijo →
        </a>
    </div>
@else
    <div class="space-y-4">
        @forelse($hijos as $hijo)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <!-- Header del hijo -->
                <div class="flex items-center gap-3 px-5 py-4 bg-slate-50 border-b border-slate-100">
                    @if($hijo->avatarUrl())
                        <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                             class="w-10 h-10 rounded-xl object-cover">
                    @else
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm text-white"
                             style="background: {{ $hijo->avatarColor() }}">
                            {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="font-extrabold text-gray-800">{{ $hijo->nombre }}</h2>
                        <p class="text-xs text-slate-500">{{ $hijo->tareas->count() }} tarea(s) activa(s)</p>
                    </div>
                    <a href="{{ route('padre.tareas.create') }}?hijo={{ $hijo->id_hijo }}"
                       class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold px-3 py-1.5 rounded-xl transition">
                        + Añadir tarea
                    </a>
                </div>

                @if($hijo->tareas->isEmpty())
                    <div class="p-6 text-center">
                        <p class="text-slate-400 text-sm">
                            Sin tareas activas ·
                            <a href="{{ route('padre.tareas.create') }}?hijo={{ $hijo->id_hijo }}"
                               class="text-indigo-600 font-medium hover:underline">crear una</a>
                        </p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($hijo->tareas as $tarea)
                            <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition group">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-base flex-shrink-0">
                                    {{ $tarea->tipo === 'RECURRENTE' ? '🔄' : '📌' }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-800 text-sm">{{ $tarea->titulo }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $tarea->tipo === 'RECURRENTE' ? $tarea->descripcionRecurrencia() : 'Puntual' }}
                                        @if($tarea->fecha_fin) · hasta {{ $tarea->fecha_fin->format('d/m/Y') }} @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-1 bg-amber-50 text-amber-700 font-extrabold px-2.5 py-1 rounded-full text-xs flex-shrink-0">
                                    <span>🪙</span>
                                    <span>{{ $tarea->monedas_recompensa }}</span>
                                </div>
                                <form action="{{ route('padre.tareas.destroy', $tarea) }}" method="POST"
                                      onsubmit="return confirm('¿Archivar la tarea «{{ $tarea->titulo }}»?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-red-500 text-lg transition px-1">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center text-slate-400">
                No hay hijos activos.
            </div>
        @endforelse
    </div>
@endif
@endsection
