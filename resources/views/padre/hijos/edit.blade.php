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
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-md"
             style="background: linear-gradient(135deg, #a855f7, #ec4899);">
            {{ $hijo->avatarEmoji() }}
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Editar: {{ $hijo->nombre }}</h1>
            <p class="text-slate-500 text-sm">{{ $hijo->edad }} años · 🪙 {{ $hijo->monedas }} monedas</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('padre.hijos.update', $hijo) }}" method="POST" class="space-y-5">
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
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Monedas actuales 🪙</label>
                <input type="number" name="monedas" value="{{ old('monedas', $hijo->monedas) }}" required min="0"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                <p class="text-slate-400 text-xs mt-1.5">💡 Puedes ajustar el saldo manualmente si es necesario</p>
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
                <input type="number" name="monedas_tope" value="{{ old('monedas_tope', $hijo->monedas_tope) }}" min="0"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Sin límite">
                @error('monedas_tope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
