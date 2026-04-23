@extends('layouts.app')

@section('title', 'Recompensas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Recompensas</h1>
        <p class="text-gray-500 text-sm">Premios que tus hijos pueden canjear con sus monedas</p>
    </div>
    <a href="{{ route('padre.recompensas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
        + Nueva recompensa
    </a>
</div>

@if($recompensas->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <span class="text-5xl block mb-4">🎁</span>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Aún no tienes recompensas</h3>
        <p class="text-gray-500 mb-6">Crea recompensas que tus hijos puedan canjear con sus monedas.</p>
        <a href="{{ route('padre.recompensas.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-indigo-700 transition inline-block">
            Crear primera recompensa
        </a>
    </div>
@else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($recompensas as $recompensa)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center text-2xl">🎁</div>
                    <div class="flex items-center gap-1 bg-yellow-100 text-yellow-700 font-bold px-3 py-1 rounded-full text-sm">
                        <span>🪙</span>
                        <span>{{ $recompensa->monedas_necesarias }}</span>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">{{ $recompensa->nombre }}</h3>
                @if($recompensa->descripcion)
                    <p class="text-gray-500 text-sm mb-3">{{ $recompensa->descripcion }}</p>
                @endif
                <form action="{{ route('padre.recompensas.destroy', $recompensa) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar la recompensa «{{ $recompensa->nombre }}»?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-sm text-red-500 hover:text-red-700 hover:bg-red-50 py-2 rounded-xl transition">
                        Eliminar
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endif
@endsection
