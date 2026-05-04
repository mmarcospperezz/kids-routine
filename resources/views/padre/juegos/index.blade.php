@extends('layouts.app')

@section('title', 'Configurar Juegos')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-gray-900">🎮 Juegos Educativos</h1>
    <p class="text-slate-500 text-sm mt-0.5">Configura cuántas monedas gana tu hijo al completar cada juego</p>
</div>

<form action="{{ route('padre.juegos.guardar') }}" method="POST">
    @csrf

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @php
            $juegosList = \App\Support\Juegos::LIST;
        @endphp

        @foreach($juegosList as $slug => $juego)
            @php
                $cfg = $configs[$slug] ?? null;
                $monedas = $cfg ? $cfg->monedas_por_partida : 5;
                $activo  = $cfg ? $cfg->activo : true;
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 card-hover">
                <!-- Header con gradiente -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm flex-shrink-0"
                         style="background: linear-gradient(135deg, {{ $juego['color_from'] }}, {{ $juego['color_to'] }});">
                        {{ $juego['icono'] }}
                    </div>
                    <div>
                        <p class="font-extrabold text-gray-800 text-sm leading-tight">{{ $juego['nombre'] }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $juego['dificultad'] === 'Fácil' ? 'bg-green-100 text-green-700' : ($juego['dificultad'] === 'Medio' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                            {{ $juego['dificultad'] }}
                        </span>
                    </div>
                </div>

                <p class="text-slate-500 text-xs mb-4 leading-relaxed">{{ $juego['descripcion'] }}</p>

                <!-- Toggle activo -->
                <label class="flex items-center gap-2 mb-3 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" name="juegos[{{ $slug }}][activo]" value="1"
                               {{ $activo ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 h-6 bg-slate-200 peer-checked:bg-indigo-500 rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-sm font-medium text-slate-700">Juego activo</span>
                </label>

                <!-- Monedas por partida -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Monedas por partida
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-lg"><x-moneda /></span>
                        <input type="number"
                               name="juegos[{{ $slug }}][monedas_por_partida]"
                               value="{{ $monedas }}"
                               min="1" max="50" required
                               onwheel="this.blur()"
                               class="w-20 border border-slate-300 rounded-xl px-3 py-2 text-sm font-bold text-center bg-slate-50 hover:bg-white">
                        <span class="text-xs text-slate-400">máx. 50</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info límite de partidas -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-5 py-4 mb-6 flex items-start gap-3">
        <span class="text-xl flex-shrink-0">ℹ️</span>
        <div>
            <p class="font-bold text-indigo-800 text-sm">Límite de partidas diarias</p>
            <p class="text-indigo-600 text-xs mt-0.5">Cada hijo puede jugar cada juego un máximo de <strong>3 veces al día</strong> para evitar que acumulen demasiadas monedas.</p>
        </div>
    </div>

    <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-xl transition shadow-sm">
        Guardar configuración
    </button>
</form>
@endsection
