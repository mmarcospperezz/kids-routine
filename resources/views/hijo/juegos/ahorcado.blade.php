@extends('layouts.hijo')
@section('title', 'Adivina la Palabra')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('hijo.juegos') }}" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">←</a>
    <div>
        <h2 class="font-extrabold text-white text-lg leading-tight">📝 Adivina la Palabra</h2>
        <p class="text-white/70 text-xs">6 errores permitidos · gana 🪙{{ $monedas }} monedas</p>
    </div>
</div>

<div id="juegoArea">
    <div class="bg-white/95 rounded-2xl shadow-xl p-5 mb-4">
        <!-- Muñeco ahorcado -->
        <div class="flex items-center justify-between mb-4">
            <div class="text-center">
                <div id="muneco" class="text-5xl leading-none select-none">😊</div>
                <p class="text-xs text-slate-400 mt-1">Vidas: <span id="vidas" class="font-bold text-indigo-600">6</span></p>
            </div>
            <div class="flex-1 ml-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pista</p>
                <p id="pista" class="text-slate-700 font-medium text-sm bg-indigo-50 rounded-xl px-3 py-2"></p>
            </div>
        </div>

        <!-- Letras usadas incorrectas -->
        <div id="incorrectas" class="flex flex-wrap gap-1 min-h-6 mb-4"></div>

        <!-- Palabra -->
        <div id="palabra" class="flex justify-center gap-2 flex-wrap mb-2"></div>
    </div>

    <!-- Teclado -->
    <div class="bg-white/95 rounded-2xl shadow-xl p-4">
        <div id="teclado" class="grid grid-cols-7 gap-1.5"></div>
    </div>
</div>

<!-- Resultado -->
<div id="resultadoArea" class="hidden bg-white/95 rounded-2xl shadow-xl p-8 text-center">
    <div id="winResult" class="hidden">
        <div class="text-6xl mb-3">🎉</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Lo adivinaste!</h3>
        <p class="text-slate-500 mb-2">La palabra era: <span id="palabraFinalW" class="font-extrabold text-indigo-600"></span></p>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 inline-flex items-center gap-2 mb-4">
            <span class="text-2xl">🪙</span>
            <span class="font-extrabold text-amber-700 text-xl">+{{ $monedas }} monedas</span>
        </div>
        <form id="winForm" action="{{ route('hijo.juegos.completar', 'ahorcado') }}" method="POST">@csrf</form>
        <p class="text-slate-400 text-sm">Enviando resultados...</p>
    </div>
    <div id="loseResult" class="hidden">
        <div class="text-6xl mb-3">😢</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Ups! Se acabaron las vidas</h3>
        <p class="text-slate-500 mb-4">La palabra era: <span id="palabraFinalL" class="font-extrabold text-red-500"></span></p>
        <div class="flex gap-3 justify-center">
            <button onclick="iniciar()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition">🔄 Otra palabra</button>
            <a href="{{ route('hijo.juegos') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition">← Volver</a>
        </div>
    </div>
</div>

<style>
.letra-btn {
    height: 38px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 800;
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    color: #1e293b;
    cursor: pointer;
    transition: transform 0.1s, background 0.15s;
}
.letra-btn:hover:not(:disabled) { background: #eef2ff; border-color: #6366f1; transform: scale(1.1); }
.letra-btn:active:not(:disabled) { transform: scale(0.9); }
.letra-btn.usada-bien { background: #d1fae5; border-color: #10b981; color: #065f46; }
.letra-btn.usada-mal  { background: #fee2e2; border-color: #ef4444; color: #991b1b; opacity: 0.7; }
.letra-btn:disabled { cursor: default; }
.hueco {
    width: 36px;
    height: 44px;
    border-bottom: 3px solid #6366f1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 900;
    color: #1e293b;
    transition: all 0.2s;
}
.hueco.adivinado { border-color: #10b981; color: #059669; }
</style>

<script>
const PALABRAS = [
    {p:'ELEFANTE',   h:'Animal más grande de tierra 🐘'},
    {p:'MARIPOSA',   h:'Insecto con alas de colores 🦋'},
    {p:'DINOSAURIO', h:'Animal prehistórico extinto 🦕'},
    {p:'COHETE',     h:'Viaja al espacio 🚀'},
    {p:'ARCOIRIS',   h:'Aparece después de la lluvia 🌈'},
    {p:'SUBMARINO',  h:'Navega bajo el agua 🌊'},
    {p:'TELESCOPIO', h:'Para ver las estrellas 🔭'},
    {p:'VOLCAN',     h:'Montaña que erupciona 🌋'},
    {p:'PIRATA',     h:'Navega buscando tesoros 🏴‍☠️'},
    {p:'CASTILLO',   h:'Hogar de reyes y princesas 🏰'},
    {p:'JIRAFA',     h:'Animal con cuello muy largo 🦒'},
    {p:'PINGÜINO',   h:'Pájaro que vive en el Ártico 🐧'},
];

const MUNECOS = ['😊','😐','😟','😨','😰','😱','💀'];
let palabraActual, adivinadas, errores, letrasUsadas;

function iniciar() {
    const r = PALABRAS[Math.floor(Math.random() * PALABRAS.length)];
    palabraActual = r.p;
    document.getElementById('pista').textContent = r.h;
    adivinadas = new Set();
    errores = 0;
    letrasUsadas = new Set();

    document.getElementById('juegoArea').classList.remove('hidden');
    document.getElementById('resultadoArea').classList.add('hidden');
    document.getElementById('winResult').classList.add('hidden');
    document.getElementById('loseResult').classList.add('hidden');

    renderTeclado();
    renderPalabra();
    renderMuneco();
}

function renderTeclado() {
    const letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    document.getElementById('teclado').innerHTML = [...letras].map(l => {
        const usadaBien = adivinadas.has(l);
        const usadaMal  = letrasUsadas.has(l) && !adivinadas.has(l);
        return `<button class="letra-btn${usadaBien?' usada-bien':usadaMal?' usada-mal':''}"
                        ${letrasUsadas.has(l) ? 'disabled' : ''}
                        onclick="adivinar('${l}')">${l}</button>`;
    }).join('');
}

function renderPalabra() {
    document.getElementById('palabra').innerHTML = palabraActual.split('').map(l =>
        `<div class="hueco${adivinadas.has(l) ? ' adivinado' : ''}">${adivinadas.has(l) ? l : ''}</div>`
    ).join('');
}

function renderMuneco() {
    document.getElementById('muneco').textContent = MUNECOS[Math.min(errores, 6)];
    document.getElementById('vidas').textContent = 6 - errores;
    const inc = [...letrasUsadas].filter(l => !adivinadas.has(l));
    document.getElementById('incorrectas').innerHTML = inc.map(l =>
        `<span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-lg">${l}</span>`
    ).join('');
}

function adivinar(letra) {
    if (letrasUsadas.has(letra)) return;
    letrasUsadas.add(letra);
    if (palabraActual.includes(letra)) {
        adivinadas.add(letra);
        renderTeclado();
        renderPalabra();
        if (palabraActual.split('').every(l => adivinadas.has(l))) ganar();
    } else {
        errores++;
        renderTeclado();
        renderMuneco();
        if (errores >= 6) perder();
    }
}

function ganar() {
    document.getElementById('palabraFinalW').textContent = palabraActual;
    document.getElementById('juegoArea').classList.add('hidden');
    document.getElementById('resultadoArea').classList.remove('hidden');
    document.getElementById('winResult').classList.remove('hidden');
    setTimeout(() => document.getElementById('winForm').submit(), 2000);
}

function perder() {
    document.getElementById('palabraFinalL').textContent = palabraActual;
    document.getElementById('juegoArea').classList.add('hidden');
    document.getElementById('resultadoArea').classList.remove('hidden');
    document.getElementById('loseResult').classList.remove('hidden');
}

iniciar();
</script>
@endsection
