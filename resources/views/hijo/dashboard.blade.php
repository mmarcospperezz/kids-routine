@extends('layouts.hijo')

@section('title', 'Mis tareas')

@section('content')

{{-- Progreso del día --}}
<div class="bg-white/20 backdrop-blur-sm rounded-2xl p-5 mb-5 text-white">
    <div class="flex items-center justify-between mb-2">
        <p class="font-semibold">Progreso de hoy</p>
        <p class="text-sm opacity-80">{{ $tareasCompletadas }}/{{ $tareasTotal }} completadas</p>
    </div>
    @if($tareasTotal > 0)
        <div class="w-full bg-white/30 rounded-full h-3">
            <div class="bg-yellow-400 h-3 rounded-full transition-all"
                 style="width: {{ $tareasTotal > 0 ? round(($tareasCompletadas/$tareasTotal)*100) : 0 }}%"></div>
        </div>
        @if($tareasCompletadas === $tareasTotal && $tareasTotal > 0)
            <p class="text-center text-sm mt-2 text-yellow-300 font-semibold">🎉 ¡Has completado todas las tareas de hoy!</p>
        @endif
    @else
        <p class="text-white/70 text-sm">No tienes tareas para hoy.</p>
    @endif
</div>

{{-- Tareas de hoy --}}
<div class="mb-5">
    <h2 class="text-white font-bold text-lg mb-3">📋 Mis tareas de hoy</h2>

    @if($instanciasHoy->isEmpty())
        <div class="bg-white/20 rounded-2xl p-8 text-center text-white/80">
            <span class="text-4xl block mb-2">🌟</span>
            <p>¡No tienes tareas para hoy!</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($instanciasHoy as $instancia)
                <div class="bg-white rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 {{ $instancia->estado === 'COMPLETADA' ? 'bg-blue-100' : ($instancia->estado === 'RECHAZADA' ? 'bg-red-100' : 'bg-yellow-100') }} rounded-xl flex items-center justify-center text-2xl">
                            {{ $instancia->estado === 'COMPLETADA' ? '⏳' : ($instancia->estado === 'RECHAZADA' ? '❌' : '📌') }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $instancia->tarea->titulo }}</p>
                            <p class="text-xs text-gray-500">
                                🪙 {{ $instancia->tarea->monedas_recompensa }} monedas
                            </p>
                            @if($instancia->comentario_padre && $instancia->estado === 'RECHAZADA')
                                <p class="text-xs text-red-500 mt-0.5">Motivo: {{ $instancia->comentario_padre }}</p>
                            @endif
                        </div>

                        @if($instancia->estado === 'PENDIENTE')
                            <form action="{{ route('hijo.tareas.completar', $instancia) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition active:scale-95">
                                    ¡Listo! ✓
                                </button>
                            </form>
                        @elseif($instancia->estado === 'COMPLETADA')
                            <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                Esperando...
                            </span>
                        @elseif($instancia->estado === 'RECHAZADA')
                            <span class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded-full">
                                Rechazada
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Canjes activos --}}
@if($canjesPendientes->isNotEmpty())
    <div>
        <h2 class="text-white font-bold text-lg mb-3">🎀 Mis canjes</h2>
        <div class="space-y-2">
            @foreach($canjesPendientes as $canje)
                <div class="bg-white rounded-2xl p-4 flex items-center gap-3">
                    <span class="text-2xl">🎁</span>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 text-sm">{{ $canje->recompensa->nombre }}</p>
                        <p class="text-xs text-gray-500">🪙 {{ $canje->monedas_gastadas }} monedas</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $canje->estadoColor() }}">
                        {{ $canje->estado === 'PENDIENTE' ? 'Esperando...' : '¡Aprobado!' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Ir a la tienda --}}
<div class="mt-5">
    <a href="{{ route('hijo.recompensas') }}"
       class="block bg-yellow-400 hover:bg-yellow-300 text-yellow-900 font-bold text-center py-4 rounded-2xl transition shadow-lg">
        🛍️ Ir a la tienda de recompensas →
    </a>
</div>

@endsection
