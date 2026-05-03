@extends('layouts.app')

@section('title', 'Estadísticas')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-gray-900">📊 Estadísticas</h1>
    <p class="text-slate-500 text-sm mt-1">Resumen de actividad de tus hijos</p>
</div>

{{-- Resumen por hijo --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    @foreach($tasaCompletado as $dato)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-4">
            @php $h = $hijos->firstWhere('nombre', $dato['nombre']); @endphp
            @if($h?->avatarUrl())
                <img src="{{ $h->avatarUrl() }}" class="w-10 h-10 rounded-xl object-cover">
            @elseif($h)
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold"
                     style="background: {{ $h->avatarColor() }}">
                    {{ mb_strtoupper(mb_substr($h->nombre, 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="font-extrabold text-gray-800">{{ $dato['nombre'] }}</p>
                <p class="text-xs text-slate-400">Nivel {{ $dato['nivel'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="bg-slate-50 rounded-xl p-2">
                <div class="text-lg font-extrabold text-indigo-600">{{ $dato['tasa'] }}%</div>
                <div class="text-slate-500">Completado</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-2">
                <div class="text-lg font-extrabold text-amber-600">{{ $dato['validadas'] }}</div>
                <div class="text-slate-500">Validadas</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-2">
                <div class="text-lg font-extrabold text-orange-500">🔥{{ $dato['racha'] }}</div>
                <div class="text-slate-500">Racha</div>
            </div>
        </div>
        {{-- Barra de completado --}}
        <div class="mt-3 bg-slate-100 rounded-full h-2 overflow-hidden">
            <div class="h-2 rounded-full bg-indigo-500" style="width: {{ $dato['tasa'] }}%"></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Monedas ganadas por semana --}}
@if($monedasSemana->isNotEmpty())
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <h2 class="font-extrabold text-gray-800 mb-4">💰 Monedas ganadas (últimas 8 semanas)</h2>
    @php $maxVal = $monedasSemana->max() ?: 1; @endphp
    <div class="flex items-end gap-2 h-32">
        @foreach($monedasSemana as $semana => $total)
        <div class="flex-1 flex flex-col items-center gap-1">
            <div class="text-xs text-slate-500 font-bold">{{ $total }}</div>
            <div class="w-full rounded-t-lg bg-indigo-400 transition-all"
                 style="height: {{ round(($total / $maxVal) * 100) }}px; min-height: 4px;"></div>
            <div class="text-xs text-slate-400">S{{ $loop->iteration }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid md:grid-cols-2 gap-6 mb-6">
    {{-- Juegos más jugados --}}
    @if($juegosMasJugados->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="font-extrabold text-gray-800 mb-4">🎮 Juegos más jugados</h2>
        <div class="space-y-3">
            @foreach($juegosMasJugados as $j)
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-bold text-gray-700 capitalize">{{ str_replace('_', ' ', $j->juego) }}</span>
                        <span class="text-xs text-slate-400">{{ $j->partidas }} partidas</span>
                    </div>
                    <div class="bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full bg-purple-400"
                             style="width: {{ round(($j->partidas / $juegosMasJugados->first()->partidas) * 100) }}%"></div>
                    </div>
                </div>
                <div class="text-xs font-bold text-amber-600 flex items-center gap-0.5">
                    <x-moneda /> {{ $j->monedas }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Historial de partidas recientes --}}
    @if($partidasRecientes->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="font-extrabold text-gray-800 mb-4">🕐 Partidas recientes</h2>
        <div class="space-y-2 max-h-64 overflow-y-auto">
            @foreach($partidasRecientes as $p)
            <div class="flex items-center gap-3 text-sm">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-base flex-shrink-0">🎮</div>
                <div class="flex-1 min-w-0">
                    <span class="font-medium text-gray-800">{{ $p->hijo->nombre }}</span>
                    <span class="text-slate-400"> · {{ str_replace('_', ' ', $p->juego) }}</span>
                </div>
                <div class="text-xs text-amber-600 font-bold flex items-center gap-0.5">
                    <x-moneda /> {{ $p->monedas_ganadas }}
                </div>
                <div class="text-xs text-slate-400 whitespace-nowrap">
                    {{ $p->created_at->format('d/m') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Calendario del mes --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <h2 class="font-extrabold text-gray-800 mb-4">📅 Calendario — {{ $mesActual->format('F Y') }}</h2>
    @php
        $diasMes = $mesActual->daysInMonth;
        $primerDia = (int) $mesActual->copy()->startOfMonth()->format('N'); // 1=lun, 7=dom
    @endphp
    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px"
         class="text-center text-xs font-bold text-slate-400 mb-2">
        @foreach(['L','M','X','J','V','S','D'] as $d) <div>{{ $d }}</div> @endforeach
    </div>
    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px">
        @for($i = 1; $i < $primerDia; $i++)<div></div>@endfor
        @for($dia = 1; $dia <= $diasMes; $dia++)
            @php
                $fecha = $mesActual->copy()->setDay($dia)->format('Y-m-d');
                $instDia = $instanciasMes[$fecha] ?? collect();
                $tieneValidada = $instDia->contains('estado', 'VALIDADA');
                $tieneRechazada = $instDia->contains('estado', 'RECHAZADA');
                $tienePendiente = $instDia->contains('estado', 'PENDIENTE') || $instDia->contains('estado', 'COMPLETADA');
                $esHoy = $fecha === today()->format('Y-m-d');
                $color = $tieneValidada ? 'bg-green-500 text-white' : ($tieneRechazada ? 'bg-red-500 text-white' : ($tienePendiente ? 'bg-amber-400 text-white' : 'text-slate-300'));
            @endphp
            <div class="aspect-square flex items-center justify-center rounded-xl text-xs font-bold
                        {{ $color }} {{ $esHoy ? 'ring-2 ring-indigo-400' : '' }}">
                {{ $dia }}
            </div>
        @endfor
    </div>
    <div class="flex gap-4 mt-3 text-xs text-slate-500">
        <span class="flex items-center gap-1"><span style="width:12px;height:12px;background:#22c55e;border-radius:3px;display:inline-block"></span> Validado</span>
        <span class="flex items-center gap-1"><span style="width:12px;height:12px;background:#fbbf24;border-radius:3px;display:inline-block"></span> Pendiente</span>
        <span class="flex items-center gap-1"><span style="width:12px;height:12px;background:#ef4444;border-radius:3px;display:inline-block"></span> Rechazado</span>
    </div>
</div>

@endsection
