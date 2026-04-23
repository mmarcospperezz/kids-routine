@extends('layouts.hijo')

@section('title', 'Tienda de recompensas')

@section('content')

<div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 mb-5 flex items-center justify-between text-white">
    <div>
        <p class="text-sm opacity-80">Tus monedas disponibles</p>
        <p class="text-3xl font-extrabold">🪙 {{ $hijo->monedas }}</p>
    </div>
    <div class="text-4xl">🏪</div>
</div>

@if($errors->any())
    <div class="mb-4 bg-red-400 text-white rounded-2xl px-4 py-3">
        {{ $errors->first() }}
    </div>
@endif

{{-- Canjes activos --}}
@if($canjesPendientes->isNotEmpty())
    <div class="mb-5">
        <h2 class="text-white font-bold text-lg mb-3">⏳ Mis solicitudes</h2>
        <div class="space-y-2">
            @foreach($canjesPendientes as $canje)
                <div class="bg-white rounded-2xl p-4 flex items-center gap-3">
                    <span class="text-2xl">🎁</span>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 text-sm">{{ $canje->recompensa->nombre }}</p>
                        <p class="text-xs text-gray-500">🪙 {{ $canje->monedas_gastadas }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full {{ $canje->estadoColor() }}">
                        {{ $canje->estado === 'PENDIENTE' ? 'Esperando...' : '¡Aprobado! 🎉' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Catálogo --}}
<h2 class="text-white font-bold text-lg mb-3">🎁 Recompensas disponibles</h2>

@if($recompensas->isEmpty())
    <div class="bg-white/20 rounded-2xl p-8 text-center text-white/80">
        <span class="text-4xl block mb-2">😕</span>
        <p>Aún no hay recompensas disponibles. ¡Tu padre/madre tiene que añadirlas!</p>
    </div>
@else
    <div class="grid grid-cols-2 gap-3">
        @foreach($recompensas as $recompensa)
            @php $puedeComprar = $hijo->monedas >= $recompensa->monedas_necesarias; @endphp
            <div class="bg-white rounded-2xl p-4 {{ !$puedeComprar ? 'opacity-60' : '' }}">
                <div class="text-3xl text-center mb-2">🎁</div>
                <p class="font-semibold text-gray-800 text-sm text-center mb-1">{{ $recompensa->nombre }}</p>
                @if($recompensa->descripcion)
                    <p class="text-xs text-gray-500 text-center mb-2">{{ $recompensa->descripcion }}</p>
                @endif
                <div class="flex items-center justify-center gap-1 text-yellow-600 font-bold text-sm mb-3">
                    <span>🪙</span>
                    <span>{{ $recompensa->monedas_necesarias }}</span>
                </div>

                @if($puedeComprar)
                    <form action="{{ route('hijo.recompensas.canjear', $recompensa) }}" method="POST"
                          onsubmit="return confirm('¿Canjear «{{ $recompensa->nombre }}» por {{ $recompensa->monedas_necesarias }} monedas?')">
                        @csrf
                        <button type="submit"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2 rounded-xl transition active:scale-95">
                            ¡Canjear!
                        </button>
                    </form>
                @else
                    <div class="w-full bg-gray-100 text-gray-400 text-xs font-medium py-2 rounded-xl text-center">
                        Faltan {{ $recompensa->monedas_necesarias - $hijo->monedas }} 🪙
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

@endsection
