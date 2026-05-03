<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids Routine — Convierte las rutinas en aventuras</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Kids Routine — Convierte las rutinas en aventuras">
    <meta property="og:description" content="Asigna tareas a tus hijos, premia su esfuerzo con monedas virtuales y deja que elijan sus recompensas. ¡Aprender a ser responsables nunca fue tan divertido!">
    <meta property="og:image" content="{{ asset('images/logo.svg') }}">
    <meta property="og:locale" content="es_ES">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Kids Routine — Convierte las rutinas en aventuras">
    <meta name="twitter:description" content="Asigna tareas a tus hijos, premia su esfuerzo con monedas virtuales y deja que elijan sus recompensas.">
    <meta name="description" content="Kids Routine: la app para familias que convierte las rutinas diarias en aventuras. Tareas, recompensas, juegos educativos y mucho más.">

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
        @keyframes heroGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50%       { opacity: 0.7; transform: scale(1.05); }
        }
        .f1 { animation: float 7s ease-in-out infinite; }
        .f2 { animation: float 9s ease-in-out 1.5s infinite; }
        .f3 { animation: float 6s ease-in-out 3s infinite; }
        .f4 { animation: float 8s ease-in-out 0.5s infinite; }
        .f5 { animation: float 10s ease-in-out 2s infinite; }
        .fade-up   { animation: fadeInUp 0.7s ease-out both; }
        .fade-up-1 { animation: fadeInUp 0.7s ease-out 0.1s both; }
        .fade-up-2 { animation: fadeInUp 0.7s ease-out 0.2s both; }
        .fade-up-3 { animation: fadeInUp 0.7s ease-out 0.3s both; }
        .glow { animation: heroGlow 4s ease-in-out infinite; }
        .feature-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .step-card { transition: transform 0.25s ease; }
        .step-card:hover { transform: scale(1.03); }
        .faq-item summary { list-style: none; cursor: pointer; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item[open] summary .faq-arrow { transform: rotate(180deg); }
        .faq-arrow { transition: transform 0.2s ease; }
        /* Cookie banner */
        #cookie-banner { position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

    <!-- Navegación -->
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md border-b border-slate-100 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="Kids Routine" class="w-9 h-9 rounded-xl shadow-sm">
                <span class="text-xl font-extrabold text-indigo-700">Kids Routine</span>
            </a>
            <div class="hidden md:flex items-center gap-6">
                <a href="#como-funciona" class="text-slate-500 hover:text-indigo-600 text-sm font-medium transition">Cómo funciona</a>
                <a href="#faq" class="text-slate-500 hover:text-indigo-600 text-sm font-medium transition">FAQ</a>
                <a href="{{ route('privacidad') }}" class="text-slate-500 hover:text-indigo-600 text-sm font-medium transition">Privacidad</a>
            </div>
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

            <!-- Contador social -->
            <div class="mt-12 grid grid-cols-3 gap-4 max-w-lg mx-auto">
                @foreach([
                    ['🏠', '200+', 'familias activas',    'from-indigo-50 to-blue-50',   'text-indigo-600'],
                    ['✅', '5.000+', 'tareas completadas', 'from-emerald-50 to-teal-50',  'text-emerald-600'],
                    ['🎮', '3.000+', 'partidas jugadas',   'from-purple-50 to-pink-50',   'text-purple-600'],
                ] as [$emoji, $num, $label, $bg, $color])
                <div class="bg-gradient-to-br {{ $bg }} rounded-2xl px-4 py-4 border border-white shadow-sm">
                    <div class="text-2xl mb-1">{{ $emoji }}</div>
                    <div class="text-2xl font-extrabold {{ $color }} leading-none">{{ $num }}</div>
                    <div class="text-xs text-slate-500 mt-1 leading-snug">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Cómo funciona -->
    <section id="como-funciona" class="py-24 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Empieza en 3 pasos</h2>
                <p class="text-slate-500 text-lg">Configura todo en menos de 5 minutos</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['📋', 'from-indigo-100 to-blue-50', '1. Crea las tareas', 'Define tareas diarias, semanales o puntuales para cada hijo. Decide cuántas monedas vale cada una y ponles una franja horaria si quieres.'],
                    ['<svg viewBox="0 0 20 20" style="width:1em;height:1em" fill="none"><circle cx="10" cy="10" r="9" fill="#F59E0B" stroke="#B45309" stroke-width="1.5"/><circle cx="10" cy="10" r="6.5" fill="#FCD34D"/><text x="10" y="14.2" text-anchor="middle" font-size="9" font-weight="900" fill="#92400E" font-family="sans-serif">★</text></svg>', 'from-amber-100 to-yellow-50', '2. El hijo las completa', 'Tu hijo entra con su PIN, ve sus tareas del día y las marca como completadas. Puede subir una foto como prueba si lo deseas.'],
                    ['🎁', 'from-pink-100 to-rose-50', '3. Elige su premio', 'Tú validas las tareas y él acumula monedas. Con ellas puede canjear las recompensas que tú hayas definido: tiempo de tablet, salidas, caprichos…'],
                ] as [$icon, $bg, $title, $desc])
                    <div class="step-card text-center bg-gradient-to-br {{ $bg }} rounded-3xl p-8 shadow-sm border border-white">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center text-4xl mx-auto mb-5 shadow-md">
                            {!! $icon !!}
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 mb-3">{{ $title }}</h3>
                        <p class="text-slate-600 leading-relaxed text-sm">{{ $desc }}</p>
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
                    ['🔐', 'Panel seguro para padres', 'Acceso con email y contraseña. Gestiona tareas, recompensas, validaciones y canjes desde un solo lugar.'],
                    ['🕹️', 'Modo niño con PIN', 'Los niños acceden con un PIN de 4 dígitos. Simple, seguro y diseñado a su nivel.'],
                    ['🔥', 'Rachas y logros', 'El sistema detecta rachas de días seguidos y desbloquea logros. La motivación no para.'],
                    ['🎮', 'Juegos educativos', 'Quiz, sumas, ahorcado, memoria y más. Jugar también da monedas.'],
                    ['⚡', 'Validación instantánea', 'Aprueba o rechaza tareas completadas con un solo clic desde tu panel.'],
                    ['👨‍👧‍👦', 'Múltiples hijos', 'Gestiona todos tus hijos y su ranking semanal desde un mismo panel unificado.'],
                    ['📊', 'Estadísticas', 'Gráficas de monedas ganadas, tareas completadas y juegos por semana.'],
                    ['🌙', 'Modo oscuro', 'Cuida la vista de noche. El modo oscuro está disponible en todos los paneles.'],
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

    <!-- Testimonios -->
    <section class="py-24 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Lo que dicen las familias</h2>
                <p class="text-slate-500 text-lg">Familias reales, resultados reales</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['Laura M.', 'Madre de 2 niños', 'Mis hijos ahora piden hacer las tareas porque quieren sus recompensas. Ha cambiado por completo la dinámica en casa.', '⭐⭐⭐⭐⭐'],
                    ['Carlos R.', 'Padre de 3 niños', 'Lo que más me gusta es que puedo validar desde el móvil en segundos. Muy práctico para familias ocupadas.', '⭐⭐⭐⭐⭐'],
                    ['Ana P.', 'Madre de 1 niña', 'Mi hija adora los juegos educativos. Aprende y se divierte a la vez. La función de rachas la tiene enganchada.', '⭐⭐⭐⭐⭐'],
                ] as [$nombre, $rol, $texto, $estrellas])
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <div class="text-lg mb-4">{{ $estrellas }}</div>
                    <p class="text-slate-700 text-sm leading-relaxed mb-5 italic">"{{ $texto }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold"
                             style="background: linear-gradient(135deg,#6366f1,#a855f7)">
                            {{ substr($nombre, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm">{{ $nombre }}</div>
                            <div class="text-xs text-slate-400">{{ $rol }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-24 px-6 bg-slate-50">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Preguntas frecuentes</h2>
                <p class="text-slate-500 text-lg">Todo lo que necesitas saber</p>
            </div>
            <div class="space-y-3">
                @foreach([
                    ['¿Es gratuito?', 'Sí, Kids Routine es completamente gratuito. No hay planes de pago ni funciones ocultas.'],
                    ['¿Cuántos hijos puedo añadir?', 'Puedes añadir tantos hijos como necesites. No hay límite.'],
                    ['¿Los niños necesitan email o cuenta?', 'No. Los niños acceden únicamente con un PIN de 4 dígitos que tú asignas. No necesitan email ni contraseña.'],
                    ['¿Puedo usar Kids Routine desde el móvil?', 'Sí. La aplicación está adaptada para móvil, tablet y escritorio. Además es una PWA: puedes instalarla en tu pantalla de inicio como una app nativa.'],
                    ['¿Las fotos de perfil se guardan de forma segura?', 'Sí. Las imágenes se procesan y almacenan en base64 directamente en la base de datos, sin almacenamiento externo.'],
                    ['¿Cómo funciona el sistema de rachas?', 'Cada día que un hijo completa al menos una tarea, su racha aumenta. A los 7 y 30 días consecutivos recibe un bonus de monedas automático.'],
                    ['¿Puedo personalizar las recompensas?', 'Completamente. Tú defines el nombre, descripción y coste en monedas de cada recompensa. Pueden ser tiempo de pantalla, salidas al parque, caprichos... lo que tú decidas.'],
                    ['¿Qué pasa si un hijo falla el PIN varias veces?', 'Tras 3 intentos fallidos, la cuenta del hijo queda bloqueada temporalmente. El padre puede verlo en el panel y desbloquearlo.'],
                ] as [$pregunta, $respuesta])
                <details class="faq-item bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <summary class="flex items-center justify-between gap-4 p-5 font-bold text-gray-800">
                        {{ $pregunta }}
                        <svg class="faq-arrow w-5 h-5 text-slate-400 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </summary>
                    <div class="px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-50 pt-3">
                        {{ $respuesta }}
                    </div>
                </details>
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

    <footer class="py-10 px-6 border-t border-slate-100">
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-400">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="" class="w-6 h-6 rounded-lg">
                <span class="font-bold text-slate-600">Kids Routine</span>
                <span>· TFG DAW 2026 · Marcos Pérez</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="#faq" class="hover:text-indigo-600 transition">FAQ</a>
                <a href="{{ route('privacidad') }}" class="hover:text-indigo-600 transition">Política de privacidad</a>
                <a href="{{ route('terminos') }}" class="hover:text-indigo-600 transition">Términos de uso</a>
            </div>
        </div>
    </footer>

    <!-- Cookie Banner -->
    <div id="cookie-banner"
         class="bg-slate-900 text-white px-6 py-4 shadow-2xl"
         style="display:none">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center gap-4 justify-between">
            <p class="text-sm text-slate-300">
                Usamos cookies esenciales para el funcionamiento de la aplicación.
                No usamos cookies de seguimiento ni publicidad.
                <a href="{{ route('privacidad') }}" class="underline text-indigo-300 hover:text-indigo-200">Más info</a>
            </p>
            <button onclick="aceptarCookies()"
                    class="flex-shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition">
                Entendido
            </button>
        </div>
    </div>

    <script>
        function aceptarCookies() {
            localStorage.setItem('cookies_aceptadas', '1');
            document.getElementById('cookie-banner').style.display = 'none';
        }
        if (!localStorage.getItem('cookies_aceptadas')) {
            document.getElementById('cookie-banner').style.display = 'block';
        }
    </script>
</body>
</html>
