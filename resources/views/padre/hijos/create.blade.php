@extends('layouts.app')

@section('title', 'Añadir hijo')

@section('content')
<div class="mb-6">
    <a href="{{ route('padre.hijos.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm flex items-center gap-1">
        ← Volver a mis hijos
    </a>
</div>

<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Añadir hijo</h1>
    <p class="text-gray-500 text-sm mb-6">Crea el perfil de tu hijo para empezar a asignarle tareas</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('padre.hijos.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del hijo *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: Sofía">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Edad *</label>
                <input type="number" name="edad" value="{{ old('edad') }}" required min="1" max="18"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: 8">
                @error('edad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">PIN de acceso (4 dígitos) *</label>
                <input type="password" name="pin" required maxlength="4" pattern="\d{4}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: 1234">
                <p class="text-gray-400 text-xs mt-1">El hijo usará este PIN para acceder a su panel</p>
                @error('pin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tope de monedas (opcional)</label>
                <input type="number" name="monedas_tope" value="{{ old('monedas_tope') }}" min="0"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Sin límite">
                <p class="text-gray-400 text-xs mt-1">Máximo de monedas que puede acumular (déjalo vacío para sin límite)</p>
                @error('monedas_tope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition">
                    Crear perfil
                </button>
                <a href="{{ route('padre.hijos.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-xl transition text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
