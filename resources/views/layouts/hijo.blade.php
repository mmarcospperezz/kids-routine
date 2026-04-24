<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi espacio') — Kids Routine</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes floatStar {
            0%, 100% { transform: translateY(0) scale(1) rotate(0deg); }
            25%       { transform: translateY(-20px) scale(1.1) rotate(10deg); }
            75%       { transform: translateY(-10px) scale(0.95) rotate(-5deg); }
        }
        @keyframes coinPop {
            0%   { transform: scale(1); }
            30%  { transform: scale(1.25) rotate(-8deg); }
            60%  { transform: scale(0.95) rotate(4deg); }
            100% { transform: scale(1); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes sparkle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(1.3) rotate(20deg); }
        }
        @keyframes progressFill {
            from { width: 0%; }
            to   { width: var(--progress); }
        }
        .star-1 { animation: floatStar 6s   ease-in-out infinite; }
        .star-2 { animation: floatStar 8s   ease-in-out 1s   infinite; }
        .star-3 { animation: floatStar 7s   ease-in-out 2s   infinite; }
        .star-4 { animation: floatStar 9s   ease-in-out 0.5s infinite; }
        .star-5 { animation: floatStar 5.5s ease-in-out 3s   infinite; }
        .coin-bounce  { animation: coinPop 0.5s ease-out; }
        .header-slide { animation: slideDown 0.5s ease-out both; }
        .card-fade    { animation: fadeInUp 0.5s ease-out both; }
        .sparkle      { animation: sparkle 2s ease-in-out infinite; }
        .progress-bar { animation: progressFill 1.2s cubic-bezier(0.22,1,0.36,1) both 0.3s; }
        .task-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .task-card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .btn-complete {
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s;
        }
        .btn-complete:hover  { transform: scale(1.05); box-shadow: 0 6px 16px rgba(139,92,246,0.4); }
        .btn-complete:active { transform: scale(0.96); }
        .reward-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .reward-card:hover { transform: translateY(-4px) rotate(-1deg); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden" style="background: linear-gradient(135deg, #7c3aed 0%, #9333ea 30%, #ec4899 65%, #f97316 100%);">

    <!-- Estrellas decorativas de fondo -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <span class="star-1 absolute top-[5%]  left-[8%]  text-4xl opacity-20 select-none">⭐</span>
        <span class="star-2 absolute top-[15%] right-[10%] text-5xl opacity-15 select-none">✨</span>
        <span class="star-3 absolute top-[45%] left-[3%]  text-3xl opacity-20 select-none">🌟</span>
        <span class="star-4 absolute bottom-[20%] right-[5%] text-4xl opacity-15 select-none">⭐</span>
        <span class="star-5 absolute bottom-[8%]  left-[15%] text-3xl opacity-20 select-none">✨</span>
        <!-- Círculos de fondo -->
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-white/5 rounded-full"></div>
        <div class="absolute top-1/3 right-1/4 w-48 h-48 bg-yellow-300/5 rounded-full"></div>
    </div>

    <!-- Header -->
    <header class="header-slide relative z-10 bg-white/15 backdrop-blur-md border-b border-white/20 shadow-lg">
        <div class="max-w-2xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Avatar + nombre -->
                <div class="flex items-center gap-3">
                    @if($hijo->avatarUrl())
                        <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                             class="w-14 h-14 rounded-2xl object-cover shadow-lg border-2 border-white/40">
                    @else
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl text-white shadow-lg border border-white/30"
                             style="background: {{ $hijo->avatarColor() }}">
                            {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-extrabold text-white text-lg leading-tight">{{ $hijo->nombre }}</p>
                        <p class="text-white/70 text-xs">{{ $hijo->edad }} años 🎂</p>
                    </div>
                </div>

                <!-- Monedas + salir -->
                <div class="flex items-center gap-2">
                    <div class="bg-yellow-400 rounded-2xl px-3 py-2 flex items-center gap-1.5 shadow-lg border-2 border-yellow-300">
                        <span class="sparkle text-xl"><x-moneda /></span>
                        <span class="font-extrabold text-yellow-900 text-lg leading-none">{{ $hijo->monedas }}</span>
                    </div>
                    <form action="{{ route('hijo.salir') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition border border-white/20"
                                title="Salir">
                            🔄
                        </button>
                    </form>
                </div>
            </div>

            <!-- Navegación de tabs -->
            <div class="flex gap-2 mt-3">
                <a href="{{ route('hijo.dashboard') }}"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold transition
                   {{ request()->routeIs('hijo.dashboard') ? 'bg-white text-purple-700 shadow-md' : 'text-white/80 hover:bg-white/20 hover:text-white' }}">
                    🏠 Mis Tareas
                </a>
                <a href="{{ route('hijo.recompensas') }}"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold transition
                   {{ request()->routeIs('hijo.recompensas') ? 'bg-white text-purple-700 shadow-md' : 'text-white/80 hover:bg-white/20 hover:text-white' }}">
                    🛍️ Tienda
                </a>
                <a href="{{ route('hijo.juegos') }}"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold transition
                   {{ request()->routeIs('hijo.juegos*') ? 'bg-white text-purple-700 shadow-md' : 'text-white/80 hover:bg-white/20 hover:text-white' }}">
                    🎮 Juegos
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido -->
    <main class="relative z-10 max-w-2xl mx-auto px-4 py-6 pb-10">

        @if(session('exito'))
            <div class="mb-4 bg-green-500 text-white rounded-2xl px-4 py-3 flex items-center gap-2 shadow-lg border border-green-400 card-fade">
                <span class="text-xl">🎉</span>
                <span class="font-semibold">{{ session('exito') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-500/90 text-white rounded-2xl px-4 py-3 shadow-lg card-fade">
                @foreach($errors->all() as $error)
                    <p class="text-sm">⚠️ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
