<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi espacio') — KidsRoutine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-violet-400 via-purple-400 to-pink-400">

    {{-- Header del hijo --}}
    <header class="bg-white/20 backdrop-blur-sm border-b border-white/30">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-3xl">{{ $hijo->avatarEmoji() }}</span>
                <div>
                    <p class="font-bold text-white text-lg leading-none">{{ $hijo->nombre }}</p>
                    <p class="text-white/70 text-xs">{{ $hijo->edad }} años</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="bg-yellow-400 rounded-full px-4 py-2 flex items-center gap-2 shadow">
                    <span class="text-xl">🪙</span>
                    <span class="font-bold text-yellow-900 text-lg">{{ $hijo->monedas }}</span>
                    <span class="text-yellow-800 text-sm">monedas</span>
                </div>

                <form action="{{ route('hijo.salir') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-white/20 hover:bg-white/30 text-white rounded-xl px-3 py-2 text-sm transition">
                        🔄 Salir
                    </button>
                </form>
            </div>
        </div>

        {{-- Nav --}}
        <div class="max-w-2xl mx-auto px-4 pb-3 flex gap-2">
            <a href="{{ route('hijo.dashboard') }}"
               class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium transition
               {{ request()->routeIs('hijo.dashboard') ? 'bg-white text-purple-700' : 'text-white/80 hover:bg-white/20' }}">
                🏠 Mis tareas
            </a>
            <a href="{{ route('hijo.recompensas') }}"
               class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium transition
               {{ request()->routeIs('hijo.recompensas') ? 'bg-white text-purple-700' : 'text-white/80 hover:bg-white/20' }}">
                🎁 Tienda
            </a>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-6">
        @if(session('exito'))
            <div class="mb-4 bg-green-500 text-white rounded-2xl px-4 py-3 flex items-center gap-2 shadow">
                <span>🎉</span> {{ session('exito') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-400 text-white rounded-2xl px-4 py-3">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
