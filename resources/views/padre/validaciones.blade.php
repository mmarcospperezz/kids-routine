@extends('layouts.app')

@section('title', 'Validaciones')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-gray-900">🔍 Validaciones</h1>
    <p class="text-slate-500 text-sm mt-0.5">Tareas completadas por tus hijos que esperan tu revisión</p>
</div>

@if($instancias->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-14 text-center">
        <span class="text-6xl block mb-4">🎉</span>
        <h3 class="text-lg font-extrabold text-gray-800 mb-2">¡Todo al día!</h3>
        <p class="text-slate-500 text-sm">No hay tareas pendientes de validación. ¡Bien hecho!</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($instancias as $instancia)
            <div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-5 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <!-- Avatar hijo -->
                    @if($instancia->hijo->avatarUrl())
                        <img src="{{ $instancia->hijo->avatarUrl() }}" alt="{{ $instancia->hijo->nombre }}"
                             class="w-12 h-12 rounded-2xl object-cover flex-shrink-0 shadow-sm">
                    @else
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-xl text-white flex-shrink-0 shadow-sm"
                             style="background: {{ $instancia->hijo->avatarColor() }}">
                            {{ mb_strtoupper(mb_substr($instancia->hijo->nombre, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 mb-1">
                            <div class="min-w-0">
                                <p class="font-extrabold text-gray-800">{{ $instancia->tarea->titulo }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">
                                    <span class="font-medium">{{ $instancia->hijo->nombre }}</span>
                                    · completada {{ $instancia->fecha_completada?->diffForHumans() }}
                                    @if($instancia->tarea->franjaLabel())
                                        · {{ $instancia->tarea->franjaLabel() }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1 bg-amber-100 text-amber-700 font-extrabold px-3 py-1 rounded-full text-sm flex-shrink-0">
                                <span><x-moneda /></span>
                                <span>+{{ $instancia->tarea->monedas_recompensa }}</span>
                            </div>
                        </div>

                        {{-- Foto de prueba --}}
                        @if($instancia->foto_prueba)
                            <div class="mt-2 mb-3">
                                <p class="text-xs font-bold text-slate-500 mb-1">📷 Foto de prueba:</p>
                                <img src="{{ $instancia->foto_prueba }}" alt="Foto prueba"
                                     class="max-h-40 rounded-xl border border-slate-200 shadow-sm cursor-pointer"
                                     onclick="this.classList.toggle('max-h-40')">
                            </div>
                        @endif

                        <!-- Acciones -->
                        <div class="flex gap-2 mt-4 flex-wrap">
                            <form action="{{ route('padre.instancias.validar', $instancia) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm">
                                    ✅ Validar
                                </button>
                            </form>

                            <form action="{{ route('padre.instancias.rechazar', $instancia) }}" method="POST" class="flex gap-2 flex-1 min-w-0">
                                @csrf
                                <input type="text" name="comentario" placeholder="Motivo del rechazo (opcional)" maxlength="500"
                                       class="flex-1 min-w-0 border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50">
                                <button type="submit"
                                        class="bg-red-50 hover:bg-red-100 text-red-600 text-sm font-bold px-4 py-2.5 rounded-xl transition border border-red-100 whitespace-nowrap">
                                    ❌ Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
