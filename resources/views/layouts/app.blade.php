<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') — KidsRoutine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-indigo-900 text-white flex flex-col min-h-screen fixed left-0 top-0 z-30">
        <div class="p-6 border-b border-indigo-800">
            <a href="{{ route('padre.dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl">⭐</span>
                <span class="text-xl font-bold">KidsRoutine</span>
            </a>
            <p class="text-indigo-300 text-sm mt-1">Hola, {{ Auth::user()->nombre }}</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            @php
                $hijoIds = Auth::user()->hijos()->where('activo', true)->pluck('id_hijo');
                $pendVal = \App\Models\TareaInstancia::whereIn('id_hijo', $hijoIds)->where('estado', 'COMPLETADA')->count();
                $pendCanj = \App\Models\Canje::whereIn('id_hijo', $hijoIds)->where('estado', 'PENDIENTE')->count();
            @endphp

            <a href="{{ route('padre.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('padre.dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <span>📊</span> Dashboard
            </a>

            <a href="{{ route('padre.hijos.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('padre.hijos.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <span>👦</span> Mis hijos
            </a>

            <a href="{{ route('padre.tareas.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('padre.tareas.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <span>✅</span> Tareas
            </a>

            <a href="{{ route('padre.recompensas.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('padre.recompensas.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <span>🎁</span> Recompensas
            </a>

            <a href="{{ route('padre.validaciones') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('padre.validaciones') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <span>⏳</span> Validaciones
                @if($pendVal > 0)
                    <span class="ml-auto bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendVal }}</span>
                @endif
            </a>

            <a href="{{ route('padre.canjes.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('padre.canjes.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <span>🎀</span> Canjes
                @if($pendCanj > 0)
                    <span class="ml-auto bg-pink-400 text-pink-900 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendCanj }}</span>
                @endif
            </a>

            <div class="border-t border-indigo-800 my-2"></div>

            <a href="{{ route('hijo.seleccionar') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-indigo-200 hover:bg-indigo-800 transition">
                <span>🕹️</span> Modo hijo
            </a>
        </nav>

        <div class="p-4 border-t border-indigo-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-indigo-300 hover:bg-indigo-800 hover:text-white transition text-left">
                    <span>🚪</span> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="ml-64 flex-1 p-8">
        @if(session('exito'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center gap-2">
                <span>✅</span> {{ session('exito') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
