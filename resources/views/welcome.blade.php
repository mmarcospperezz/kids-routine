<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KidsRoutine — Convierte las rutinas en aventuras</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

    {{-- Nav --}}
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-2xl">⭐</span>
                <span class="text-xl font-bold text-indigo-700">KidsRoutine</span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('padre.dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
                        Ir al panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 text-sm font-medium transition">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
                        Registrarse gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-32 pb-20 px-6 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 rounded-full px-4 py-1.5 text-sm font-medium mb-6">
                <span>🚀</span> Herramienta educativa para familias
            </div>
            <h1 class="text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                Convierte las rutinas<br>en <span class="text-indigo-600">aventuras</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                Asigna tareas a tus hijos, premia su esfuerzo con monedas virtuales y deja que elijan sus recompensas. ¡Aprender a ser responsables nunca fue tan divertido!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl text-lg font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    Empezar gratis →
                </a>
                <a href="{{ route('login') }}" class="bg-white text-indigo-600 border border-indigo-200 px-8 py-4 rounded-2xl text-lg font-semibold hover:border-indigo-400 transition">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </section>

    {{-- Flujo --}}
    <section class="py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">¿Cómo funciona?</h2>
            <p class="text-center text-gray-500 mb-12">En tres pasos sencillos</p>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">📋</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">1. Crea tareas</h3>
                    <p class="text-gray-500">Define tareas diarias, semanales o puntuales para cada hijo con su recompensa en monedas.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">🪙</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">2. Gana monedas</h3>
                    <p class="text-gray-500">El hijo completa tareas y acumula monedas. Tú las validas desde el panel de padres.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">🎁</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">3. Elige premios</h3>
                    <p class="text-gray-500">Con las monedas ganadas, los niños pueden canjear las recompensas que tú hayas definido.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-20 px-6 bg-gray-50">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Todo lo que necesitas</h2>
            <div class="grid md:grid-cols-2 gap-6">
                @foreach([
                    ['🔐', 'Panel seguro para padres', 'Acceso con email y contraseña. Tú controlas todo.'],
                    ['🕹️', 'Modo niño con PIN', 'Los niños acceden con un PIN de 4 dígitos. Simple y seguro.'],
                    ['🔄', 'Tareas recurrentes', 'Define tareas diarias o semanales que se generan automáticamente.'],
                    ['📊', 'Historial de monedas', 'Registro completo de ganancias y gastos de cada hijo.'],
                    ['⚡', 'Validación rápida', 'Aprueba o rechaza tareas completadas con un clic.'],
                    ['🎯', 'Múltiples hijos', 'Gestiona todos tus hijos desde un mismo panel.'],
                ] as [$icon, $title, $desc])
                <div class="bg-white rounded-2xl p-6 flex gap-4 shadow-sm">
                    <div class="text-3xl flex-shrink-0">{{ $icon }}</div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">{{ $title }}</h3>
                        <p class="text-gray-500 text-sm">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-6 bg-indigo-600">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-4">¿Listo para empezar?</h2>
            <p class="text-indigo-200 mb-8">Crea tu cuenta gratis y configura las primeras tareas en menos de 5 minutos.</p>
            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-2xl text-lg font-semibold hover:bg-indigo-50 transition inline-block">
                Crear cuenta gratis →
            </a>
        </div>
    </section>

    <footer class="py-8 text-center text-gray-400 text-sm border-t">
        <p>KidsRoutine — TFG DAW 2025 · Marcos Pérez</p>
    </footer>

</body>
</html>
