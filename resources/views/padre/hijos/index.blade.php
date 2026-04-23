@extends('layouts.app')

@section('title', 'Mis hijos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Mis hijos</h1>
        <p class="text-gray-500 text-sm">Gestiona los perfiles de tus hijos</p>
    </div>
    <a href="{{ route('padre.hijos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2">
        <span>+</span> Añadir hijo
    </a>
</div>

@if($hijos->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <span class="text-5xl block mb-4">👦</span>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Aún no tienes hijos registrados</h3>
        <p class="text-gray-500 mb-6">Añade a tus hijos para empezar a asignarles tareas y recompensas.</p>
        <a href="{{ route('padre.hijos.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-indigo-700 transition inline-block">
            Añadir primer hijo
        </a>
    </div>
@else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($hijos as $hijo)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-3xl">
                        {{ $hijo->avatarEmoji() }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $hijo->nombre }}</h3>
                        <p class="text-gray-500 text-sm">{{ $hijo->edad }} años</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 bg-yellow-50 rounded-xl px-3 py-2 mb-4">
                    <span class="text-xl">🪙</span>
                    <span class="font-bold text-yellow-700">{{ $hijo->monedas }} monedas</span>
                    @if($hijo->monedas_tope)
                        <span class="text-yellow-500 text-xs ml-auto">máx. {{ $hijo->monedas_tope }}</span>
                    @endif
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('padre.hijos.edit', $hijo) }}"
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 rounded-xl text-center transition">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('padre.hijos.destroy', $hijo) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar a {{ $hijo->nombre }}? Esta acción no se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium px-3 py-2 rounded-xl transition">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
