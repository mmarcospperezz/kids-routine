@extends('layouts.hijo')
@section('title', 'Adivina la Palabra')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('hijo.juegos') }}" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">←</a>
    <div>
        <h2 class="font-extrabold text-white text-lg leading-tight">📝 Adivina la Palabra</h2>
        <p class="text-white/70 text-xs">6 errores permitidos · gana <x-moneda />{{ $monedas }} monedas</p>
    </div>
</div>

<div id="juegoArea">
    <div class="bg-white/95 rounded-2xl shadow-xl p-5 mb-4">
        <!-- Muñeco + pista -->
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

        <!-- Palabra (huecos) -->
        <div id="palabra" class="flex justify-center gap-2 flex-wrap py-4"></div>
    </div>

    <!-- Input teclado libre -->
    <div class="bg-white/95 rounded-2xl shadow-xl p-5">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
            Escribe una letra <span class="normal-case font-normal text-slate-400">(admite tildes, ñ, ü…)</span>
        </p>
        <div class="flex gap-3">
            <input type="text"
                   id="letraInput"
                   maxlength="2"
                   autofocus
                   autocomplete="off" autocorrect="off" autocapitalize="characters" spellcheck="false"
                   class="w-20 h-16 text-center text-3xl font-black border-2 border-slate-200 rounded-2xl uppercase bg-slate-50 focus:border-indigo-500 focus:bg-white outline-none transition"
                   placeholder="?">
            <button onclick="procesarInput()" id="enviarBtn"
                    class="flex-1 h-16 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-extrabold text-xl rounded-2xl transition shadow-md">
                Enviar ✓
            </button>
        </div>

        <!-- Letras usadas -->
        <div class="mt-4 space-y-2">
            <div id="letrasCorrectas" class="flex flex-wrap gap-1.5 min-h-7"></div>
            <div id="letrasIncorrectas" class="flex flex-wrap gap-1.5 min-h-7"></div>
        </div>

        <p id="feedbackLetra" class="text-sm font-bold text-center mt-3 h-5"></p>
    </div>
</div>

<!-- Resultado -->
<div id="resultadoArea" class="hidden bg-white/95 rounded-2xl shadow-xl p-8 text-center">
    <div id="winResult" class="hidden">
        <div class="text-6xl mb-3">🎉</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Lo adivinaste!</h3>
        <p class="text-slate-500 mb-2">La palabra era: <span id="palabraFinalW" class="font-extrabold text-indigo-600"></span></p>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3 inline-flex items-center gap-2 mb-4">
            <span class="text-2xl"><x-moneda /></span>
            <span class="font-extrabold text-amber-700 text-xl">+{{ $monedas }} monedas</span>
        </div>
        <form id="winForm" action="{{ route('hijo.juegos.completar', 'ahorcado') }}" method="POST">@csrf</form>
        <p class="text-slate-400 text-sm">Enviando resultados...</p>
    </div>
    <div id="loseResult" class="hidden">
        <div class="text-6xl mb-3">😢</div>
        <h3 class="text-2xl font-extrabold text-gray-800 mb-1">¡Ups! Sin vidas</h3>
        <p class="text-slate-500 mb-4">La palabra era: <span id="palabraFinalL" class="font-extrabold text-red-500"></span></p>
        <div class="flex gap-3 justify-center">
            <button onclick="iniciar()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition">🔄 Otra palabra</button>
            <a href="{{ route('hijo.juegos') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition">← Volver</a>
        </div>
    </div>
</div>

<style>
.hueco {
    min-width: 30px;
    height: 44px;
    border-bottom: 3px solid #6366f1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 900;
    color: #1e293b;
    padding: 0 4px;
}
.hueco.adivinado { border-color: #10b981; color: #059669; }
@keyframes aparecer {
    0%   { transform: scale(0) rotate(-10deg); opacity: 0; }
    70%  { transform: scale(1.2) rotate(3deg); }
    100% { transform: scale(1) rotate(0deg); opacity: 1; }
}
.hueco.adivinado span { animation: aparecer 0.35s cubic-bezier(0.34,1.56,0.64,1) both; }
@keyframes sacudir {
    0%,100% { transform: translateX(0); }
    20%      { transform: translateX(-6px); }
    40%      { transform: translateX(6px); }
    60%      { transform: translateX(-4px); }
    80%      { transform: translateX(4px); }
}
.sacudir { animation: sacudir 0.4s ease; }
</style>

<script>
/* Lista de palabras con tildes y ñ/ü */
const PALABRAS = [
    {p:'ELEFANTE',    h:'Animal más grande de tierra 🐘'},
    {p:'MARIPOSA',    h:'Insecto con alas de colores 🦋'},
    {p:'DINOSAURIO',  h:'Animal prehistórico extinto 🦕'},
    {p:'COHETE',      h:'Viaja al espacio 🚀'},
    {p:'ARCOÍRIS',    h:'Aparece después de la lluvia 🌈'},
    {p:'SUBMARINO',   h:'Navega bajo el agua 🌊'},
    {p:'TELESCOPIO',  h:'Para ver las estrellas 🔭'},
    {p:'VOLCÁN',      h:'Montaña que erupciona 🌋'},
    {p:'CASTILLO',    h:'Hogar de reyes y princesas 🏰'},
    {p:'JIRAFA',      h:'Animal con cuello muy largo 🦒'},
    {p:'PINGÜINO',    h:'Pájaro que vive en el Ártico 🐧'},
    {p:'MURCIÉLAGO',  h:'Mamífero que vuela de noche 🦇'},
    {p:'HIPOPÓTAMO',  h:'Animal grande que vive en ríos 🦛'},
    {p:'MONTAÑA',     h:'Gran elevación de terreno ⛰️'},
    {p:'OCÉANO',      h:'Gran masa de agua salada 🌊'},
    {p:'CANCIÓN',     h:'Música con letra 🎵'},
    {p:'ESPAÑA',      h:'País de Europa 🇪🇸'},
    {p:'COCODRILO',   h:'Reptil de grandes mandíbulas 🐊'},
    {p:'PIRATA',      h:'Navega buscando tesoros 🏴‍☠️'},
    {p:'TIBURÓN',     h:'Pez grande y temido 🦈'},
];

/* Normalización: quita tildes de vocales básicas pero respeta Ñ y Ü */
function base(c) {
    return c.toUpperCase()
        .replace(/[ÁÀÂÄ]/g, 'A')
        .replace(/[ÉÈÊË]/g, 'E')
        .replace(/[ÍÌÎÏ]/g, 'I')
        .replace(/[ÓÒÔÖ]/g, 'O')
        .replace(/[ÚÙÛÜ]/g, 'U');  // Ü → U (simplificado)
    // Ñ queda como Ñ (no se convierte a N)
}

const MUNECOS = ['😊','😐','😟','😨','😰','😱','💀'];
let palabraActual, adivinadas, errores, letrasUsadas;

function iniciar() {
    const r = PALABRAS[Math.floor(Math.random() * PALABRAS.length)];
    palabraActual = r.p;
    document.getElementById('pista').textContent = r.h;
    adivinadas   = new Set();
    errores      = 0;
    letrasUsadas = new Set();

    document.getElementById('juegoArea').classList.remove('hidden');
    document.getElementById('resultadoArea').classList.add('hidden');
    document.getElementById('winResult').classList.add('hidden');
    document.getElementById('loseResult').classList.add('hidden');
    document.getElementById('letrasCorrectas').innerHTML   = '';
    document.getElementById('letrasIncorrectas').innerHTML = '';
    document.getElementById('feedbackLetra').textContent   = '';
    document.getElementById('letraInput').value = '';
    document.getElementById('letraInput').focus();
    renderPalabra();
    renderMuneco();
}

function renderPalabra() {
    document.getElementById('palabra').innerHTML = palabraActual.split('').map(c =>
        `<div class="hueco${adivinadas.has(c) ? ' adivinado' : ''}">
            ${adivinadas.has(c) ? `<span>${c}</span>` : ''}
        </div>`
    ).join('');
}

function renderMuneco() {
    document.getElementById('muneco').textContent = MUNECOS[Math.min(errores, 6)];
    document.getElementById('vidas').textContent  = 6 - errores;
}

function renderLetrasUsadas() {
    const corr = [...letrasUsadas].filter(l =>
        palabraActual.split('').some(c => base(c) === base(l))
    );
    const incorr = [...letrasUsadas].filter(l =>
        !palabraActual.split('').some(c => base(c) === base(l))
    );
    document.getElementById('letrasCorrectas').innerHTML = corr.map(l =>
        `<span class="bg-emerald-100 text-emerald-700 text-xs font-extrabold px-2 py-1 rounded-lg">${l}</span>`
    ).join('');
    document.getElementById('letrasIncorrectas').innerHTML = incorr.map(l =>
        `<span class="bg-red-100 text-red-500 text-xs font-extrabold px-2 py-1 rounded-lg line-through">${l}</span>`
    ).join('');
}

function adivinar(letra) {
    const letraUp = letra.toUpperCase();
    /* Solo acepta letras españolas válidas */
    if (!/^[A-ZÁÉÍÓÚÜÑÀÈÌ]$/.test(letraUp)) return;
    if (letrasUsadas.has(letraUp)) {
        mostrarFeedback('⚠️ Ya la usaste', '#f59e0b');
        return;
    }

    letrasUsadas.add(letraUp);
    const letraBase = base(letraUp);
    const acierta   = palabraActual.split('').some(c => base(c) === letraBase);

    if (acierta) {
        palabraActual.split('').forEach(c => {
            if (base(c) === letraBase) adivinadas.add(c);
        });
        renderPalabra();
        mostrarFeedback('✅ ¡Correcto!', '#10b981');
        if (palabraActual.split('').every(c => adivinadas.has(c))) ganar();
    } else {
        errores++;
        renderMuneco();
        /* Animar la zona de vidas */
        const m = document.getElementById('muneco');
        m.classList.add('sacudir');
        setTimeout(() => m.classList.remove('sacudir'), 400);
        mostrarFeedback(`❌ No está — ${6 - errores} vida${6-errores!==1?'s':''} restante${6-errores!==1?'s':''}`, '#ef4444');
        if (errores >= 6) perder();
    }
    renderLetrasUsadas();
}

function mostrarFeedback(texto, color) {
    const el = document.getElementById('feedbackLetra');
    el.textContent = texto;
    el.style.color = color;
    clearTimeout(el._t);
    el._t = setTimeout(() => el.textContent = '', 1800);
}

function procesarInput() {
    const input = document.getElementById('letraInput');
    const val   = input.value.trim();
    input.value = '';
    input.focus();
    if (!val) return;
    /* Tomar solo la primera letra válida */
    const letra = [...val].find(c => /[A-ZÁÉÍÓÚÜÑa-záéíóúüñàèì]/i.test(c));
    if (letra) adivinar(letra);
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

/* Enviar con Enter o al escribir una sola letra */
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('letraInput');
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); procesarInput(); }
    });
    input.addEventListener('input', () => {
        /* Auto-envío instantáneo si ya hay una letra */
        if (input.value.trim().length >= 1) {
            setTimeout(procesarInput, 80);
        }
    });
});

iniciar();
</script>
@endsection
