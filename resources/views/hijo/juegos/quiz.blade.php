@extends('layouts.hijo')
@section('title', 'Quiz de Conocimiento')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('hijo.juegos') }}" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">←</a>
    <div>
        <h2 class="font-extrabold text-white text-lg leading-tight">🧠 Quiz de Conocimiento</h2>
        <p class="text-white/70 text-xs">Acierta 6 de 8 preguntas · gana <x-moneda />{{ $monedas }} monedas</p>
    </div>
</div>

<div id="juego" class="bg-white/95 rounded-2xl shadow-xl p-6">
    <!-- Progreso -->
    <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pregunta</span>
        <span id="progreso" class="text-sm font-extrabold text-indigo-600">1 / 8</span>
    </div>
    <div class="h-2 bg-slate-100 rounded-full mb-6 overflow-hidden">
        <div id="barraProgreso" class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width:12.5%"></div>
    </div>

    <!-- Categoría -->
    <p id="categoria" class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2"></p>

    <!-- Pregunta -->
    <p id="pregunta" class="text-xl font-extrabold text-gray-800 mb-6 leading-snug"></p>

    <!-- Opciones -->
    <div id="opciones" class="space-y-2"></div>
</div>

<!-- Resultado (oculto) -->
<div id="resultado" class="hidden bg-white/95 rounded-2xl shadow-xl p-8 text-center">
    <div id="winResult" class="hidden">
        <div class="text-6xl mb-3">🧠</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Eres muy listo!</h3>
        <p class="text-slate-500 mb-2">Acertaste <span id="puntajeW" class="text-emerald-600 font-extrabold"></span> de 8 preguntas</p>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 inline-flex items-center gap-2 mb-4">
            <span class="text-2xl"><x-moneda /></span>
            <span class="font-extrabold text-amber-700 text-xl">+{{ $monedas }} monedas</span>
        </div>
        <form id="winForm" action="{{ route('hijo.juegos.completar', 'quiz') }}" method="POST">@csrf</form>
        <p class="text-slate-400 text-sm">Enviando resultados...</p>
    </div>
    <div id="loseResult" class="hidden">
        <div class="text-6xl mb-3">📚</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Sigue estudiando!</h3>
        <p class="text-slate-500 mb-4">Acertaste <span id="puntajeL" class="text-indigo-600 font-extrabold"></span> de 8 · necesitas 6 para ganar</p>
        <div class="flex gap-3 justify-center">
            <button onclick="reiniciar()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl transition">🔄 Intentar de nuevo</button>
            <a href="{{ route('hijo.juegos') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition">← Volver</a>
        </div>
    </div>
</div>

<style>
.quiz-btn {
    width: 100%;
    text-align: left;
    padding: 14px 18px;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 700;
    border: 2.5px solid #e2e8f0;
    background: #f8fafc;
    color: #1e293b;
    cursor: pointer;
    transition: transform 0.12s, border-color 0.15s, background 0.15s;
    display: flex;
    align-items: center;
    gap: 10px;
}
.quiz-btn:hover:not(:disabled) { border-color: #10b981; background: #f0fdf4; transform: translateX(3px); }
.quiz-btn:active:not(:disabled) { transform: scale(0.98); }
.quiz-btn.correcta { background: #d1fae5; border-color: #10b981; color: #065f46; }
.quiz-btn.incorrecta { background: #fee2e2; border-color: #ef4444; color: #991b1b; }
.quiz-btn:disabled { cursor: default; transform: none; }
.quiz-letra {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 900;
    flex-shrink: 0;
}
</style>

<script>
const BANCO = [
    {c:'Animales',   q:'¿Cuántas patas tiene una araña?',              opts:['4','6','8','10'],            a:2},
    {c:'Espacio',    q:'¿Cuál es el planeta más grande del sistema solar?', opts:['Saturno','Júpiter','Neptuno','Urano'], a:1},
    {c:'Naturaleza', q:'¿Cuántos colores tiene el arcoíris?',           opts:['5','6','7','8'],             a:2},
    {c:'Animales',   q:'¿Cuál es el animal terrestre más rápido?',      opts:['León','Guepardo','Tigre','Caballo'], a:1},
    {c:'Geografía',  q:'¿En qué continente está España?',               opts:['América','Asia','Europa','África'], a:2},
    {c:'Ciencia',    q:'¿Cuántos planetas tiene nuestro sistema solar?',opts:['7','8','9','10'],            a:1},
    {c:'Naturaleza', q:'¿Cuál es el océano más grande del mundo?',      opts:['Atlántico','Índico','Ártico','Pacífico'], a:3},
    {c:'Animales',   q:'¿Qué animal es el más grande del mundo?',       opts:['Elefante','Ballena azul','Tiburón ballena','Jirafa'], a:1},
    {c:'Ciencia',    q:'¿De qué está hecha el agua?',                   opts:['Hidrógeno y oxígeno','Solo oxígeno','Hidrógeno y nitrógeno','Carbono y oxígeno'], a:0},
    {c:'Geografía',  q:'¿Cuál es la capital de Francia?',               opts:['Londres','Roma','París','Berlín'], a:2},
    {c:'Animales',   q:'¿Cuántas patas tiene una vaca?',                opts:['2','4','6','8'],             a:1},
    {c:'Espacio',    q:'¿Qué astro da luz y calor a la Tierra?',        opts:['La Luna','Marte','El Sol','Venus'], a:2},
];

function shuffle(a) { return a.sort(() => Math.random() - 0.5); }

const LETRAS = ['A','B','C','D'];
let preguntas, actual, correctas;

function generarPreguntas() {
    preguntas = shuffle([...BANCO]).slice(0, 8);
    actual = 0;
    correctas = 0;
}

function mostrarPregunta() {
    const p = preguntas[actual];
    document.getElementById('progreso').textContent = `${actual + 1} / 8`;
    document.getElementById('barraProgreso').style.width = `${((actual + 1) / 8) * 100}%`;
    document.getElementById('categoria').textContent = `📚 ${p.c}`;
    document.getElementById('pregunta').textContent = p.q;
    document.getElementById('opciones').innerHTML = p.opts.map((op, i) =>
        `<button class="quiz-btn" onclick="responder(${i})">
            <span class="quiz-letra">${LETRAS[i]}</span>
            ${op}
        </button>`
    ).join('');
}

function responder(idx) {
    const p = preguntas[actual];
    if (idx === p.a) correctas++;
    const btns = document.querySelectorAll('.quiz-btn');
    btns.forEach((btn, i) => {
        btn.disabled = true;
        if (i === p.a) btn.classList.add('correcta');
        else if (i === idx) btn.classList.add('incorrecta');
    });
    setTimeout(() => {
        actual++;
        if (actual < 8) mostrarPregunta();
        else mostrarResultado();
    }, 1000);
}

function mostrarResultado() {
    document.getElementById('juego').classList.add('hidden');
    document.getElementById('resultado').classList.remove('hidden');
    if (correctas >= 6) {
        document.getElementById('puntajeW').textContent = `${correctas}/8`;
        document.getElementById('winResult').classList.remove('hidden');
        setTimeout(() => document.getElementById('winForm').submit(), 2000);
    } else {
        document.getElementById('puntajeL').textContent = `${correctas}`;
        document.getElementById('loseResult').classList.remove('hidden');
    }
}

function reiniciar() {
    document.getElementById('juego').classList.remove('hidden');
    document.getElementById('resultado').classList.add('hidden');
    document.getElementById('winResult').classList.add('hidden');
    document.getElementById('loseResult').classList.add('hidden');
    generarPreguntas();
    mostrarPregunta();
}

generarPreguntas();
mostrarPregunta();
</script>
@endsection
