@extends('layouts.app')

@section('title', 'Mis hijos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900">👨‍👧‍👦 Mis hijos</h1>
        <p class="text-slate-500 text-sm mt-0.5">Gestiona los perfiles de tus hijos</p>
    </div>
    <a href="{{ route('padre.hijos.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-sm flex items-center gap-1.5">
        <span class="text-base">+</span> Añadir hijo
    </a>
</div>

@if($hijos->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-14 text-center">
        <span class="text-6xl block mb-4">👦</span>
        <h3 class="text-lg font-extrabold text-gray-800 mb-2">Aún no tienes hijos registrados</h3>
        <p class="text-slate-500 text-sm mb-6">Añade a tus hijos para empezar a asignarles tareas y recompensas.</p>
        <a href="{{ route('padre.hijos.create') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-md">
            Añadir primer hijo →
        </a>
    </div>
@else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($hijos as $hijo)
            <div class="card-hover bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <!-- Top con gradiente -->
                <div class="h-24 flex items-end px-5 pb-0 relative"
                     style="background: linear-gradient(135deg, #eef2ff 0%, #fdf2f8 100%);">
                    <div class="absolute top-4 right-4">
                        @if($hijo->estaBloqueado())
                            <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded-full">🔒 Bloqueado</span>
                        @else
                            <span class="bg-green-100 text-green-600 text-[10px] font-bold px-2 py-1 rounded-full">✓ Activo</span>
                        @endif
                    </div>
                    <!-- Avatar sobreexpuesto -->
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-4xl shadow-lg border-4 border-white -mb-8"
                         style="background: linear-gradient(135deg, #a855f7, #ec4899);">
                        {{ $hijo->avatarEmoji() }}
                    </div>
                </div>

                <div class="pt-10 px-5 pb-5">
                    <h3 class="font-extrabold text-gray-900 text-lg">{{ $hijo->nombre }}</h3>
                    <p class="text-slate-500 text-xs">{{ $hijo->edad }} años</p>

                    <!-- Monedas -->
                    <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2 mt-3 mb-4">
                        <span class="text-xl">🪙</span>
                        <span class="font-extrabold text-amber-700 text-base">{{ $hijo->monedas }}</span>
                        <span class="text-amber-600 text-xs">monedas</span>
                        @if($hijo->monedas_tope)
                            <span class="ml-auto text-amber-400 text-xs">máx. {{ $hijo->monedas_tope }}</span>
                        @endif
                    </div>

                    <!-- Acciones -->
                    <div class="flex gap-2">
                        <a href="{{ route('padre.hijos.edit', $hijo) }}"
                           class="flex-1 flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-indigo-100 hover:text-indigo-700 text-slate-700 text-sm font-bold py-2.5 rounded-xl transition">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('padre.hijos.destroy', $hijo) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar a {{ $hijo->nombre }}? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-12 h-10 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl text-lg transition flex items-center justify-center">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
