@extends('layouts.hijo')
@section('title', 'Operaciones Matemáticas')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('hijo.juegos') }}" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">←</a>
    <div>
        <h2 class="font-extrabold text-white text-lg leading-tight">➕ Operaciones Matemáticas</h2>
        <p class="text-white/70 text-xs">Acierta 7 de 10 para ganar 🪙{{ $monedas }} monedas</p>
    </div>
</div>

<!-- Juego -->
<div id="juego" class="bg-white/95 rounded-2xl shadow-xl p-6">
    <!-- Progreso -->
    <div class="flex items-center justify-between mb-4">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pregunta</span>
        <span id="progreso" class="text-sm font-extrabold text-indigo-600">1 / 10</span>
    </div>
    <div class="h-2 bg-slate-100 rounded-full mb-6 overflow-hidden">
        <div id="barraProgreso" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-500" style="width:10%"></div>
    </div>

    <!-- Enunciado -->
    <div class="text-center mb-8">
        <p class="text-5xl font-black text-gray-800" id="enunciado">3 + 5 = ?</p>
    </div>

    <!-- Opciones -->
    <div id="opciones" class="grid grid-cols-2 gap-3"></div>
</div>

<!-- Resultado (oculto) -->
<div id="resultado" class="hidden bg-white/95 rounded-2xl shadow-xl p-8 text-center">
    <div id="resultado-win" class="hidden">
        <div class="text-6xl mb-3">🏆</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Excelente!</h3>
        <p class="text-slate-500 mb-2">Respondiste correctamente <span id="puntaje" class="text-indigo-600 font-extrabold"></span> preguntas</p>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 inline-flex items-center gap-2 mb-6">
            <span class="text-2xl">🪙</span>
            <span class="font-extrabold text-amber-700 text-xl">+{{ $monedas }} monedas</span>
        </div>
        <form id="winForm" action="{{ route('hijo.juegos.completar', 'sumas') }}" method="POST">
            @csrf
        </form>
        <p class="text-slate-400 text-sm">Enviando resultados...</p>
    </div>
    <div id="resultado-lose" class="hidden">
        <div class="text-6xl mb-3">😅</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Casi!</h3>
        <p class="text-slate-500 mb-2">Respondiste correctamente <span id="puntajePerdiste" class="text-indigo-600 font-extrabold"></span> de 10 preguntas</p>
        <p class="text-slate-400 text-sm mb-6">Necesitas al menos 7 aciertos para ganar monedas</p>
        <div class="flex gap-3 justify-center">
            <button onclick="reiniciar()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-sm">
                🔄 Intentar de nuevo
            </button>
            <a href="{{ route('hijo.juegos') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition">
                ← Volver
            </a>
        </div>
    </div>
</div>

<style>
.opcion-btn {
    padding: 20px;
    border-radius: 16px;
    font-size: 22px;
    font-weight: 900;
    border: 2.5px solid #e2e8f0;
    background: #f8fafc;
    color: #1e293b;
    cursor: pointer;
    transition: transform 0.12s, border-color 0.15s, background 0.15s;
}
.opcion-btn:hover { transform: scale(1.04); border-color: #6366f1; background: #eef2ff; }
.opcion-btn:active { transform: scale(0.96); }
.opcion-btn.correcta { background: #d1fae5; border-color: #10b981; color: #065f46; }
.opcion-btn.incorrecta { background: #fee2e2; border-color: #ef4444; color: #991b1b; }
.opcion-btn:disabled { cursor: default; transform: none; }
</style>

<script>
const MINIMO = 7;
let preguntas = [], actual = 0, correctas = 0;

function shuffle(arr) { return arr.sort(() => Math.random() - 0.5); }

function generarPreguntas() {
    preguntas = [];
    for (let i = 0; i < 10; i++) {
        const a = Math.floor(Math.random() * 20) + 1;
        const b = Math.floor(Math.random() * 20) + 1;
        const esSum = Math.random() > 0.4;
        let correcta, enunciado;
        if (esSum) {
            correcta = a + b;
            enunciado = `${a} + ${b}`;
        } else {
            const [max, min] = a >= b ? [a, b] : [b, a];
            correcta = max - min;
            enunciado = `${max} − ${min}`;
        }
        const opts = new Set([correcta]);
        while (opts.size < 4) {
            const d = correcta + Math.floor(Math.random() * 10) - 4;
            if (d >= 0 && d !== correcta) opts.add(d);
        }
        preguntas.push({ enunciado, correcta, opts: shuffle([...opts]) });
    }
}

function mostrarPregunta() {
    const p = preguntas[actual];
    document.getElementById('progreso').textContent = `${actual + 1} / 10`;
    document.getElementById('barraProgreso').style.width = `${((actual + 1) / 10) * 100}%`;
    document.getElementById('enunciado').textContent = `${p.enunciado} = ?`;
    document.getElementById('opciones').innerHTML = p.opts.map((op, i) =>
        `<button class="opcion-btn" onclick="responder(${i})">${op}</button>`
    ).join('');
}

function responder(idx) {
    const p = preguntas[actual];
    const esCorrecta = p.opts[idx] === p.correcta;
    if (esCorrecta) correctas++;
    const btns = document.querySelectorAll('.opcion-btn');
    btns.forEach((btn, i) => {
        btn.disabled = true;
        if (p.opts[i] === p.correcta) btn.classList.add('correcta');
        else if (i === idx) btn.classList.add('incorrecta');
    });
    setTimeout(() => {
        actual++;
        if (actual < 10) mostrarPregunta();
        else mostrarResultado();
    }, 900);
}

function mostrarResultado() {
    document.getElementById('juego').classList.add('hidden');
    document.getElementById('resultado').classList.remove('hidden');
    if (correctas >= MINIMO) {
        document.getElementById('puntaje').textContent = `${correctas}/10`;
        document.getElementById('resultado-win').classList.remove('hidden');
        setTimeout(() => document.getElementById('winForm').submit(), 2000);
    } else {
        document.getElementById('puntajePerdiste').textContent = `${correctas}`;
        document.getElementById('resultado-lose').classList.remove('hidden');
    }
}

function reiniciar() {
    actual = 0; correctas = 0;
    document.getElementById('juego').classList.remove('hidden');
    document.getElementById('resultado').classList.add('hidden');
    document.getElementById('resultado-win').classList.add('hidden');
    document.getElementById('resultado-lose').classList.add('hidden');
    generarPreguntas();
    mostrarPregunta();
}

generarPreguntas();
mostrarPregunta();
</script>
@endsection
