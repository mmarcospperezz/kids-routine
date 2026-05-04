<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') — Kids Routine</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%237c3aed'/%3E%3Ccircle cx='22' cy='32' r='11' fill='white'/%3E%3Cellipse cx='22' cy='57' rx='12' ry='14' fill='white'/%3E%3Ccircle cx='78' cy='32' r='11' fill='white'/%3E%3Cellipse cx='78' cy='57' rx='12' ry='14' fill='white'/%3E%3Cellipse cx='50' cy='65' rx='9' ry='11' fill='white'/%3E%3Ccircle cx='50' cy='41' r='10' fill='white'/%3E%3C/svg%3E" type="image/svg+xml">
    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <script>
        // Apply dark mode before paint to avoid flash
        if (localStorage.getItem('dark_mode') === '1') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
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
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        /* Scrollbar del sidebar */
        #sidebar nav::-webkit-scrollbar { width: 4px; }
        #sidebar nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar nav::-webkit-scrollbar-thumb { background: rgba(165,180,252,0.25); border-radius: 99px; }
        #sidebar nav::-webkit-scrollbar-thumb:hover { background: rgba(165,180,252,0.5); }
        #sidebar nav { scrollbar-width: thin; scrollbar-color: rgba(165,180,252,0.25) transparent; }
        /* Dark mode */
        .dark body { background-color: #0f172a; color: #e2e8f0; }
        .dark .bg-white { background-color: #1e293b !important; }
        .dark .bg-slate-50 { background-color: #0f172a !important; }
        .dark .border-slate-100 { border-color: #334155 !important; }
        .dark .text-gray-800, .dark .text-gray-900 { color: #f1f5f9 !important; }
        .dark .text-slate-500, .dark .text-slate-600 { color: #94a3b8 !important; }
        .dark .bg-slate-50.hover\:bg-white { background-color: #1e293b !important; }
        .dark input, .dark select, .dark textarea { background-color: #1e293b !important; border-color: #475569 !important; color: #e2e8f0 !important; }
        .dark .bg-emerald-50 { background-color: #064e3b !important; }
        .dark .bg-red-50 { background-color: #450a0a !important; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-indigo-800 to-indigo-950 text-white z-30 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl">

        <div class="px-5 py-5 border-b border-indigo-700/60">
            <a href="{{ route('padre.dashboard') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.svg') }}" alt="Kids Routine" class="w-10 h-10 rounded-xl group-hover:opacity-90 transition">
                <div>
                    <div class="font-bold text-base leading-tight">Kids Routine</div>
                    <div class="text-indigo-300 text-xs">Panel Familiar</div>
                </div>
            </a>
        </div>

        <!-- Usuario con opción de foto -->
        <div class="px-5 py-4 border-b border-indigo-700/40">
            <div class="flex items-center gap-3">
                <!-- Avatar del padre -->
                <label for="avatarPadreInput" class="cursor-pointer group relative flex-shrink-0" title="Cambiar foto">
                    @if(auth()->user()->avatarUrl())
                        <img src="{{ auth()->user()->avatarUrl() }}"
                             alt="{{ auth()->user()->nombre }}"
                             class="w-10 h-10 rounded-full object-cover shadow-sm border-2 border-white/30 group-hover:border-white/70 transition">
                    @else
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm text-white shadow-sm border-2 border-white/30 group-hover:border-white/70 transition"
                             style="background: linear-gradient(135deg,#a855f7,#ec4899)">
                            {{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}
                        </div>
                    @endif
                    <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <span class="text-white text-xs">📷</span>
                    </div>
                </label>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-semibold truncate">{{ auth()->user()->nombre }}</div>
                    <div class="text-indigo-300 text-xs">Padre / Madre</div>
                    <!-- Acciones de foto -->
                    <div class="flex items-center gap-2 mt-1">
                        <label for="avatarPadreInput" class="text-indigo-300 hover:text-white text-[10px] cursor-pointer transition">
                            {{ auth()->user()->avatarUrl() ? '🔄 Cambiar' : '📷 Añadir foto' }}
                        </label>
                        @if(auth()->user()->avatarUrl())
                            <form action="{{ route('padre.perfil.avatar.eliminar') }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-[10px] transition"
                                        onclick="return confirm('¿Eliminar foto de perfil?')">✕ Eliminar</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Form oculto para subir avatar -->
            <form action="{{ route('padre.perfil.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarPadreForm">
                @csrf
                <input type="file" id="avatarPadreInput" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp,image/heic,image/heif" class="hidden"
                       onchange="document.getElementById('avatarPadreForm').submit()">
            </form>
        </div>

        @php
            $hijoIds  = auth()->user()->hijos()->where('activo', true)->pluck('id_hijo');
            $pendVal  = \App\Models\TareaInstancia::whereIn('id_hijo', $hijoIds)->where('estado', 'COMPLETADA')->count();
            $pendCanj = \App\Models\Canje::whereIn('id_hijo', $hijoIds)->where('estado', 'PENDIENTE')->count();
        @endphp

        <nav class="flex-1 px-3 py-4 overflow-y-auto">
            <p class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest px-2 mb-2">Panel</p>

            <a href="{{ route('padre.dashboard') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">🏠</span> Inicio
            </a>
            <a href="{{ route('padre.hijos.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.hijos.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">👨‍👧‍👦</span> Mis Hijos
            </a>
            <a href="{{ route('padre.tareas.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.tareas.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">✅</span> Tareas
            </a>
            <a href="{{ route('padre.validaciones') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.validaciones') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">🔍</span>
                <span class="flex-1">Validaciones</span>
                @if($pendVal > 0)
                    <span class="bg-amber-400 text-amber-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendVal }}</span>
                @endif
            </a>
            <a href="{{ route('padre.recompensas.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.recompensas.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">🎁</span> Recompensas
            </a>
            <a href="{{ route('padre.canjes.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.canjes.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">🏆</span>
                <span class="flex-1">Canjes</span>
                @if($pendCanj > 0)
                    <span class="bg-pink-400 text-pink-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendCanj }}</span>
                @endif
            </a>
            <a href="{{ route('padre.juegos.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.juegos.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">🎮</span> Juegos
            </a>
            <a href="{{ route('padre.estadisticas') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.estadisticas') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">📊</span> Estadísticas
            </a>

            @php $pendPin = \App\Models\SolicitudPin::whereIn('id_hijo', $hijoIds)->where('estado','PENDIENTE')->count(); @endphp
            @if($pendPin > 0)
            <a href="{{ route('padre.solicitudes_pin.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium
               {{ request()->routeIs('padre.solicitudes_pin.*') ? 'bg-white/20 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <span class="w-5 text-center">🔑</span>
                <span class="flex-1">Cambios PIN</span>
                <span class="bg-yellow-400 text-yellow-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendPin }}</span>
            </a>
            @endif

            <div class="border-t border-indigo-700/40 my-3"></div>
            <p class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest px-2 mb-2">Niños</p>
            <a href="{{ route('hijo.seleccionar') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium text-indigo-200 hover:bg-white/10 hover:text-white">
                <span class="w-5 text-center">👦</span> Modo Hijo
            </a>
        </nav>

        <div class="px-3 py-3 border-t border-indigo-700/40 space-y-1">
            <button onclick="toggleDarkMode()" id="darkModeBtn"
                    class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-indigo-200 hover:bg-white/10 hover:text-white">
                <span class="w-5 text-center" id="darkModeIcon">🌙</span>
                <span id="darkModeLabel">Modo oscuro</span>
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-indigo-300 hover:bg-red-500/20 hover:text-red-300">
                    <span class="w-5 text-center">🚪</span> Cerrar Sesión
                </button>
            </form>
            <button onclick="document.getElementById('modalEliminarCuenta').classList.remove('hidden')"
                    class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-400/70 hover:bg-red-500/20 hover:text-red-400">
                <span class="w-5 text-center">🗑️</span> Eliminar cuenta
            </button>
        </div>
    </aside>

    <!-- Modal eliminar cuenta -->
    <div id="modalEliminarCuenta" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="text-center mb-5">
                <div class="text-5xl mb-3">⚠️</div>
                <h3 class="text-xl font-extrabold text-gray-800">¿Eliminar tu cuenta?</h3>
                <p class="text-slate-500 text-sm mt-2">Se borrarán permanentemente tu cuenta, todos tus hijos, tareas, recompensas e historial. <strong>Esta acción no se puede deshacer.</strong></p>
            </div>

            <form action="{{ route('padre.perfil.cuenta.eliminar') }}" method="POST" id="formEliminarCuenta">
                @csrf @method('DELETE')
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Escribe <span class="text-red-600 font-black">eliminar</span> para confirmar:
                </label>
                <input type="text" name="confirmacion" id="inputConfirmacion" autocomplete="off"
                       class="w-full border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-red-400 outline-none transition mb-1"
                       placeholder="eliminar">
                @error('confirmacion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </form>

            <div class="flex gap-3 mt-4">
                <button onclick="document.getElementById('modalEliminarCuenta').classList.add('hidden')"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-sm transition">
                    Cancelar
                </button>
                <button onclick="confirmarEliminar()"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                    Eliminar cuenta
                </button>
            </div>
        </div>
    </div>

    <script>
    function confirmarEliminar() {
        const val = document.getElementById('inputConfirmacion').value.trim().toLowerCase();
        if (val !== 'eliminar') {
            document.getElementById('inputConfirmacion').classList.add('border-red-400');
            document.getElementById('inputConfirmacion').focus();
            return;
        }
        document.getElementById('formEliminarCuenta').submit();
    }
    // Abrir modal si hay error de validación en el campo confirmacion
    @if($errors->has('confirmacion'))
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('modalEliminarCuenta').classList.remove('hidden');
        });
    @endif
    </script>

    <div class="lg:ml-64 min-h-screen flex flex-col">
        <header class="lg:hidden bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-10 shadow-sm">
            <button onclick="openSidebar()" class="p-2 rounded-xl hover:bg-slate-100 transition">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="Kids Routine" class="w-7 h-7 rounded-lg">
                <span class="font-bold text-indigo-700">Kids Routine</span>
            </div>
            @if(auth()->user()->avatarUrl())
                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->nombre }}"
                     class="w-9 h-9 rounded-full object-cover shadow-sm">
            @else
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white shadow-sm"
                     style="background: linear-gradient(135deg,#a855f7,#ec4899)">
                    {{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}
                </div>
            @endif
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
                        <ul class="space-y-1">
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
        function openSidebar()  { document.getElementById('sidebar').classList.remove('-translate-x-full'); document.getElementById('overlay').classList.remove('hidden'); }
        function closeSidebar() { document.getElementById('sidebar').classList.add('-translate-x-full');    document.getElementById('overlay').classList.add('hidden'); }

        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dark_mode', isDark ? '1' : '0');
            document.getElementById('darkModeIcon').textContent  = isDark ? '☀️' : '🌙';
            document.getElementById('darkModeLabel').textContent = isDark ? 'Modo claro' : 'Modo oscuro';
        }
        // Sync label on load
        if (localStorage.getItem('dark_mode') === '1') {
            document.getElementById('darkModeIcon').textContent  = '☀️';
            document.getElementById('darkModeLabel').textContent = 'Modo claro';
        }

        // Register service worker (PWA)
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>
</body>
</html>
