@extends('layouts.hijo')
@section('title', 'Ordena los Números')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('hijo.juegos') }}" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">←</a>
    <div>
        <h2 class="font-extrabold text-white text-lg leading-tight">🔢 Ordena los Números</h2>
        <p class="text-white/70 text-xs">Toca del 1 al 10 en orden · gana <x-moneda />{{ $monedas }} monedas</p>
    </div>
</div>

<div id="juegoArea">
    <div class="bg-white/95 rounded-2xl shadow-xl p-5 mb-4">
        <!-- Progreso y timer -->
        <div class="flex items-center justify-between mb-4">
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2">
                <p class="text-xs font-bold text-indigo-500 uppercase">Siguiente</p>
                <p class="text-3xl font-black text-indigo-700 leading-none" id="siguiente">1</p>
            </div>
            <div class="text-center">
                <p class="text-xs font-bold text-slate-500 uppercase">Tiempo</p>
                <p class="text-3xl font-black text-slate-700" id="timer">60</p>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-xl px-3 py-2 text-right">
                <p class="text-xs font-bold text-amber-500 uppercase">Ronda</p>
                <p class="text-xl font-black text-amber-700" id="ronda">1 / 3</p>
            </div>
        </div>

        <!-- Grid de números -->
        <div id="numeros" class="grid grid-cols-5 gap-3"></div>

        <!-- Mensaje de feedback -->
        <p id="feedback" class="text-center text-sm font-bold mt-3 h-5"></p>
    </div>
</div>

<!-- Resultado -->
<div id="resultadoArea" class="hidden bg-white/95 rounded-2xl shadow-xl p-8 text-center">
    <div id="winResult" class="hidden">
        <div class="text-6xl mb-3">🏆</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Perfecto!</h3>
        <p class="text-slate-500 mb-2">Completaste las 3 rondas</p>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 inline-flex items-center gap-2 mb-4">
            <span class="text-2xl"><x-moneda /></span>
            <span class="font-extrabold text-amber-700 text-xl">+{{ $monedas }} monedas</span>
        </div>
        <form id="winForm" action="{{ route('hijo.juegos.completar', 'ordenar') }}" method="POST">@csrf</form>
        <p class="text-slate-400 text-sm">Enviando resultados...</p>
    </div>
    <div id="loseResult" class="hidden">
        <div class="text-6xl mb-3">⏰</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Se acabó el tiempo!</h3>
        <p class="text-slate-500 mb-4">Ibas por el <span id="numeroAlTiempo" class="font-bold text-indigo-600"></span>. ¡Inténtalo más rápido!</p>
        <div class="flex gap-3 justify-center">
            <button onclick="iniciarJuego()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition">🔄 Intentar de nuevo</button>
            <a href="{{ route('hijo.juegos') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition">← Volver</a>
        </div>
    </div>
</div>

<style>
.num-btn {
    aspect-ratio: 1;
    border-radius: 16px;
    font-size: 24px;
    font-weight: 900;
    border: 3px solid #e2e8f0;
    background: white;
    color: #1e293b;
    cursor: pointer;
    transition: transform 0.15s, border-color 0.15s, background 0.15s, opacity 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.num-btn:hover:not(:disabled) { transform: scale(1.1); border-color: #6366f1; box-shadow: 0 4px 16px rgba(99,102,241,0.3); }
.num-btn:active:not(:disabled) { transform: scale(0.92); }
.num-btn.correcto { background: #d1fae5; border-color: #10b981; color: #059669; opacity: 0.5; cursor: default; }
@keyframes sacudir {
    0%,100% { transform: translateX(0); }
    20%      { transform: translateX(-8px); }
    40%      { transform: translateX(8px); }
    60%      { transform: translateX(-5px); }
    80%      { transform: translateX(5px); }
}
.sacudir { animation: sacudir 0.4s ease-in-out; }
</style>

<script>
let siguiente, rondaActual, segundos, intervalo, numerosArr;

function shuffle(a) { return a.sort(() => Math.random() - 0.5); }

function iniciarRonda() {
    siguiente = 1;
    numerosArr = shuffle([1,2,3,4,5,6,7,8,9,10]);
    document.getElementById('siguiente').textContent = '1';
    document.getElementById('feedback').textContent = '';
    renderNumeros();
}

function renderNumeros() {
    document.getElementById('numeros').innerHTML = numerosArr.map(n =>
        `<button class="num-btn${n < siguiente ? ' correcto' : ''}"
                 id="n${n}"
                 ${n < siguiente ? 'disabled' : ''}
                 onclick="tocar(${n})">${n}</button>`
    ).join('');
}

function tocar(n) {
    if (n === siguiente) {
        document.getElementById(`n${n}`).classList.add('correcto');
        document.getElementById(`n${n}`).disabled = true;
        const fb = document.getElementById('feedback');
        fb.textContent = '✅ ¡Correcto!';
        fb.style.color = '#059669';
        siguiente++;
        document.getElementById('siguiente').textContent = siguiente <= 10 ? siguiente : '—';
        if (siguiente > 10) {
            clearInterval(intervalo);
            const r = rondaActual;
            rondaActual++;
            if (rondaActual > 3) {
                setTimeout(ganar, 500);
            } else {
                document.getElementById('ronda').textContent = `${rondaActual} / 3`;
                fb.textContent = '🎉 ¡Ronda superada! Siguiente en 2s...';
                fb.style.color = '#6366f1';
                setTimeout(() => {
                    iniciarRonda();
                    iniciarTimer();
                }, 2000);
            }
        }
    } else {
        const btn = document.getElementById(`n${n}`);
        btn.classList.add('sacudir');
        setTimeout(() => btn.classList.remove('sacudir'), 400);
        const fb = document.getElementById('feedback');
        fb.textContent = `❌ ¡El siguiente es el ${siguiente}!`;
        fb.style.color = '#dc2626';
    }
}

function iniciarTimer() {
    clearInterval(intervalo);
    segundos = 60;
    document.getElementById('timer').textContent = segundos;
    intervalo = setInterval(() => {
        segundos--;
        document.getElementById('timer').textContent = segundos;
        document.getElementById('timer').style.color = segundos <= 10 ? '#ef4444' : '#374151';
        if (segundos <= 0) {
            clearInterval(intervalo);
            perder();
        }
    }, 1000);
}

function ganar() {
    clearInterval(intervalo);
    document.getElementById('juegoArea').classList.add('hidden');
    document.getElementById('resultadoArea').classList.remove('hidden');
    document.getElementById('winResult').classList.remove('hidden');
    setTimeout(() => document.getElementById('winForm').submit(), 2000);
}

function perder() {
    document.getElementById('numeroAlTiempo').textContent = siguiente;
    document.getElementById('juegoArea').classList.add('hidden');
    document.getElementById('resultadoArea').classList.remove('hidden');
    document.getElementById('loseResult').classList.remove('hidden');
}

function iniciarJuego() {
    rondaActual = 1;
    document.getElementById('ronda').textContent = '1 / 3';
    document.getElementById('juegoArea').classList.remove('hidden');
    document.getElementById('resultadoArea').classList.add('hidden');
    document.getElementById('winResult').classList.add('hidden');
    document.getElementById('loseResult').classList.add('hidden');
    iniciarRonda();
    iniciarTimer();
}

iniciarJuego();
</script>
@endsection
