@extends('layouts.app')

@section('title', 'Canjes')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Canjes</h1>
    <p class="text-gray-500 text-sm">Solicitudes de recompensa de tus hijos</p>
</div>

@if($canjes->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <span class="text-5xl block mb-4">🎀</span>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Sin solicitudes de canje</h3>
        <p class="text-gray-500">Cuando tus hijos soliciten una recompensa, aparecerán aquí.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($canjes as $canje)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 {{ $canje->estado === 'PENDIENTE' ? 'border-yellow-200' : '' }}">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                        🎁
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4 mb-1">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $canje->recompensa->nombre }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $canje->hijo->nombre }} · {{ $canje->fecha_solicitud?->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="flex items-center gap-1 text-yellow-600 font-semibold text-sm">
                                    <span>🪙</span>{{ $canje->monedas_gastadas }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full {{ $canje->estadoColor() }}">
                                    {{ $canje->estado }}
                                </span>
                            </div>
                        </div>

                        @if($canje->comentario)
                            <p class="text-sm text-gray-500 italic mb-3">{{ $canje->comentario }}</p>
                        @endif

                        @if($canje->estado === 'PENDIENTE')
                            <div class="flex gap-2 mt-3">
                                <form action="{{ route('padre.canjes.aprobar', $canje) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                                        ✅ Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('padre.canjes.rechazar', $canje) }}" method="POST" class="flex gap-2 flex-1">
                                    @csrf
                                    <input type="text" name="comentario" placeholder="Motivo del rechazo" maxlength="500"
                                           class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium px-4 py-2 rounded-xl transition">
                                        ❌ Rechazar
                                    </button>
                                </form>
                            </div>
                        @elseif($canje->estado === 'APROBADO')
                            <div class="flex items-center gap-3 mt-3">
                                <span class="text-sm text-blue-600">✓ Aprobado — recuerda entregar la recompensa</span>
                                <form action="{{ route('padre.canjes.entregar', $canje) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                                        📦 Marcar como entregado
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
