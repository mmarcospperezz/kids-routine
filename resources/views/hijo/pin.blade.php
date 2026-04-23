@extends('layouts.guest')

@section('title', 'Introduce tu PIN')

@section('content')
<div class="text-center mb-6">
    <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-400 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-3">
        {{ $hijo->avatarEmoji() }}
    </div>
    <h2 class="text-2xl font-bold text-gray-900">¡Hola, {{ $hijo->nombre }}!</h2>
    <p class="text-gray-500 text-sm mt-1">Introduce tu PIN de 4 dígitos</p>
</div>

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm text-center">
        {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('hijo.verificarPin') }}" method="POST" id="pinForm">
    @csrf
    <input type="hidden" name="id_hijo" value="{{ $hijo->id_hijo }}">

    <div class="flex justify-center gap-3 mb-6">
        @for($i = 1; $i <= 4; $i++)
        <div class="w-14 h-14 border-2 border-gray-300 rounded-2xl flex items-center justify-center text-2xl font-bold text-gray-700 pin-slot" id="slot{{ $i }}">
            <span class="dot hidden">●</span>
        </div>
        @endfor
    </div>

    <input type="hidden" name="pin" id="pinInput" maxlength="4">

    {{-- Teclado numérico --}}
    <div class="grid grid-cols-3 gap-3 mb-4">
        @foreach([1, 2, 3, 4, 5, 6, 7, 8, 9] as $n)
        <button type="button" onclick="addDigit('{{ $n }}')"
                class="h-14 bg-gray-100 hover:bg-indigo-100 hover:text-indigo-700 rounded-2xl text-xl font-semibold transition active:scale-95">
            {{ $n }}
        </button>
        @endforeach
        <button type="button" onclick="clearPin()"
                class="h-14 bg-gray-100 hover:bg-red-100 hover:text-red-600 rounded-2xl text-sm font-medium transition">
            Borrar
        </button>
        <button type="button" onclick="addDigit('0')"
                class="h-14 bg-gray-100 hover:bg-indigo-100 hover:text-indigo-700 rounded-2xl text-xl font-semibold transition active:scale-95">
            0
        </button>
        <button type="submit"
                class="h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xl font-semibold transition active:scale-95">
            →
        </button>
    </div>
</form>

<div class="text-center mt-4">
    <a href="{{ route('hijo.seleccionar') }}" class="text-sm text-gray-500 hover:text-indigo-600">
        ← Cambiar de perfil
    </a>
</div>

<script>
let pin = '';

function addDigit(d) {
    if (pin.length >= 4) return;
    pin += d;
    updateSlots();
    if (pin.length === 4) {
        document.getElementById('pinInput').value = pin;
        setTimeout(() => document.getElementById('pinForm').submit(), 100);
    }
}

function clearPin() {
    pin = '';
    updateSlots();
}

function updateSlots() {
    for (let i = 1; i <= 4; i++) {
        const slot = document.getElementById('slot' + i);
        const dot = slot.querySelector('.dot');
        if (i <= pin.length) {
            slot.classList.add('border-indigo-500', 'bg-indigo-50');
            slot.classList.remove('border-gray-300');
            dot.classList.remove('hidden');
        } else {
            slot.classList.remove('border-indigo-500', 'bg-indigo-50');
            slot.classList.add('border-gray-300');
            dot.classList.add('hidden');
        }
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key >= '0' && e.key <= '9') addDigit(e.key);
    if (e.key === 'Backspace') clearPin();
});
</script>
@endsection
