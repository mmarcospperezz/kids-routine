<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduce tu PIN — Kids Routine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            40%       { transform: translateY(-18px) rotate(7deg); }
            70%       { transform: translateY(-9px) rotate(-4deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes shakeIt {
            0%, 100% { transform: translateX(0); }
            15%       { transform: translateX(-10px); }
            30%       { transform: translateX(10px); }
            45%       { transform: translateX(-8px); }
            60%       { transform: translateX(8px); }
            75%       { transform: translateX(-4px); }
            90%       { transform: translateX(4px); }
        }
        @keyframes slotFill {
            0%   { transform: scale(0.5); opacity: 0; }
            60%  { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        .f1 { animation: float 7s ease-in-out infinite; }
        .f2 { animation: float 9s ease-in-out 1.5s infinite; }
        .f3 { animation: float 6s ease-in-out 3s infinite; }
        .f4 { animation: float 8s ease-in-out 0.5s infinite; }
        .fade-up  { animation: fadeInUp 0.6s ease-out both; }
        .fade-up2 { animation: fadeInUp 0.6s ease-out 0.15s both; }
        .shake { animation: shakeIt 0.5s ease-in-out; }
        .dot-enter { animation: slotFill 0.2s cubic-bezier(0.34,1.56,0.64,1) both; }
        .pin-slot {
            width: 64px;
            height: 64px;
            border: 2.5px solid rgba(255,255,255,0.4);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 900;
            color: white;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            transition: border-color 0.2s, background 0.2s, transform 0.15s;
        }
        .pin-slot.filled {
            border-color: rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.3);
            transform: scale(1.05);
        }
        .key-btn {
            height: 62px;
            background: rgba(255,255,255,0.2);
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 16px;
            color: white;
            font-size: 22px;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.12s ease, background 0.15s ease;
            backdrop-filter: blur(8px);
        }
        .key-btn:hover  { background: rgba(255,255,255,0.35); transform: scale(1.05); }
        .key-btn:active { transform: scale(0.92); }
        .key-clear {
            background: rgba(239,68,68,0.3);
            border-color: rgba(239,68,68,0.4);
            font-size: 13px;
            font-weight: 700;
        }
        .key-enter {
            background: rgba(255,255,255,0.9);
            color: #7c3aed;
            font-size: 24px;
        }
        .key-enter:hover { background: white; }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden flex flex-col items-center justify-center p-6"
      style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 35%, #ec4899 70%, #f97316 100%);">

    <!-- Decoraciones -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-white/5 rounded-full"></div>
        <span class="f1 absolute top-[6%]  left-[8%]  text-4xl opacity-25 select-none">⭐</span>
        <span class="f2 absolute top-[10%] right-[9%] text-5xl opacity-20 select-none">🌟</span>
        <span class="f3 absolute top-[45%] left-[3%]  text-3xl opacity-25 select-none">🎈</span>
        <span class="f4 absolute bottom-[12%] right-[7%] text-4xl opacity-20 select-none">✨</span>
    </div>

    <div class="relative z-10 w-full max-w-sm">

        <!-- Avatar + saludo -->
        <div class="fade-up text-center mb-8">
            @if($hijo->avatarUrl())
                <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                     class="w-24 h-24 rounded-3xl mx-auto mb-4 object-cover shadow-2xl border-2 border-white/50">
            @else
                <div class="w-24 h-24 rounded-3xl mx-auto mb-4 flex items-center justify-center font-black text-5xl text-white shadow-2xl border-2 border-white/30"
                     style="background: {{ $hijo->avatarColor() }}">
                    {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
                </div>
            @endif
            <h2 class="text-3xl font-extrabold text-white drop-shadow-md">¡Hola, {{ $hijo->nombre }}!</h2>
            <p class="text-white/75 text-base mt-1">Escribe tu PIN secreto 🔐</p>
        </div>

        <!-- Error -->
        @if($errors->any())
            <div id="errorMsg" class="mb-4 bg-red-500/80 backdrop-blur text-white rounded-2xl px-4 py-3 text-center border border-red-400/50 font-semibold text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Slots del PIN -->
        <div class="fade-up2">
            <div class="flex justify-center gap-4 mb-8" id="pinSlots">
                <div class="pin-slot" id="s1"></div>
                <div class="pin-slot" id="s2"></div>
                <div class="pin-slot" id="s3"></div>
                <div class="pin-slot" id="s4"></div>
            </div>

            <!-- Formulario -->
            <form action="{{ route('hijo.verificarPin') }}" method="POST" id="pinForm">
                @csrf
                <input type="hidden" name="id_hijo" value="{{ $hijo->id_hijo }}">
                <input type="hidden" name="pin" id="pinInput">

                <!-- Teclado numérico -->
                <div class="grid grid-cols-3 gap-3 mb-5">
                    @foreach([1,2,3,4,5,6,7,8,9] as $n)
                        <button type="button" class="key-btn" onclick="addDigit('{{ $n }}')">{{ $n }}</button>
                    @endforeach
                    <button type="button" class="key-btn key-clear" onclick="clearAll()">✕ Borrar</button>
                    <button type="button" class="key-btn" onclick="addDigit('0')">0</button>
                    <button type="submit" class="key-btn key-enter">→</button>
                </div>
            </form>

            <!-- Solo volver a seleccionar hijo, NO al dashboard de padre -->
            <div class="text-center">
                <a href="{{ route('hijo.seleccionar') }}"
                   class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition">
                    ← Elegir otro perfil
                </a>
            </div>
        </div>
    </div>

    <script>
        let pin = '';

        function addDigit(d) {
            if (pin.length >= 4) return;
            pin += d;
            updateSlots();
            if (pin.length === 4) {
                document.getElementById('pinInput').value = pin;
                setTimeout(() => document.getElementById('pinForm').submit(), 120);
            }
        }

        function clearAll() {
            pin = '';
            updateSlots();
        }

        function clearLast() {
            pin = pin.slice(0, -1);
            updateSlots();
        }

        function updateSlots() {
            for (let i = 1; i <= 4; i++) {
                const slot = document.getElementById('s' + i);
                if (i <= pin.length) {
                    slot.classList.add('filled');
                    slot.innerHTML = '<span class="dot-enter">●</span>';
                } else {
                    slot.classList.remove('filled');
                    slot.innerHTML = '';
                }
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key >= '0' && e.key <= '9') addDigit(e.key);
            if (e.key === 'Backspace') clearLast();
            if (e.key === 'Escape') clearAll();
        });

        // Shake en error
        @if($errors->any())
            document.getElementById('pinSlots')?.classList.add('shake');
        @endif
    </script>
</body>
</html>
