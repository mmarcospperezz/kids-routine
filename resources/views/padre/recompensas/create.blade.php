@extends('layouts.app')

@section('title', 'Nueva recompensa')

@section('content')
<div class="mb-6">
    <a href="{{ route('padre.recompensas.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm flex items-center gap-1">
        ← Volver a recompensas
    </a>
</div>

<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Nueva recompensa</h1>
    <p class="text-gray-500 text-sm mb-6">Define un premio que tus hijos puedan canjear con monedas</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('padre.recompensas.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la recompensa *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="150"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: 1 hora de tablet">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción (opcional)</label>
                <textarea name="descripcion" rows="2" maxlength="500"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                          placeholder="Detalles adicionales...">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monedas necesarias *</label>
                <input type="number" name="monedas_necesarias" value="{{ old('monedas_necesarias', 10) }}" required min="1" max="99999"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('monedas_necesarias') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-yellow-50 rounded-xl p-4 text-sm text-yellow-800 flex items-start gap-2">
                <span>💡</span>
                <span>Esta recompensa estará disponible para <strong>todos tus hijos</strong>. Asegúrate de que el coste en monedas sea apropiado para cada uno.</span>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition">
                    Crear recompensa
                </button>
                <a href="{{ route('padre.recompensas.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-xl transition text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
