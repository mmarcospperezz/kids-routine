@extends('layouts.hijo')
@section('title', 'Juego de Memoria')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('hijo.juegos') }}" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">←</a>
    <div>
        <h2 class="font-extrabold text-white text-lg leading-tight">🃏 Juego de Memoria</h2>
        <p class="text-white/70 text-xs">Encuentra las 8 parejas para ganar <x-moneda />{{ $monedas }} monedas</p>
    </div>
</div>

<div class="bg-white/95 rounded-2xl shadow-xl p-5">
    <!-- Info -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-1.5">
            <span>🃏</span>
            <span class="text-sm font-extrabold text-amber-700">Parejas: <span id="parejas">0</span>/8</span>
        </div>
        <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-1.5">
            <span>🔄</span>
            <span class="text-sm font-extrabold text-indigo-700">Intentos: <span id="intentos">0</span></span>
        </div>
    </div>

    <!-- Grid de cartas -->
    <div id="tablero" class="grid grid-cols-4 gap-2"></div>
</div>

<!-- Victoria (oculta) -->
<div id="victoria" class="hidden bg-white/95 rounded-2xl shadow-xl p-8 text-center mt-4">
    <div class="text-6xl mb-3">🎉</div>
    <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Increíble!</h3>
    <p class="text-slate-500 mb-2">Completaste el juego en <span id="intentosFinal" class="font-extrabold text-indigo-600"></span> intentos</p>
    <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 inline-flex items-center gap-2 mb-4">
        <span class="text-2xl"><x-moneda /></span>
        <span class="font-extrabold text-amber-700 text-xl">+{{ $monedas }} monedas</span>
    </div>
    <form id="winForm" action="{{ route('hijo.juegos.completar', 'memoria') }}" method="POST">
        @csrf
    </form>
    <p class="text-slate-400 text-sm">Enviando resultados...</p>
</div>

<style>
.carta {
    aspect-ratio: 1;
    perspective: 600px;
    cursor: pointer;
}
.carta-inner {
    width: 100%;
    height: 100%;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.4s ease;
    border-radius: 12px;
}
.carta.volteada .carta-inner,
.carta.emparejada .carta-inner { transform: rotateY(180deg); }
.carta-front, .carta-back {
    position: absolute;
    inset: 0;
    border-radius: 12px;
    backface-visibility: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: clamp(18px, 5vw, 30px);
}
.carta-front {
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    border: 2px solid rgba(255,255,255,0.3);
}
.carta-back {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    transform: rotateY(180deg);
}
.carta.emparejada .carta-back {
    background: #d1fae5;
    border-color: #10b981;
}
@keyframes empareja {
    0%,100% { transform: rotateY(180deg) scale(1); }
    50%      { transform: rotateY(180deg) scale(1.15); }
}
.carta.emparejada .carta-inner { animation: empareja 0.4s ease-out both; }
</style>

<script>
const SIMBOLOS = ['🐶','🐱','🐸','🦋','⭐','🍎','🚀','🎈'];
const cartas = shuffle([...SIMBOLOS, ...SIMBOLOS]).map((s,i) => ({ id:i, simbolo:s, volteada:false, emparejada:false }));
let seleccionadas = [], parejas = 0, intentos = 0, bloqueado = false;

function shuffle(a) { return a.sort(()=>Math.random()-0.5); }

function renderizar() {
    const t = document.getElementById('tablero');
    t.innerHTML = cartas.map(c => `
        <div class="carta${c.volteada?' volteada':''}${c.emparejada?' emparejada':''}"
             id="c${c.id}" onclick="voltear(${c.id})">
            <div class="carta-inner">
                <div class="carta-front">✨</div>
                <div class="carta-back">${c.simbolo}</div>
            </div>
        </div>
    `).join('');
}

function voltear(id) {
    if (bloqueado) return;
    const carta = cartas[id];
    if (carta.volteada || carta.emparejada) return;
    carta.volteada = true;
    seleccionadas.push(id);
    renderizar();
    if (seleccionadas.length === 2) {
        intentos++;
        document.getElementById('intentos').textContent = intentos;
        bloqueado = true;
        const [a, b] = seleccionadas;
        if (cartas[a].simbolo === cartas[b].simbolo) {
            cartas[a].emparejada = cartas[b].emparejada = true;
            cartas[a].volteada  = cartas[b].volteada  = false;
            parejas++;
            document.getElementById('parejas').textContent = parejas;
            seleccionadas = [];
            bloqueado = false;
            renderizar();
            if (parejas === 8) ganar();
        } else {
            setTimeout(() => {
                cartas[a].volteada = cartas[b].volteada = false;
                seleccionadas = [];
                bloqueado = false;
                renderizar();
            }, 1000);
        }
    }
}

function ganar() {
    document.getElementById('intentosFinal').textContent = intentos;
    setTimeout(() => {
        document.getElementById('victoria').classList.remove('hidden');
        setTimeout(() => document.getElementById('winForm').submit(), 2000);
    }, 500);
}

renderizar();
</script>
@endsection
