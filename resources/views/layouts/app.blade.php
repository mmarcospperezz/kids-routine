<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') — Kids Routine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { transform: translateX(-100%); }
            to   { transform: translateX(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-alert { animation: fadeInDown 0.4s ease-out both; }
        .card-hover { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.08); }
        .nav-link { transition: background 0.18s, color 0.18s, transform 0.18s; }
        .nav-link:hover { transform: translateX(3px); }
        .stat-enter { animation: fadeInUp 0.5s ease-out both; }
        .stat-enter:nth-child(1) { animation-delay: 0.05s; }
        .stat-enter:nth-child(2) { animation-delay: 0.1s;  }
        .stat-enter:nth-child(3) { animation-delay: 0.15s; }
        .stat-enter:nth-child(4) { animation-delay: 0.2s;  }
        input, select, textarea {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <!-- Overlay móvil -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-indigo-800 to-indigo-950 text-white z-30 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl">

        <!-- Logo -->
        <div class="px-5 py-5 border-b border-indigo-700/60">
            <a href="{{ route('padre.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center text-xl group-hover:bg-white/25 transition">🌟</div>
                <div>
                    <div class="font-bold text-base leading-tight">Kids Routine</div>
                    <div class="text-indigo-300 text-xs">Panel Familiar</div>
                </div>
            </a>
        </div>

        <!-- Usuario -->
        <div class="px-5 py-4 border-b border-indigo-700/40">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold truncate">{{ auth()->user()->nombre }}</div>
                    <div class="text-indigo-300 text-xs">Padre / Madre</div>
                </div>
            </div>
        </div>

        <!-- Navegación -->
        @php
            $hijoIds    = auth()->user()->hijos()->where('activo', true)->pluck('id_hijo');
            $pendVal    = \App\Models\TareaInstancia::whereIn('id_hijo', $hijoIds)->where('estado', 'COMPLETADA')->count();
            $pendCanj   = \App\Models\Canje::whereIn('id_hijo', $hijoIds)->where('estado', 'PENDIENTE')->count();
        @endphp

        <nav class="flex-1 px-3 py-4 overflow-y-auto">
            <p class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest px-2 mb-2">Panel</p>

            <a href="{{ route('padre.dashboard') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="text-base w-5 text-center">🏠</span> Inicio
            </a>

            <a href="{{ route('padre.hijos.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.hijos.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="text-base w-5 text-center">👨‍👧‍👦</span> Mis Hijos
            </a>

            <a href="{{ route('padre.tareas.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.tareas.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="text-base w-5 text-center">✅</span> Tareas
            </a>

            <a href="{{ route('padre.validaciones') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.validaciones') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="text-base w-5 text-center">🔍</span>
                <span class="flex-1">Validaciones</span>
                @if($pendVal > 0)
                    <span class="bg-amber-400 text-amber-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ $pendVal }}</span>
                @endif
            </a>

            <a href="{{ route('padre.recompensas.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.recompensas.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="text-base w-5 text-center">🎁</span> Recompensas
            </a>

            <a href="{{ route('padre.canjes.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.canjes.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="text-base w-5 text-center">🏆</span>
                <span class="flex-1">Canjes</span>
                @if($pendCanj > 0)
                    <span class="bg-pink-400 text-pink-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ $pendCanj }}</span>
                @endif
            </a>

            <div class="border-t border-indigo-700/40 my-3"></div>
            <p class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest px-2 mb-2">Niños</p>

            <a href="{{ route('hijo.seleccionar') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium text-indigo-200 hover:bg-white/10 hover:text-white">
                <span class="text-base w-5 text-center">🎮</span> Modo Hijo
            </a>
        </nav>

        <!-- Cerrar sesión -->
        <div class="px-3 py-3 border-t border-indigo-700/40">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-indigo-300 hover:bg-red-500/20 hover:text-red-300">
                    <span class="text-base w-5 text-center">🚪</span> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenido principal -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        <!-- Topbar móvil -->
        <header class="lg:hidden bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-10 shadow-sm">
            <button onclick="openSidebar()" class="p-2 rounded-xl hover:bg-slate-100 active:bg-slate-200 transition">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <span class="text-xl">🌟</span>
                <span class="font-bold text-indigo-700 text-base">Kids Routine</span>
            </div>
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center font-bold text-sm text-white shadow-sm">
                {{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">

            @if(session('exito'))
                <div class="fade-alert mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm">
                    <span class="text-xl flex-shrink-0">✅</span>
                    <span class="text-sm font-medium">{{ session('exito') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="fade-alert mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm">
                    <span class="text-xl flex-shrink-0">❌</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="fade-alert mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="text-xl flex-shrink-0">⚠️</span>
                        <ul class="list-none space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function openSidebar()  {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('overlay').classList.remove('hidden');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('overlay').classList.add('hidden');
        }
    </script>
</body>
</html>
