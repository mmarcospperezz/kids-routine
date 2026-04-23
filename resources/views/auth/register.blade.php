@extends('layouts.guest')

@section('title', 'Crear cuenta')

@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-1">Crear cuenta</h2>
<p class="text-gray-500 text-sm mb-6">Empieza a gestionar las rutinas de tus hijos</p>

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register.post') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tu nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required autofocus
               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
               placeholder="Nombre completo">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
               placeholder="tu@email.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
        <input type="password" name="password" required
               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
               placeholder="Mínimo 6 caracteres">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" required
               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
               placeholder="Repite la contraseña">
    </div>

    <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition">
        Crear cuenta
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-6">
    ¿Ya tienes cuenta?
    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Iniciar sesión</a>
</p>
@endsection
