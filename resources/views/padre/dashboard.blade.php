@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

{{-- Saludo --}}
<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-gray-900">¡Hola, {{ $padre->nombre }}! 👋</h1>
    <p class="text-gray-500 text-sm mt-0.5">{{ now()->isoFormat('dddd D [de] MMMM') }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-enter card-hover bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-2">
            <span class="text-2xl">👨‍👧‍👦</span>
            <span class="text-xs text-slate-400 font-medium">hijos</span>
        </div>
        <p class="text-3xl font-extrabold text-indigo-600">{{ $hijos->count() }}</p>
        <p class="text-xs text-slate-500 mt-1">activos</p>
    </div>

    <div class="stat-enter card-hover bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-2">
            <span class="text-2xl">📋</span>
            <span class="text-xs text-slate-400 font-medium">hoy</span>
        </div>
        <p class="text-3xl font-extrabold text-blue-600">{{ $tareasHoy->count() }}</p>
        <p class="text-xs text-slate-500 mt-1">tareas asignadas</p>
    </div>

    <div class="stat-enter card-hover rounded-2xl p-5 shadow-sm border
         {{ $pendientesValidacion > 0 ? 'bg-amber-50 border-amber-200' : 'bg-white border-slate-100' }}">
        <div class="flex items-center justify-between mb-2">
            <span class="text-2xl">🔍</span>
            <span class="text-xs {{ $pendientesValidacion > 0 ? 'text-amber-500' : 'text-slate-400' }} font-medium">validar</span>
        </div>
        <p class="text-3xl font-extrabold {{ $pendientesValidacion > 0 ? 'text-amber-600' : 'text-slate-400' }}">{{ $pendientesValidacion }}</p>
        <p class="text-xs {{ $pendientesValidacion > 0 ? 'text-amber-600' : 'text-slate-500' }} mt-1">por validar</p>
    </div>

    <div class="stat-enter card-hover rounded-2xl p-5 shadow-sm border
         {{ $pendientesCanjes > 0 ? 'bg-pink-50 border-pink-200' : 'bg-white border-slate-100' }}">
        <div class="flex items-center justify-between mb-2">
            <span class="text-2xl">🎁</span>
            <span class="text-xs {{ $pendientesCanjes > 0 ? 'text-pink-400' : 'text-slate-400' }} font-medium">canjes</span>
        </div>
        <p class="text-3xl font-extrabold {{ $pendientesCanjes > 0 ? 'text-pink-600' : 'text-slate-400' }}">{{ $pendientesCanjes }}</p>
        <p class="text-xs {{ $pendientesCanjes > 0 ? 'text-pink-600' : 'text-slate-500' }} mt-1">solicitudes</p>
    </div>
</div>

{{-- Alertas de acción --}}
@if($pendientesValidacion > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 flex items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3 min-w-0">
            <span class="text-2xl flex-shrink-0">⏳</span>
            <div class="min-w-0">
                <p class="font-bold text-amber-800 text-sm">{{ $pendientesValidacion }} tarea(s) esperando tu validación</p>
                <p class="text-amber-600 text-xs mt-0.5 truncate">Tus hijos han completado tareas y esperan tu aprobación</p>
            </div>
        </div>
        <a href="{{ route('padre.validaciones') }}"
           class="flex-shrink-0 bg-amber-400 hover:bg-amber-500 text-amber-900 font-bold px-4 py-2 rounded-xl text-sm transition shadow-sm">
            Validar
        </a>
    </div>
@endif

@if($pendientesCanjes > 0)
    <div class="bg-pink-50 border border-pink-200 rounded-2xl p-4 mb-6 flex items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3 min-w-0">
            <span class="text-2xl flex-shrink-0">🎀</span>
            <div class="min-w-0">
                <p class="font-bold text-pink-800 text-sm">{{ $pendientesCanjes }} solicitud(es) de canje</p>
                <p class="text-pink-600 text-xs mt-0.5">Tus hijos quieren canjear sus monedas</p>
            </div>
        </div>
        <a href="{{ route('padre.canjes.index') }}"
           class="flex-shrink-0 bg-pink-500 hover:bg-pink-600 text-white font-bold px-4 py-2 rounded-xl text-sm transition shadow-sm">
            Revisar
        </a>
    </div>
@endif

<div class="grid lg:grid-cols-2 gap-5">
    {{-- Mis hijos --}}
    <div class="card-hover bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-extrabold text-gray-800">👨‍👧‍👦 Mis hijos</h2>
            <a href="{{ route('padre.hijos.create') }}"
               class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold px-3 py-1.5 rounded-xl transition">
                + Añadir
            </a>
        </div>

        @if($hijos->isEmpty())
            <div class="text-center py-8">
                <span class="text-4xl block mb-2">👦</span>
                <p class="text-sm text-slate-500 mb-3">Aún no tienes hijos registrados</p>
                <a href="{{ route('padre.hijos.create') }}"
                   class="text-indigo-600 text-sm font-bold hover:underline">Añadir el primero →</a>
            </div>
        @else
            <div class="space-y-2.5">
                @foreach($hijos as $hijo)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 transition">
                        @if($hijo->avatarUrl())
                            <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                                 class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm text-white flex-shrink-0"
                                 style="background: {{ $hijo->avatarColor() }}">
                                {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 text-sm">{{ $hijo->nombre }}</p>
                            <p class="text-xs text-slate-500">{{ $hijo->edad }} años</p>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-600 font-extrabold text-sm">
                            <span><x-moneda /></span>
                            <span>{{ $hijo->monedas }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Tareas de hoy --}}
    <div class="card-hover bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-extrabold text-gray-800">📋 Tareas de hoy</h2>
            @if($tareasHoy->isEmpty())
                <a href="{{ route('padre.tareas.create') }}"
                   class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold px-3 py-1.5 rounded-xl transition">
                    + Crear
                </a>
            @endif
        </div>

        @if($tareasHoy->isEmpty())
            <div class="text-center py-8">
                <span class="text-4xl block mb-2">✅</span>
                <p class="text-sm text-slate-500">No hay tareas para hoy</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($tareasHoy as $instancia)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                        @if($instancia->hijo->avatarUrl())
                            <img src="{{ $instancia->hijo->avatarUrl() }}" alt="{{ $instancia->hijo->nombre }}"
                                 class="w-7 h-7 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-xs text-white flex-shrink-0"
                                 style="background: {{ $instancia->hijo->avatarColor() }}">
                                {{ mb_strtoupper(mb_substr($instancia->hijo->nombre, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $instancia->tarea->titulo }}</p>
                            <p class="text-xs text-slate-500">{{ $instancia->hijo->nombre }}</p>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $instancia->estadoColor() }} whitespace-nowrap">
                            {{ $instancia->estadoLabel() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Onboarding --}}
@if($hijos->isEmpty())
    <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 rounded-2xl p-8 text-center shadow-sm">
        <span class="text-5xl block mb-3">🚀</span>
        <h3 class="text-xl font-extrabold text-gray-900 mb-2">¡Empecemos!</h3>
        <p class="text-gray-500 text-sm mb-5">Primero añade a tus hijos, luego crea sus tareas y recompensas.</p>
        <a href="{{ route('padre.hijos.create') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-md">
            Añadir mi primer hijo →
        </a>
    </div>
@endif

@endsection
