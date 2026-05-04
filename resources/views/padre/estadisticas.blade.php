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

{{-- Historial de monedas ganadas --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <h2 class="font-extrabold text-gray-800 mb-4">💰 Historial de monedas (últimas 8 semanas)</h2>
    @if($historialMonedas->isEmpty())
        <p class="text-center text-slate-400 text-sm py-6">Sin monedas ganadas en las últimas 8 semanas</p>
    @else
    <div class="space-y-5">
        @foreach($historialMonedas->sortKeysDesc() as $semana => $entries)
        @php
            $year  = (int) substr((string)$semana, 0, 4);
            $nSem  = (int) substr((string)$semana, 4);
            $lunes = \Carbon\Carbon::now()->setISODate($year, $nSem, 1);
        @endphp
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Semana del {{ $lunes->format('d/m') }} al {{ $lunes->copy()->addDays(6)->format('d/m') }}
                </span>
                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                    +{{ $entries->sum('cantidad') }} <x-moneda />
                </span>
            </div>
            <div class="space-y-1.5">
                @foreach($entries as $entry)
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-3 py-2.5">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-base flex-shrink-0
                        {{ $entry->tipo === 'TAREA' ? 'bg-green-100' : 'bg-purple-100' }}">
                        {{ $entry->tipo === 'TAREA' ? '✅' : '🎮' }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-700 truncate">
                            @if($entry->tipo === 'TAREA')
                                {{ $entry->descripcion }}
                            @else
                                {{ ucwords(str_replace('_', ' ', $entry->descripcion)) }}
                            @endif
                        </p>
                        <p class="text-xs text-slate-400">{{ $entry->hijo_nombre }}</p>
                    </div>
                    <span class="text-sm font-bold text-amber-600 flex-shrink-0 flex items-center gap-1">
                        +{{ $entry->cantidad }} <x-moneda />
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @if(!$loop->last)<hr class="border-slate-100 my-1">@endif
        @endforeach
    </div>
    @endif
</div>

{{-- Historial de validaciones --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <h2 class="font-extrabold text-gray-800 mb-4">✅ Historial de validaciones (últimas 8 semanas)</h2>
    @if($historialValidaciones->isEmpty())
        <p class="text-center text-slate-400 text-sm py-6">Sin validaciones en las últimas 8 semanas</p>
    @else
    <div class="space-y-5">
        @foreach($historialValidaciones->sortKeysDesc() as $semana => $entries)
        @php
            $year  = (int) substr((string)$semana, 0, 4);
            $nSem  = (int) substr((string)$semana, 4);
            $lunes = \Carbon\Carbon::now()->setISODate($year, $nSem, 1);
        @endphp
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Semana del {{ $lunes->format('d/m') }} al {{ $lunes->copy()->addDays(6)->format('d/m') }}
                </span>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                    {{ $entries->count() }} {{ $entries->count() === 1 ? 'validación' : 'validaciones' }}
                </span>
            </div>
            <div class="space-y-1.5">
                @foreach($entries as $val)
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-3 py-2.5">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 text-base flex-shrink-0">✅</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-700 truncate">{{ $val->titulo }}</p>
                        <p class="text-xs text-slate-400">
                            {{ $val->hijo_nombre }} · {{ \Carbon\Carbon::parse($val->fecha)->format('d/m H:i') }}
                        </p>
                    </div>
                    <span class="text-sm font-bold text-amber-600 flex-shrink-0 flex items-center gap-1">
                        +{{ $val->monedas }} <x-moneda />
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @if(!$loop->last)<hr class="border-slate-100 my-1">@endif
        @endforeach
    </div>
    @endif
</div>

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
