@extends('layouts.hijo')

@section('title', 'Mis tareas')

@section('content')

@php
    $pct = $tareasTotal > 0 ? round(($tareasCompletadas / $tareasTotal) * 100) : 0;
    $allDone = $tareasTotal > 0 && $tareasCompletadas === $tareasTotal;
    $racha = $hijo->racha_actual ?? 0;
    $nivel = $hijo->nivel();
    $monedasNext = $hijo->monedas_para_siguiente_nivel();
@endphp

{{-- Nivel + Racha --}}
<div class="grid grid-cols-2 gap-3 mb-4">
    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 border border-white/25 text-center">
        <div class="text-3xl font-extrabold text-white">{{ $nivel }}</div>
        <div class="text-white/80 text-xs font-bold mt-0.5">Nivel</div>
        <div class="text-white/60 text-xs mt-1">{{ $monedasNext }} <x-moneda class="inline w-3 h-3"/> para nivel {{ $nivel + 1 }}</div>
    </div>
    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 border border-white/25 text-center">
        <div class="text-3xl font-extrabold text-white">🔥 {{ $racha }}</div>
        <div class="text-white/80 text-xs font-bold mt-0.5">Racha</div>
        <div class="text-white/60 text-xs mt-1">Máximo: {{ $hijo->racha_max ?? 0 }} días</div>
    </div>
</div>

{{-- Progreso del día --}}
<div class="card-fade bg-white/20 backdrop-blur-sm rounded-3xl p-5 mb-5 border border-white/25 shadow-lg">
    <div class="flex items-center justify-between mb-3">
        <div>
            <p class="text-white font-extrabold text-base">⚡ Progreso de hoy</p>
            <p class="text-white/70 text-sm">{{ $tareasCompletadas }} de {{ $tareasTotal }} completadas</p>
        </div>
        <div class="text-3xl font-extrabold text-white">{{ $pct }}%</div>
    </div>

    @if($tareasTotal > 0)
        <div class="w-full bg-white/20 rounded-full h-4 overflow-hidden">
            <div class="progress-bar h-4 rounded-full shadow-inner"
                 style="--progress: {{ $pct }}%; background: linear-gradient(90deg, #fbbf24, #f59e0b);">
            </div>
        </div>

        @if($allDone)
            <div class="mt-3 text-center">
                <p class="text-yellow-300 font-extrabold text-lg animate-bounce">
                    🎉 ¡Eres increíble! ¡Lo has hecho todo! 🎉
                </p>
            </div>
        @endif
    @else
        <p class="text-white/60 text-sm text-center py-2">No tienes tareas para hoy</p>
    @endif
</div>

{{-- Tareas de hoy --}}
<div class="mb-5">
    <h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">📋 Mis tareas de hoy</h2>

    @if($instanciasHoy->isEmpty())
        <div class="bg-white/20 backdrop-blur-sm rounded-3xl p-10 text-center border border-white/25">
            <span class="text-6xl block mb-3">🌟</span>
            <p class="text-white font-bold text-lg">¡Sin tareas por hoy!</p>
            <p class="text-white/70 text-sm mt-1">Descansa y disfruta 🎈</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($instanciasHoy as $instancia)
                @php
                    $estado = $instancia->estado;
                    $icons = ['PENDIENTE'=>'📌','COMPLETADA'=>'⏳','VALIDADA'=>'✅','RECHAZADA'=>'❌','CADUCADA'=>'💤'];
                    $colors = [
                        'PENDIENTE'  => 'bg-amber-50  border-amber-200',
                        'COMPLETADA' => 'bg-blue-50   border-blue-200',
                        'VALIDADA'   => 'bg-green-50  border-green-200',
                        'RECHAZADA'  => 'bg-red-50    border-red-200',
                        'CADUCADA'   => 'bg-gray-50   border-gray-200',
                    ];
                    $franjaLabel = $instancia->tarea->franjaLabel();
                @endphp
                <div class="task-card bg-white rounded-2xl p-4 border {{ $colors[$estado] ?? 'border-gray-200' }} shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0
                             {{ $estado === 'VALIDADA' ? 'bg-green-100' : ($estado === 'RECHAZADA' ? 'bg-red-100' : ($estado === 'COMPLETADA' ? 'bg-blue-100' : 'bg-amber-100')) }}">
                            {{ $icons[$estado] ?? '📌' }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 text-sm leading-tight">{{ $instancia->tarea->titulo }}</p>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <span class="text-xs font-semibold text-yellow-600 flex items-center gap-0.5">
                                    <x-moneda /> {{ $instancia->tarea->monedas_recompensa }}
                                </span>
                                @if($franjaLabel)
                                    <span class="text-xs text-slate-400">{{ $franjaLabel }}</span>
                                @endif
                                @if($instancia->tarea->categoria)
                                    <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $instancia->tarea->categoria }}</span>
                                @endif
                                @if($instancia->comentario_padre && $estado === 'RECHAZADA')
                                    <span class="text-xs text-red-500">· {{ $instancia->comentario_padre }}</span>
                                @endif
                            </div>
                            {{-- Foto prueba subida --}}
                            @if($instancia->foto_prueba && $estado === 'COMPLETADA')
                                <p class="text-xs text-blue-500 mt-1">📷 Foto enviada</p>
                            @endif
                        </div>

                        @if($estado === 'PENDIENTE')
                            <div>
                                <button type="button"
                                        onclick="this.closest('div').querySelector('.form-completar').classList.toggle('hidden'); this.classList.add('hidden')"
                                        class="btn-complete bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-md">
                                    ¡Listo! ✓
                                </button>
                                <div class="form-completar hidden mt-3">
                                    <form action="{{ route('hijo.tareas.completar', $instancia) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        {{-- Selector de foto personalizado --}}
                                        <input type="file" name="foto_prueba" id="foto_{{ $instancia->id_instancia }}"
                                               accept="image/*" capture="environment" class="hidden"
                                               onchange="previewFoto(this, 'preview_{{ $instancia->id_instancia }}', 'zona_{{ $instancia->id_instancia }}')">
                                        <label for="foto_{{ $instancia->id_instancia }}"
                                               id="zona_{{ $instancia->id_instancia }}"
                                               class="flex flex-col items-center gap-1 w-full border-2 border-dashed border-purple-300 rounded-2xl py-3 px-3 cursor-pointer bg-purple-50 hover:bg-purple-100 transition mb-2">
                                            <span class="text-2xl">📷</span>
                                            <span class="text-xs font-bold text-purple-600">Añadir foto (opcional)</span>
                                            <span class="text-xs text-slate-400">Toca para abrir la cámara</span>
                                        </label>
                                        <div id="preview_{{ $instancia->id_instancia }}" class="hidden mb-2 relative">
                                            <img src="" alt="Vista previa"
                                                 class="w-full max-h-32 object-cover rounded-xl border-2 border-purple-300">
                                            <button type="button"
                                                    onclick="quitarFoto('foto_{{ $instancia->id_instancia }}', 'preview_{{ $instancia->id_instancia }}', 'zona_{{ $instancia->id_instancia }}')"
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 text-xs font-bold flex items-center justify-center">✕</button>
                                        </div>
                                        <button type="submit"
                                                class="w-full text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-md"
                                                style="background:linear-gradient(135deg,#7c3aed,#a855f7)">
                                            Confirmar ✓
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif($estado === 'COMPLETADA')
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full whitespace-nowrap">
                                ⏳ Esperando...
                            </span>
                        @elseif($estado === 'VALIDADA')
                            <div class="flex flex-col items-center">
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full whitespace-nowrap">
                                    ✅ ¡Validado!
                                </span>
                                <span class="text-xs font-extrabold text-amber-500 mt-0.5 flex items-center gap-0.5">
                                    +{{ $instancia->tarea->monedas_recompensa }} <x-moneda class="inline w-3 h-3"/>
                                </span>
                            </div>
                        @elseif($estado === 'RECHAZADA')
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full">
                                ❌ Rechazada
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Ranking entre hermanos --}}
@if(isset($ranking) && $ranking->count() > 1)
<div class="mb-5">
    <h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">🏆 Ranking esta semana</h2>
    <div class="bg-white/20 backdrop-blur-sm rounded-2xl border border-white/25 overflow-hidden">
        @foreach($ranking as $i => $h)
        <div class="flex items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-b border-white/10' : '' }}
                    {{ $h->id_hijo === $hijo->id_hijo ? 'bg-white/10' : '' }}">
            <div class="text-xl font-extrabold text-white/80 w-6">
                {{ ['🥇','🥈','🥉'][$i] ?? ($i+1) . '.' }}
            </div>
            @if($h->avatarUrl())
                <img src="{{ $h->avatarUrl() }}" class="w-9 h-9 rounded-xl object-cover">
            @else
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold"
                     style="background: {{ $h->avatarColor() }}">
                    {{ mb_strtoupper(mb_substr($h->nombre, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1">
                <p class="text-white font-bold text-sm">{{ $h->nombre }}
                    @if($h->id_hijo === $hijo->id_hijo)<span class="text-white/60 text-xs">(tú)</span>@endif
                </p>
            </div>
            <div class="text-white font-extrabold text-sm flex items-center gap-1">
                <x-moneda /> {{ $h->monedas_semana ?? 0 }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Logros --}}
@if(isset($logros) && $logros->isNotEmpty())
<div class="mb-5">
    <h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">🏅 Mis logros</h2>
    <div class="flex flex-wrap gap-2">
        @foreach($logros as $logro)
        <div class="bg-white/20 backdrop-blur-sm border border-white/25 rounded-2xl px-4 py-2 flex items-center gap-2">
            <span class="text-xl">{{ $logro->icono }}</span>
            <div>
                <p class="text-white font-bold text-xs">{{ $logro->titulo }}</p>
                <p class="text-white/60 text-xs">{{ $logro->pivot->fecha_obtenido ? \Carbon\Carbon::parse($logro->pivot->fecha_obtenido)->format('d/m/y') : '' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Canjes activos --}}
@if($canjesPendientes->isNotEmpty())
    <div class="mb-5">
        <h2 class="text-white font-extrabold text-lg mb-3 drop-shadow">🎀 Mis canjes</h2>
        <div class="space-y-2">
            @foreach($canjesPendientes as $canje)
                <div class="task-card bg-white rounded-2xl p-4 flex items-center gap-3 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-pink-100 flex items-center justify-center text-2xl flex-shrink-0">🎁</div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 text-sm">{{ $canje->recompensa->nombre }}</p>
                        <p class="text-xs text-gray-500 mt-0.5"><x-moneda /> {{ $canje->monedas_gastadas }} monedas</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $canje->estadoColor() }}">
                        {{ $canje->estado === 'PENDIENTE' ? '⏳ Esperando' : '✅ ¡Aprobado!' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Botón a la tienda --}}
<a href="{{ route('hijo.recompensas') }}"
   class="block text-center font-extrabold text-yellow-900 py-4 rounded-2xl shadow-xl transition mt-2 border-2 border-yellow-300"
   style="background: linear-gradient(135deg, #fbbf24, #f59e0b); font-size: 16px;">
    🛍️ Ir a la tienda de recompensas →
</a>

<script>
function previewFoto(input, previewId, zonaId) {
    const preview = document.getElementById(previewId);
    const zona = document.getElementById(zonaId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            zona.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function quitarFoto(inputId, previewId, zonaId) {
    document.getElementById(inputId).value = '';
    document.getElementById(previewId).classList.add('hidden');
    document.getElementById(zonaId).classList.remove('hidden');
}
</script>
@endsection
