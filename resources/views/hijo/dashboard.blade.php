@extends('layouts.hijo')

@section('title', 'Mis tareas')

@section('content')

{{-- Progreso del día --}}
@php
    $pct = $tareasTotal > 0 ? round(($tareasCompletadas / $tareasTotal) * 100) : 0;
    $allDone = $tareasTotal > 0 && $tareasCompletadas === $tareasTotal;
@endphp

<div class="card-fade bg-white/20 backdrop-blur-sm rounded-3xl p-5 mb-5 border border-white/25 shadow-lg">
    <div class="flex items-center justify-between mb-3">
        <div>
            <p class="text-white font-extrabold text-base">⚡ Progreso de hoy</p>
            <p class="text-white/70 text-sm">{{ $tareasCompletadas }} de {{ $tareasTotal }} completadas</p>
        </div>
        <div class="text-3xl font-extrabold text-white">{{ $pct }}%</div>
    </div>

    @if($tareasTotal > 0)
        <div class="w-full bg-white/20 rounded-full h-4 overflow-hidden">
            <div class="progress-bar h-4 rounded-full shadow-inner"
                 style="--progress: {{ $pct }}%; background: linear-gradient(90deg, #fbbf24, #f59e0b);">
            </div>
        </div>

        @if($allDone)
            <div class="mt-3 text-center">
                <p class="text-yellow-300 font-extrabold text-lg animate-bounce">
                    🎉 ¡Eres increíble! ¡Lo has hecho todo! 🎉
                </p>
            </div>
        @endif
    @else
        <p class="text-white/60 text-sm text-center py-2">No tienes tareas para hoy</p>
    @endif
</div>

{{-- Tareas de hoy --}}
<div class="mb-5">
    <h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">📋 Mis tareas de hoy</h2>

    @if($instanciasHoy->isEmpty())
        <div class="bg-white/20 backdrop-blur-sm rounded-3xl p-10 text-center border border-white/25">
            <span class="text-6xl block mb-3">🌟</span>
            <p class="text-white font-bold text-lg">¡Sin tareas por hoy!</p>
            <p class="text-white/70 text-sm mt-1">Descansa y disfruta 🎈</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($instanciasHoy as $instancia)
                @php
                    $estado = $instancia->estado;
                    $icons = ['PENDIENTE'=>'📌','COMPLETADA'=>'⏳','VALIDADA'=>'✅','RECHAZADA'=>'❌','CADUCADA'=>'💤'];
                    $colors = [
                        'PENDIENTE'  => 'bg-amber-50  border-amber-200',
                        'COMPLETADA' => 'bg-blue-50   border-blue-200',
                        'VALIDADA'   => 'bg-green-50  border-green-200',
                        'RECHAZADA'  => 'bg-red-50    border-red-200',
                        'CADUCADA'   => 'bg-gray-50   border-gray-200',
                    ];
                @endphp
                <div class="task-card bg-white rounded-2xl p-4 border {{ $colors[$estado] ?? 'border-gray-200' }} shadow-sm">
                    <div class="flex items-center gap-3">
                        <!-- Icono de estado -->
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0
                             {{ $estado === 'VALIDADA' ? 'bg-green-100' : ($estado === 'RECHAZADA' ? 'bg-red-100' : ($estado === 'COMPLETADA' ? 'bg-blue-100' : 'bg-amber-100')) }}">
                            {{ $icons[$estado] ?? '📌' }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 text-sm leading-tight">{{ $instancia->tarea->titulo }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-semibold text-yellow-600 flex items-center gap-0.5">
                                    🪙 {{ $instancia->tarea->monedas_recompensa }}
                                </span>
                                @if($instancia->comentario_padre && $estado === 'RECHAZADA')
                                    <span class="text-xs text-red-500">· {{ $instancia->comentario_padre }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Acción / Badge -->
                        @if($estado === 'PENDIENTE')
                            <form action="{{ route('hijo.tareas.completar', $instancia) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="btn-complete bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-md">
                                    ¡Listo! ✓
                                </button>
                            </form>
                        @elseif($estado === 'COMPLETADA')
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full whitespace-nowrap">
                                ⏳ Esperando...
                            </span>
                        @elseif($estado === 'VALIDADA')
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                ✅ ¡Ganado!
                            </span>
                        @elseif($estado === 'RECHAZADA')
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full">
                                ❌ Rechazada
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
    <div class="mb-5">
        <h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">🎀 Mis canjes</h2>
        <div class="space-y-2">
            @foreach($canjesPendientes as $canje)
                <div class="task-card bg-white rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-pink-100 flex items-center justify-center text-2xl flex-shrink-0">🎁</div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 text-sm">{{ $canje->recompensa->nombre }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">🪙 {{ $canje->monedas_gastadas }} monedas</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $canje->estadoColor() }}">
                        {{ $canje->estado === 'PENDIENTE' ? '⏳ Esperando' : '✅ ¡Aprobado!' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Botón a la tienda --}}
<a href="{{ route('hijo.recompensas') }}"
   class="block text-center font-extrabold text-yellow-900 py-4 rounded-2xl shadow-xl transition mt-2 border-2 border-yellow-300"
   style="background: linear-gradient(135deg, #fbbf24, #f59e0b); font-size: 16px;">
    🛍️ Ir a la tienda de recompensas →
</a>

@endsection
