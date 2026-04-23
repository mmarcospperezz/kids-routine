@extends('layouts.app')

@section('title', 'Añadir hijo')

@section('content')
<div class="mb-5">
    <a href="{{ route('padre.hijos.index') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 text-sm font-medium transition">
        ← Mis hijos
    </a>
</div>

<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Añadir hijo 👦</h1>
        <p class="text-slate-500 text-sm mt-1">Crea el perfil de tu hijo para empezar a asignarle tareas</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('padre.hijos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required autofocus
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Ej: Sofía">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Edad *</label>
                <input type="number" name="edad" value="{{ old('edad') }}" required min="1" max="18"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Ej: 8">
                @error('edad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">PIN de acceso (4 dígitos) *</label>
                <input type="password" name="pin" required maxlength="4" pattern="\d{4}" inputmode="numeric"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Ej: 1234">
                <p class="text-slate-400 text-xs mt-1.5">💡 El hijo usará este PIN para acceder a su panel</p>
                @error('pin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tope de monedas (opcional)</label>
                <input type="number" name="monedas_tope" value="{{ old('monedas_tope') }}" min="0"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Sin límite">
                <p class="text-slate-400 text-xs mt-1.5">Máximo de monedas que puede acumular</p>
                @error('monedas_tope') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Foto de perfil (opcional)</label>
                <label class="flex items-center gap-3 cursor-pointer group border border-dashed border-slate-300 hover:border-indigo-400 rounded-xl px-4 py-3 transition">
                    <span class="text-2xl">📷</span>
                    <div>
                        <span class="block text-sm font-medium text-indigo-600 group-hover:text-indigo-700">Subir foto</span>
                        <span class="text-xs text-slate-400">JPG, PNG o GIF · máx 2MB</span>
                    </div>
                    <input type="file" name="avatar" accept="image/*" class="hidden"
                           onchange="this.previousElementSibling.previousElementSibling.textContent = this.files[0]?.name ?? '📷'">
                </label>
                @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-sm">
                    Crear perfil →
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
