@extends('layouts.guest')

@section('title', 'Crear cuenta')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Crear cuenta 🚀</h2>
    <p class="text-gray-500 text-sm mt-1">Empieza a gestionar las rutinas de tus hijos</p>
</div>

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-3.5 text-sm">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li class="flex items-center gap-1.5"><span>⚠️</span> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register.post') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tu nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required autofocus
               class="input-field"
               placeholder="Nombre completo">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="input-field"
               placeholder="tu@email.com">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Contraseña</label>
        <input type="password" name="password" required
               class="input-field"
               placeholder="Mínimo 6 caracteres">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" required
               class="input-field"
               placeholder="Repite la contraseña">
    </div>

    <button type="submit" class="btn-primary">
        Crear cuenta →
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-5">
    ¿Ya tienes cuenta?
    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-purple-600 font-bold transition">Iniciar sesión</a>
</p>
@endsection
