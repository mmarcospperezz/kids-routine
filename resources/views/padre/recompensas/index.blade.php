@extends('layouts.app')

@section('title', 'Recompensas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900">🎁 Recompensas</h1>
        <p class="text-slate-500 text-sm mt-0.5">Premios que tus hijos pueden canjear con sus monedas</p>
    </div>
    <a href="{{ route('padre.recompensas.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-sm flex items-center gap-1.5">
        <span>+</span> Nueva recompensa
    </a>
</div>

@if($recompensas->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-14 text-center">
        <span class="text-6xl block mb-4">🎁</span>
        <h3 class="text-lg font-extrabold text-gray-800 mb-2">Aún no tienes recompensas</h3>
        <p class="text-slate-500 text-sm mb-6">Crea recompensas que tus hijos puedan canjear con sus monedas.</p>
        <a href="{{ route('padre.recompensas.create') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-md">
            Crear primera recompensa →
        </a>
    </div>
@else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($recompensas as $recompensa)
            <div class="card-hover bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm"
                         style="background: linear-gradient(135deg, #fdf2f8, #fce7f3);">
                        🎁
                    </div>
                    <div class="flex items-center gap-1 bg-amber-100 text-amber-700 font-extrabold px-3 py-1.5 rounded-full text-sm shadow-sm">
                        <span><x-moneda /></span>
                        <span>{{ $recompensa->monedas_necesarias }}</span>
                    </div>
                </div>

                <h3 class="font-extrabold text-gray-800 text-base mb-1">{{ $recompensa->nombre }}</h3>
                <div class="flex gap-1.5 mb-2 flex-wrap">
                    @if(($recompensa->tipo ?? 'FISICA') === 'VIRTUAL')
                        <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full">💻 Virtual</span>
                    @else
                        <span class="text-[10px] bg-pink-100 text-pink-700 font-bold px-2 py-0.5 rounded-full">🎁 Física</span>
                    @endif
                    @if($recompensa->recurrente ?? false)
                        <span class="text-[10px] bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">🔄 Recurrente</span>
                    @endif
                </div>
                @if($recompensa->descripcion)
                    <p class="text-slate-500 text-sm mb-3 leading-snug flex-1">{{ $recompensa->descripcion }}</p>
                @endif

                <div class="mt-auto pt-3 border-t border-slate-100">
                    <form action="{{ route('padre.recompensas.destroy', $recompensa) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar la recompensa «{{ $recompensa->nombre }}»?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full text-sm text-red-400 hover:text-red-600 hover:bg-red-50 py-2 rounded-xl transition font-medium">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
