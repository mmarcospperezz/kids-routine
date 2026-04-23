@extends('layouts.app')

@section('title', 'Editar hijo')

@section('content')
<div class="mb-6">
    <a href="{{ route('padre.hijos.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm flex items-center gap-1">
        ← Volver a mis hijos
    </a>
</div>

<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Editar: {{ $hijo->nombre }}</h1>
    <p class="text-gray-500 text-sm mb-6">Modifica el perfil de tu hijo</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('padre.hijos.update', $hijo) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $hijo->nombre) }}" required
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Edad *</label>
                <input type="number" name="edad" value="{{ old('edad', $hijo->edad) }}" required min="1" max="18"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('edad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monedas actuales</label>
                <input type="number" name="monedas" value="{{ old('monedas', $hijo->monedas) }}" required min="0"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <p class="text-gray-400 text-xs mt-1">Puedes ajustar el saldo manualmente si es necesario</p>
                @error('monedas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cambiar PIN (opcional)</label>
                <input type="password" name="pin" maxlength="4" pattern="\d{4}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Dejar vacío para no cambiar">
                @error('pin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tope de monedas (opcional)</label>
                <input type="number" name="monedas_tope" value="{{ old('monedas_tope', $hijo->monedas_tope) }}" min="0"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Sin límite">
                @error('monedas_tope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition">
                    Guardar cambios
                </button>
                <a href="{{ route('padre.hijos.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-xl transition text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
