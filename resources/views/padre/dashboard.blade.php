@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">¡Hola, {{ $padre->nombre }}! 👋</h1>
    <p class="text-gray-500">Aquí tienes el resumen de hoy, {{ now()->isoFormat('dddd D [de] MMMM') }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-indigo-600">{{ $hijos->count() }}</p>
        <p class="text-sm text-gray-500 mt-1">Hijos activos</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-blue-600">{{ $tareasHoy->count() }}</p>
        <p class="text-sm text-gray-500 mt-1">Tareas hoy</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 {{ $pendientesValidacion > 0 ? 'border-yellow-300 bg-yellow-50' : '' }}">
        <p class="text-3xl font-bold {{ $pendientesValidacion > 0 ? 'text-yellow-600' : 'text-gray-400' }}">{{ $pendientesValidacion }}</p>
        <p class="text-sm text-gray-500 mt-1">Por validar</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 {{ $pendientesCanjes > 0 ? 'border-pink-300 bg-pink-50' : '' }}">
        <p class="text-3xl font-bold {{ $pendientesCanjes > 0 ? 'text-pink-600' : 'text-gray-400' }}">{{ $pendientesCanjes }}</p>
        <p class="text-sm text-gray-500 mt-1">Canjes pendientes</p>
    </div>
</div>

{{-- Alertas de acción --}}
@if($pendientesValidacion > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⏳</span>
            <div>
                <p class="font-semibold text-yellow-800">{{ $pendientesValidacion }} tarea(s) esperando tu validación</p>
                <p class="text-yellow-600 text-sm">Tus hijos han completado tareas y esperan tu aprobación</p>
            </div>
        </div>
        <a href="{{ route('padre.validaciones') }}" class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-medium px-4 py-2 rounded-xl text-sm transition">
            Validar ahora
        </a>
    </div>
@endif

@if($pendientesCanjes > 0)
    <div class="bg-pink-50 border border-pink-200 rounded-2xl p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🎀</span>
            <div>
                <p class="font-semibold text-pink-800">{{ $pendientesCanjes }} solicitud(es) de canje pendientes</p>
                <p class="text-pink-600 text-sm">Tus hijos quieren canjear sus monedas</p>
            </div>
        </div>
        <a href="{{ route('padre.canjes.index') }}" class="bg-pink-400 hover:bg-pink-500 text-white font-medium px-4 py-2 rounded-xl text-sm transition">
            Revisar canjes
        </a>
    </div>
@endif

<div class="grid md:grid-cols-2 gap-6">
    {{-- Resumen de hijos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-900">Mis hijos</h2>
            <a href="{{ route('padre.hijos.create') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">+ Añadir</a>
        </div>

        @if($hijos->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <span class="text-4xl block mb-2">👦</span>
                <p class="text-sm">Aún no tienes hijos registrados</p>
                <a href="{{ route('padre.hijos.create') }}" class="text-indigo-600 text-sm hover:underline">Añadir el primero</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($hijos as $hijo)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                        <span class="text-2xl">{{ $hijo->avatarEmoji() }}</span>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $hijo->nombre }}</p>
                            <p class="text-xs text-gray-500">{{ $hijo->edad }} años</p>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-600 font-semibold">
                            <span>🪙</span>
                            <span>{{ $hijo->monedas }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Tareas de hoy --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Tareas de hoy</h2>

        @if($tareasHoy->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <span class="text-4xl block mb-2">✅</span>
                <p class="text-sm">No hay tareas generadas para hoy</p>
                <a href="{{ route('padre.tareas.create') }}" class="text-indigo-600 text-sm hover:underline">Crear una tarea</a>
            </div>
        @else
            <div class="space-y-2">
                @foreach($tareasHoy as $instancia)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                        <span class="text-lg">{{ $instancia->hijo->avatarEmoji() }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $instancia->tarea->titulo }}</p>
                            <p class="text-xs text-gray-500">{{ $instancia->hijo->nombre }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $instancia->estadoColor() }}">
                            {{ $instancia->estadoLabel() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Accesos rápidos --}}
@if($hijos->isEmpty())
    <div class="mt-8 bg-indigo-50 border border-indigo-100 rounded-2xl p-8 text-center">
        <span class="text-5xl block mb-4">🚀</span>
        <h3 class="text-xl font-bold text-gray-900 mb-2">¡Empecemos!</h3>
        <p class="text-gray-500 mb-6">Primero añade a tus hijos, luego crea sus tareas y recompensas.</p>
        <a href="{{ route('padre.hijos.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-indigo-700 transition inline-block">
            Añadir mi primer hijo →
        </a>
    </div>
@endif
@endsection
