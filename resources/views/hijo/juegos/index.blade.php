@extends('layouts.hijo')

@section('title', 'Juegos')

@section('content')
<div class="mb-5">
    <h2 class="text-2xl font-extrabold text-white drop-shadow">🎮 Mis Juegos</h2>
    <p class="text-white/70 text-sm mt-0.5">Juega y gana monedas extra · máx. 3 partidas por juego al día</p>
</div>

<div class="space-y-3">
    @php $juegosList = \App\Support\Juegos::LIST; @endphp

    @foreach($juegosList as $slug => $juego)
        @php
            $cfg        = $configs[$slug] ?? null;
            $activo     = $cfg ? $cfg->activo : true;
            $monedas    = $cfg ? $cfg->monedas_por_partida : 5;
            $jugadas    = $partidasHoy[$slug] ?? 0;
            $restantes  = max(0, 3 - $jugadas);
            $disponible = $activo && $restantes > 0;
        @endphp

        <div class="bg-white/95 rounded-2xl shadow-lg border border-white/60 overflow-hidden
                    {{ $disponible ? 'card-hover cursor-pointer' : 'opacity-60' }}">
            <div class="flex items-center gap-4 p-4">
                <!-- Icono -->
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-md flex-shrink-0"
                     style="background: linear-gradient(135deg, {{ $juego['color_from'] }}, {{ $juego['color_to'] }});">
                    {{ $juego['icono'] }}
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-extrabold text-gray-800">{{ $juego['nombre'] }}</p>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold
                            {{ $juego['dificultad'] === 'Fácil' ? 'bg-green-100 text-green-700' : ($juego['dificultad'] === 'Medio' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                            {{ $juego['dificultad'] }}
                        </span>
                    </div>
                    <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">{{ $juego['descripcion'] }}</p>

                    <div class="flex items-center gap-3 mt-2">
                        <!-- Monedas -->
                        <div class="flex items-center gap-1 bg-amber-50 border border-amber-100 rounded-lg px-2 py-1">
                            <span class="text-sm">🪙</span>
                            <span class="text-xs font-extrabold text-amber-700">+{{ $monedas }}</span>
                        </div>
                        <!-- Partidas restantes -->
                        <div class="flex items-center gap-1">
                            @for($i = 0; $i < 3; $i++)
                                <div class="w-2 h-2 rounded-full {{ $i < $restantes ? 'bg-indigo-400' : 'bg-slate-200' }}"></div>
                            @endfor
                            <span class="text-xs text-slate-400 ml-1">{{ $restantes }} restante{{ $restantes !== 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botón jugar -->
                @if($disponible)
                    <a href="{{ route('hijo.juegos.jugar', $slug) }}"
                       class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-2xl text-white text-xl shadow-md transition hover:scale-110 active:scale-95"
                       style="background: linear-gradient(135deg, {{ $juego['color_from'] }}, {{ $juego['color_to'] }});">
                        ▶
                    </a>
                @elseif(!$activo)
                    <span class="flex-shrink-0 text-xs font-bold text-slate-400 bg-slate-100 px-3 py-2 rounded-xl">
                        Inactivo
                    </span>
                @else
                    <div class="flex-shrink-0 text-center">
                        <span class="text-2xl block">😴</span>
                        <span class="text-[10px] text-slate-400 font-bold">Mañana</span>
                    </div>
                @endif
            </div>

            <!-- Barra de progreso de jugadas -->
            @if($jugadas > 0)
                <div class="px-4 pb-3">
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all"
                             style="width: {{ ($jugadas / 3) * 100 }}%; background: linear-gradient(90deg, {{ $juego['color_from'] }}, {{ $juego['color_to'] }});">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
