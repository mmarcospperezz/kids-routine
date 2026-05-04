@extends('layouts.app')

@section('title', 'Editar hijo')

@section('content')
<div class="mb-5">
    <a href="{{ route('padre.hijos.index') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 text-sm font-medium transition">
        ← Mis hijos
    </a>
</div>

<div class="max-w-lg">
    <div class="flex items-center gap-4 mb-6">
        @if($hijo->avatarUrl())
            <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                 class="w-14 h-14 rounded-2xl object-cover shadow-md border-2 border-slate-200">
        @else
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl text-white shadow-md"
                 style="background: {{ $hijo->avatarColor() }}">
                {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
            </div>
        @endif
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Editar: {{ $hijo->nombre }}</h1>
            <p class="text-slate-500 text-sm">{{ $hijo->edad }} años · <x-moneda /> {{ $hijo->monedas }} monedas</p>
        </div>
    </div>

    {{-- Ajuste rápido de monedas --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-5">
        <h2 class="text-sm font-extrabold text-amber-800 mb-3">⚡ Ajuste rápido de monedas</h2>
        @if(session('exito'))
            <div class="bg-green-100 text-green-800 rounded-xl px-4 py-2 text-sm mb-3">{{ session('exito') }}</div>
        @endif
        <form action="{{ route('padre.hijos.monedas.ajustar', $hijo) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="number" name="cantidad" placeholder="Ej: +10 o -5" step="1" required
                   onwheel="this.blur()"
                   class="flex-1 border border-amber-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-amber-400">
            <input type="text" name="motivo" placeholder="Motivo (ej: comportamiento ejemplar)" required maxlength="200"
                   class="flex-[2] border border-amber-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-amber-400">
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-sm">
                Aplicar
            </button>
        </form>
        @error('cantidad') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        @error('motivo')   <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('padre.hijos.update', $hijo) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $hijo->nombre) }}" required
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Edad *</label>
                <input type="number" name="edad" value="{{ old('edad', $hijo->edad) }}" required min="1" max="18"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                @error('edad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Monedas actuales <x-moneda /></label>
                <input type="number" name="monedas" value="{{ old('monedas', $hijo->monedas) }}" required min="0"
                       onwheel="this.blur()"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                @error('monedas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Cambiar PIN (opcional)</label>
                <input type="password" name="pin" maxlength="4" pattern="\d{4}" inputmode="numeric"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Dejar vacío para no cambiar">
                @error('pin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tope de monedas (opcional)</label>
                <input type="number" name="monedas_tope" value="{{ old('monedas_tope', $hijo->monedas_tope) }}" min="1"
                       onwheel="this.blur()"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Sin límite">
                @error('monedas_tope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Foto de perfil (opcional)</label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    @if($hijo->avatarUrl())
                        <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                             class="w-12 h-12 rounded-xl object-cover border-2 border-slate-200 group-hover:border-indigo-400 transition">
                    @else
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-xl text-white border-2 border-slate-200 group-hover:border-indigo-400 transition"
                             style="background: {{ $hijo->avatarColor() }}">
                            {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <span class="block text-sm font-medium text-indigo-600 group-hover:text-indigo-700">📷 Cambiar foto</span>
                        <span class="text-xs text-slate-400">JPG, PNG o GIF · máx 2MB</span>
                    </div>
                    <input type="file" name="avatar" accept="image/*" class="hidden">
                </label>
                @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-sm">
                    Guardar cambios
                </button>
                <a href="{{ route('padre.hijos.index') }}"
                   class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
