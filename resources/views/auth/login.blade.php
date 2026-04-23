@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-1">Bienvenido de nuevo</h2>
<p class="text-gray-500 text-sm mb-6">Accede a tu panel de padres</p>

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm">
        {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('login.post') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
               placeholder="tu@email.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
        <input type="password" name="password" required
               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
               placeholder="••••••">
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember" class="rounded">
        <label for="remember" class="text-sm text-gray-600">Recordarme</label>
    </div>

    <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition">
        Iniciar sesión
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-6">
    ¿No tienes cuenta?
    <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Regístrate gratis</a>
</p>

<div class="mt-4 p-3 bg-indigo-50 rounded-xl text-xs text-indigo-700">
    <strong>Demo:</strong> demo@kidsroutine.com / demo123
</div>
@endsection
