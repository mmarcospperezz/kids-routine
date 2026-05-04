<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi espacio') — Kids Routine</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='22' fill='%237c3aed'/><circle cx='22' cy='32' r='11' fill='white'/><ellipse cx='22' cy='57' rx='12' ry='14' fill='white'/><circle cx='78' cy='32' r='11' fill='white'/><ellipse cx='78' cy='57' rx='12' ry='14' fill='white'/><ellipse cx='50' cy='65' rx='9' ry='11' fill='white'/><circle cx='50' cy='41' r='10' fill='white'/></svg>" type="image/svg+xml">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#7c3aed">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(()=>{});</script>
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
                    <label for="avatarHijoInput" class="cursor-pointer group relative flex-shrink-0" title="Cambiar foto">
                        @if($hijo->avatarUrl())
                            <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                                 class="w-14 h-14 rounded-2xl object-cover shadow-lg border-2 border-white/40 group-hover:border-white/80 transition">
                        @else
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl text-white shadow-lg border border-white/30 group-hover:border-white/70 transition"
                                 style="background: {{ $hijo->avatarColor() }}">
                                {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
                            </div>
                        @endif
                        <div class="absolute inset-0 rounded-2xl bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-white text-lg">📷</span>
                        </div>
                    </label>
                    <form action="{{ route('hijo.perfil.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarHijoForm">
                        @csrf
                        <input type="file" id="avatarHijoInput" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp,image/heic,image/heif" class="hidden"
                               onchange="document.getElementById('avatarHijoForm').submit()">
                    </form>
                    <div>
                        <p class="font-extrabold text-white text-lg leading-tight">{{ $hijo->nombre }}</p>
                        <p class="text-white/70 text-xs">{{ $hijo->edad }} años 🎂</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <label for="avatarHijoInput" class="text-white/60 hover:text-white text-[10px] cursor-pointer transition">
                                {{ $hijo->avatarUrl() ? '🔄 Cambiar' : '📷 Añadir foto' }}
                            </label>
                            @if($hijo->avatarUrl())
                                <form action="{{ route('hijo.perfil.avatar.eliminar') }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-300 hover:text-red-200 text-[10px] transition">✕ Eliminar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Monedas + opciones + salir -->
                <div class="flex items-center gap-2">
                    <div class="bg-yellow-400 rounded-2xl px-3 py-2 flex items-center gap-1.5 shadow-lg border-2 border-yellow-300">
                        <span class="sparkle text-xl"><x-moneda /></span>
                        <span class="font-extrabold text-yellow-900 text-lg leading-none">{{ $hijo->monedas }}</span>
                    </div>
                    <!-- Botón cambiar PIN -->
                    <button onclick="document.getElementById('modalPin').classList.remove('hidden')"
                            class="flex flex-col items-center justify-center bg-white/20 hover:bg-white/30 rounded-xl px-2 py-1 text-white transition border border-white/20 min-w-[44px]"
                            title="Cambiar PIN">
                        <span class="text-base leading-none">🔑</span>
                        <span class="text-[9px] font-bold leading-tight mt-0.5 opacity-90">PIN</span>
                    </button>
                    <form action="{{ route('hijo.salir') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex flex-col items-center justify-center bg-white/20 hover:bg-white/30 rounded-xl px-2 py-1 text-white transition border border-white/20 min-w-[44px]"
                                title="Cerrar Sesión">
                            <span class="text-base leading-none">🔄</span>
                            <span class="text-[9px] font-bold leading-tight mt-0.5 opacity-90">Salir</span>
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

    <!-- Modal cambiar PIN -->
    <div id="modalPin" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <h3 class="text-lg font-extrabold text-gray-800 mb-1">🔑 Cambiar mi PIN</h3>
            <p class="text-slate-500 text-xs mb-4">Tu padre tiene que aprobar el cambio antes de que surta efecto.</p>
            <form action="{{ route('hijo.perfil.solicitar_pin') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nuevo PIN (4 dígitos)</label>
                    <input type="password" name="nuevo_pin" maxlength="4" pattern="\d{4}" inputmode="numeric" required
                           class="w-full border-2 border-slate-200 rounded-xl px-4 py-2.5 text-center text-2xl tracking-widest focus:border-purple-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Confirmar PIN</label>
                    <input type="password" name="nuevo_pin_confirm" maxlength="4" pattern="\d{4}" inputmode="numeric" required
                           class="w-full border-2 border-slate-200 rounded-xl px-4 py-2.5 text-center text-2xl tracking-widest focus:border-purple-400 outline-none">
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="document.getElementById('modalPin').classList.add('hidden')"
                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-sm transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                        Solicitar
                    </button>
                </div>
            </form>
        </div>
    </div>

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
