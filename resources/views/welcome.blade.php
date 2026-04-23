<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids Routine — Convierte las rutinas en aventuras</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33%       { transform: translateY(-20px) rotate(8deg); }
            66%       { transform: translateY(-10px) rotate(-4deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes heroGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50%       { opacity: 0.7; transform: scale(1.05); }
        }
        .f1 { animation: float 7s ease-in-out infinite; }
        .f2 { animation: float 9s ease-in-out 1.5s infinite; }
        .f3 { animation: float 6s ease-in-out 3s infinite; }
        .f4 { animation: float 8s ease-in-out 0.5s infinite; }
        .f5 { animation: float 10s ease-in-out 2s infinite; }
        .fade-up { animation: fadeInUp 0.7s ease-out both; }
        .fade-up-1 { animation: fadeInUp 0.7s ease-out 0.1s both; }
        .fade-up-2 { animation: fadeInUp 0.7s ease-out 0.2s both; }
        .fade-up-3 { animation: fadeInUp 0.7s ease-out 0.3s both; }
        .glow { animation: heroGlow 4s ease-in-out infinite; }
        .feature-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .step-card { transition: transform 0.25s ease; }
        .step-card:hover { transform: scale(1.03); }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

    <!-- Navegación -->
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md border-b border-slate-100 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xl shadow-sm"
                     style="background: linear-gradient(135deg, #6366f1, #a855f7);">🌟</div>
                <span class="text-xl font-extrabold text-indigo-700">Kids Routine</span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('padre.dashboard') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm">
                        Ir al panel →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-indigo-600 text-sm font-medium transition">
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm">
                        Empieza gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-32 pb-24 px-6 relative"
             style="background: linear-gradient(160deg, #eef2ff 0%, #fdf4ff 50%, #fff7ed 100%);">
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="glow absolute top-10 left-1/4 w-80 h-80 bg-purple-200/40 rounded-full blur-3xl"></div>
            <div class="glow absolute bottom-10 right-1/4 w-80 h-80 bg-indigo-200/40 rounded-full blur-3xl" style="animation-delay: 2s;"></div>
            <span class="f1 absolute top-[10%] left-[5%] text-5xl opacity-30 select-none">⭐</span>
            <span class="f2 absolute top-[15%] right-[6%] text-6xl opacity-25 select-none">🌟</span>
            <span class="f3 absolute bottom-[10%] left-[8%] text-4xl opacity-25 select-none">🎈</span>
            <span class="f4 absolute bottom-[15%] right-[5%] text-5xl opacity-20 select-none">✨</span>
            <span class="f5 absolute top-[50%] left-[2%] text-3xl opacity-20 select-none">🎯</span>
        </div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <div class="fade-up inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 rounded-full px-4 py-1.5 text-sm font-bold mb-6 shadow-sm">
                <span>🚀</span> Herramienta educativa para familias
            </div>
            <h1 class="fade-up-1 text-5xl sm:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                Convierte las rutinas<br>
                en <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">aventuras</span> 🎉
            </h1>
            <p class="fade-up-2 text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Asigna tareas a tus hijos, premia su esfuerzo con monedas virtuales y deja que elijan sus recompensas. ¡Aprender a ser responsables nunca fue tan divertido!
            </p>
            <div class="fade-up-3 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl text-lg font-extrabold transition shadow-xl shadow-indigo-200/60">
                    Empezar gratis →
                </a>
                <a href="{{ route('login') }}"
                   class="inline-block bg-white text-indigo-600 border-2 border-indigo-200 hover:border-indigo-400 px-8 py-4 rounded-2xl text-lg font-bold transition shadow-md">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </section>

    <!-- Cómo funciona -->
    <section class="py-24 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-3">¿Cómo funciona?</h2>
                <p class="text-slate-500 text-lg">En tres pasos sencillos</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['📋', 'from-indigo-100 to-blue-50', '1. Crea tareas', 'Define tareas diarias, semanales o puntuales para cada hijo con su recompensa en monedas.'],
                    ['🪙', 'from-amber-100 to-yellow-50', '2. Gana monedas', 'El hijo completa tareas y acumula monedas. Tú las validas desde tu panel en segundos.'],
                    ['🎁', 'from-pink-100 to-rose-50', '3. Elige premios', 'Con las monedas ganadas, los niños pueden canjear las recompensas que tú hayas definido.'],
                ] as [$icon, $bg, $title, $desc])
                    <div class="step-card text-center bg-gradient-to-br {{ $bg }} rounded-3xl p-8 shadow-sm border border-white">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center text-4xl mx-auto mb-5 shadow-md">
                            {{ $icon }}
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 mb-3">{{ $title }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-24 px-6 bg-slate-50">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Todo lo que necesitas</h2>
                <p class="text-slate-500 text-lg">Diseñado para familias reales</p>
            </div>
            <div class="grid md:grid-cols-2 gap-5">
                @foreach([
                    ['🔐', 'Panel seguro para padres', 'Acceso con email y contraseña. Tú controlas todo desde tu panel.'],
                    ['🕹️', 'Modo niño con PIN', 'Los niños acceden con un PIN de 4 dígitos. Simple, seguro y divertido.'],
                    ['🔄', 'Tareas recurrentes', 'Define tareas diarias o semanales que se generan automáticamente.'],
                    ['📊', 'Historial de monedas', 'Registro completo de ganancias y gastos de cada hijo.'],
                    ['⚡', 'Validación instantánea', 'Aprueba o rechaza tareas completadas con un solo clic.'],
                    ['👨‍👧‍👦', 'Múltiples hijos', 'Gestiona todos tus hijos desde un mismo panel unificado.'],
                ] as [$icon, $title, $desc])
                    <div class="feature-card bg-white rounded-2xl p-6 flex gap-4 shadow-sm border border-slate-100">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm"
                             style="background: linear-gradient(135deg, #eef2ff, #fdf2f8);">
                            {{ $icon }}
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-800 mb-1">{{ $title }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA final -->
    <section class="py-24 px-6 relative overflow-hidden"
             style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #9333ea 100%);">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <span class="f1 absolute top-[10%] left-[5%] text-5xl opacity-20 select-none">⭐</span>
            <span class="f3 absolute bottom-[10%] right-[8%] text-6xl opacity-15 select-none">🌟</span>
        </div>
        <div class="max-w-2xl mx-auto text-center relative z-10">
            <h2 class="text-4xl font-extrabold text-white mb-4">¿Listo para empezar?</h2>
            <p class="text-indigo-200 text-lg mb-10">Crea tu cuenta gratis y configura las primeras tareas en menos de 5 minutos.</p>
            <a href="{{ route('register') }}"
               class="inline-block bg-white text-indigo-600 hover:bg-indigo-50 px-10 py-4 rounded-2xl text-lg font-extrabold transition shadow-2xl">
                Crear cuenta gratis →
            </a>
        </div>
    </section>

    <footer class="py-8 text-center text-slate-400 text-sm border-t border-slate-100">
        <p>Kids Routine · TFG DAW 2026 · Marcos Pérez</p>
    </footer>

</body>
</html>
