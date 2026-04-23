@extends('layouts.guest')

@section('title', 'Seleccionar perfil')

@section('content')
<div class="text-center mb-6">
    <span class="text-4xl block mb-2">👨‍👩‍👧‍👦</span>
    <h2 class="text-2xl font-bold text-gray-900">¿Quién eres?</h2>
    <p class="text-gray-500 text-sm mt-1">Selecciona tu perfil para continuar</p>
</div>

@if($hijos->isEmpty())
    <div class="text-center py-6">
        <p class="text-gray-500 mb-4">Aún no has añadido hijos a tu cuenta.</p>
        <a href="{{ route('padre.hijos.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition inline-block">
            Añadir hijo
        </a>
    </div>
@else
    <div class="space-y-3">
        @foreach($hijos as $hijo)
            <form action="{{ route('hijo.pin') }}" method="POST">
                @csrf
                <input type="hidden" name="id_hijo" value="{{ $hijo->id_hijo }}">
                <button type="submit"
                        class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50 transition text-left">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-pink-400 rounded-2xl flex items-center justify-center text-3xl">
                        {{ $hijo->avatarEmoji() }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">{{ $hijo->nombre }}</p>
                        <p class="text-sm text-gray-500">{{ $hijo->edad }} años · 🪙 {{ $hijo->monedas }} monedas</p>
                    </div>
                    <span class="ml-auto text-gray-400 text-xl">→</span>
                </button>
            </form>
        @endforeach
    </div>
@endif

<div class="mt-6 pt-4 border-t border-gray-100 text-center">
    <a href="{{ route('padre.dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">
        ← Volver al panel de padres
    </a>
</div>
@endsection
