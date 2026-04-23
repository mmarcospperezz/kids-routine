@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Bienvenido de nuevo 👋</h2>
    <p class="text-gray-500 text-sm mt-1">Accede a tu panel de padres</p>
</div>

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-3.5 text-sm flex items-center gap-2">
        <span class="text-base">⚠️</span>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<form action="{{ route('login.post') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="input-field"
               placeholder="tu@email.com">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Contraseña</label>
        <input type="password" name="password" required
               class="input-field"
               placeholder="••••••••">
    </div>

    <div class="flex items-center gap-2 pt-1">
        <input type="checkbox" name="remember" id="remember"
               class="w-4 h-4 rounded text-indigo-600">
        <label for="remember" class="text-sm text-gray-600">Recordarme</label>
    </div>

    <button type="submit" class="btn-primary">
        Iniciar sesión →
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-5">
    ¿No tienes cuenta?
    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-purple-600 font-bold transition">Regístrate gratis</a>
</p>

<div class="mt-4 p-3.5 bg-indigo-50 rounded-2xl border border-indigo-100">
    <p class="text-xs text-indigo-600 font-semibold">🧪 Cuenta demo:</p>
    <p class="text-xs text-indigo-500 mt-0.5">demo@kidsroutine.com · demo123</p>
</div>
@endsection
