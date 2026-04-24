@extends('layouts.app')

@section('title', 'Nueva recompensa')

@section('content')
<div class="mb-5">
    <a href="{{ route('padre.recompensas.index') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 text-sm font-medium transition">
        ← Recompensas
    </a>
</div>

<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Nueva recompensa 🎁</h1>
        <p class="text-slate-500 text-sm mt-1">Define un premio que tus hijos puedan canjear con monedas</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('padre.recompensas.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nombre de la recompensa *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="150" autofocus
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Ej: 1 hora de tablet">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Descripción (opcional)</label>
                <textarea name="descripcion" rows="2" maxlength="500"
                          class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white resize-none"
                          placeholder="Detalles adicionales...">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Monedas necesarias *</label>
                <div class="flex items-center gap-3">
                    <span class="text-2xl"><x-moneda /></span>
                    <input type="number" name="monedas_necesarias" value="{{ old('monedas_necesarias', 10) }}" required min="1" max="99999"
                           class="flex-1 border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                </div>
                @error('monedas_necesarias') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-start gap-3">
                <span class="text-xl flex-shrink-0">💡</span>
                <p class="text-sm text-amber-800">Esta recompensa estará disponible para <strong>todos tus hijos</strong>. Asegúrate de que el coste en monedas sea apropiado.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-sm">
                    Crear recompensa →
                </button>
                <a href="{{ route('padre.recompensas.index') }}"
                   class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
