@extends('layouts.hijo')

@section('title', 'Tienda de recompensas')

@section('content')

{{-- Balance --}}
<div class="card-fade bg-white/20 backdrop-blur-sm rounded-3xl p-5 mb-5 border border-white/25 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-white/75 text-sm font-medium">Tus monedas</p>
            <div class="flex items-center gap-2 mt-1">
                <span class="sparkle text-3xl"><x-moneda /></span>
                <span class="text-4xl font-extrabold text-white">{{ $hijo->monedas }}</span>
            </div>
        </div>
        <div class="text-6xl opacity-30">🏪</div>
    </div>
</div>

@if($errors->any())
    <div class="mb-4 bg-red-500/80 text-white rounded-2xl px-4 py-3 border border-red-400/50 card-fade">
        <p class="font-semibold text-sm">⚠️ {{ $errors->first() }}</p>
    </div>
@endif

{{-- Canjes pendientes --}}
@if($canjesPendientes->isNotEmpty())
    <div class="mb-6">
        <h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">⏳ Mis solicitudes</h2>
        <div class="space-y-2">
            @foreach($canjesPendientes as $canje)
                <div class="bg-white rounded-2xl p-4 flex items-center gap-3 shadow-md">
                    <div class="w-11 h-11 rounded-xl bg-pink-100 flex items-center justify-center text-2xl flex-shrink-0">🎁</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-sm truncate">{{ $canje->recompensa->nombre }}</p>
                        <p class="text-xs text-gray-500"><x-moneda /> {{ $canje->monedas_gastadas }}</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $canje->estadoColor() }} whitespace-nowrap">
                        {{ $canje->estado === 'PENDIENTE' ? '⏳ Esperando' : '✅ ¡Aprobado!' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Catálogo --}}
<h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">🎁 Recompensas disponibles</h2>

@if($recompensas->isEmpty())
    <div class="bg-white/20 backdrop-blur-sm rounded-3xl p-10 text-center border border-white/25">
        <span class="text-5xl block mb-3">😕</span>
        <p class="text-white font-bold">Aún no hay recompensas</p>
        <p class="text-white/70 text-sm mt-1">Pide a tu padre o madre que añada recompensas</p>
    </div>
@else
    <div class="grid grid-cols-2 gap-3">
        @foreach($recompensas as $recompensa)
            @php $puedeComprar = $hijo->monedas >= $recompensa->monedas_necesarias; @endphp
            <div class="reward-card bg-white rounded-2xl p-4 shadow-md {{ !$puedeComprar ? 'opacity-55' : '' }}">
                <!-- Icono -->
                <div class="text-4xl text-center mb-2">🎁</div>

                <!-- Nombre -->
                <p class="font-extrabold text-gray-800 text-sm text-center leading-tight mb-1">{{ $recompensa->nombre }}</p>

                @if($recompensa->descripcion)
                    <p class="text-xs text-gray-500 text-center mb-2 leading-tight">{{ $recompensa->descripcion }}</p>
                @endif

                <!-- Precio -->
                <div class="flex items-center justify-center gap-1 mb-3">
                    <span class="text-base"><x-moneda /></span>
                    <span class="font-extrabold text-yellow-600 text-base">{{ $recompensa->monedas_necesarias }}</span>
                </div>

                @if($puedeComprar)
                    <form action="{{ route('hijo.recompensas.canjear', $recompensa) }}" method="POST"
                          onsubmit="return confirm('¿Canjear {{ $recompensa->nombre }} por {{ $recompensa->monedas_necesarias }} monedas? 🎁')">
                        @csrf
                        <button type="submit"
                                class="btn-complete w-full text-white text-sm font-extrabold py-2.5 rounded-xl shadow-md"
                                style="background: linear-gradient(135deg, #7c3aed, #ec4899);">
                            ¡Canjear! 🎉
                        </button>
                    </form>
                @else
                    @php $faltan = $recompensa->monedas_necesarias - $hijo->monedas; @endphp
                    <div class="w-full bg-gray-100 text-gray-400 text-xs font-bold py-2.5 rounded-xl text-center">
                        Faltan {{ $faltan }} <x-moneda />
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

@endsection
