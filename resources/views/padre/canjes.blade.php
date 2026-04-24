@extends('layouts.app')

@section('title', 'Canjes')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-gray-900">🏆 Canjes</h1>
    <p class="text-slate-500 text-sm mt-0.5">Solicitudes de recompensa de tus hijos</p>
</div>

@if($canjes->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-14 text-center">
        <span class="text-6xl block mb-4">🎀</span>
        <h3 class="text-lg font-extrabold text-gray-800 mb-2">Sin solicitudes de canje</h3>
        <p class="text-slate-500 text-sm">Cuando tus hijos soliciten una recompensa, aparecerán aquí.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($canjes as $canje)
            <div class="bg-white rounded-2xl shadow-sm border p-5 hover:shadow-md transition
                 {{ $canje->estado === 'PENDIENTE' ? 'border-amber-200' : ($canje->estado === 'APROBADO' ? 'border-blue-200' : 'border-slate-100') }}">
                <div class="flex items-start gap-4">
                    <!-- Icono -->
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm"
                         style="background: linear-gradient(135deg, #fdf2f8, #fce7f3);">
                        🎁
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 mb-1">
                            <div class="min-w-0">
                                <p class="font-extrabold text-gray-800">{{ $canje->recompensa->nombre }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">
                                    <span class="font-medium">{{ $canje->hijo->nombre }}</span>
                                    · {{ $canje->fecha_solicitud?->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="flex items-center gap-1 bg-amber-100 text-amber-700 font-extrabold px-2.5 py-1 rounded-full text-xs">
                                    <x-moneda /> {{ $canje->monedas_gastadas }}
                                </span>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $canje->estadoColor() }}">
                                    {{ $canje->estado }}
                                </span>
                            </div>
                        </div>

                        @if($canje->comentario)
                            <p class="text-sm text-slate-500 italic mb-3 bg-slate-50 px-3 py-2 rounded-xl">
                                "{{ $canje->comentario }}"
                            </p>
                        @endif

                        @if($canje->estado === 'PENDIENTE')
                            <div class="flex gap-2 mt-4 flex-wrap">
                                <form action="{{ route('padre.canjes.aprobar', $canje) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm">
                                        ✅ Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('padre.canjes.rechazar', $canje) }}" method="POST" class="flex gap-2 flex-1 min-w-0">
                                    @csrf
                                    <input type="text" name="comentario" placeholder="Motivo del rechazo" maxlength="500"
                                           class="flex-1 min-w-0 border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50">
                                    <button type="submit"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 text-sm font-bold px-4 py-2.5 rounded-xl transition border border-red-100 whitespace-nowrap">
                                        ❌ Rechazar
                                    </button>
                                </form>
                            </div>
                        @elseif($canje->estado === 'APROBADO')
                            <div class="flex items-center gap-3 mt-4 bg-blue-50 rounded-xl px-4 py-3">
                                <span class="text-blue-600 text-sm font-medium">✓ Aprobado — recuerda entregar la recompensa</span>
                                <form action="{{ route('padre.canjes.entregar', $canje) }}" method="POST" class="ml-auto">
                                    @csrf
                                    <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold px-4 py-2 rounded-xl transition shadow-sm whitespace-nowrap">
                                        📦 Entregar
                                    </button>
                                </form>
                            </div>
                        @elseif($canje->estado === 'ENTREGADO')
                            <div class="flex items-center gap-2 mt-3 text-emerald-600 text-sm font-medium">
                                <span>🎉</span>
                                <span>Entregado · {{ $canje->fecha_resolucion?->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
