<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos de Uso — Kids Routine</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md border-b border-slate-100 z-50 shadow-sm">
        <div class="max-w-4xl mx-auto px-6 h-14 flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                <img src="{{ asset('images/logo.svg') }}" alt="" class="w-7 h-7 rounded-lg">
                <span class="font-extrabold">Kids Routine</span>
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-500 text-sm">Términos de Uso</span>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 pt-28 pb-20">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Términos de Uso</h1>
        <p class="text-slate-400 text-sm mb-10">Última actualización: mayo de 2026</p>

        <div class="space-y-8 text-sm leading-relaxed text-slate-600">

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">1. Aceptación</h2>
                <p>Al registrarte en Kids Routine aceptas estos Términos de Uso. Si no estás de acuerdo, no utilices la aplicación.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">2. Descripción del servicio</h2>
                <p>Kids Routine es una aplicación web educativa orientada a familias que permite gestionar tareas, recompensas y juegos educativos para hijos. Es un proyecto académico (TFG DAW 2026) y se ofrece de forma gratuita sin garantías de disponibilidad continua.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">3. Uso adecuado</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li>La aplicación está destinada al uso familiar y educativo.</li>
                    <li>Queda prohibido el uso para actividades ilegales, la creación de contenido ofensivo o el acceso no autorizado a cuentas ajenas.</li>
                    <li>Las recompensas y monedas son virtuales y no tienen ningún valor monetario real.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">4. Cuentas y responsabilidad</h2>
                <p>El padre/tutor que se registra es responsable de toda la actividad realizada bajo su cuenta y las cuentas de sus hijos. Debes mantener tu contraseña segura y notificar cualquier uso no autorizado.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">5. Contenido generado por el usuario</h2>
                <p>Los avatares e imágenes que subas a la plataforma deben ser apropiados. No está permitido subir contenido que vulnere derechos de terceros o que sea inapropiado para menores.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">6. Disponibilidad del servicio</h2>
                <p>Al ser un proyecto académico, el servicio puede sufrir interrupciones sin previo aviso. No garantizamos disponibilidad continua ni almacenamiento permanente de los datos.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">7. Eliminación de cuenta</h2>
                <p>Puedes eliminar tu cuenta en cualquier momento desde el panel de configuración. La eliminación es definitiva e irreversible: todos tus datos y los de tus hijos serán borrados permanentemente.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">8. Limitación de responsabilidad</h2>
                <p>Kids Routine se proporciona "tal cual", sin garantías de ningún tipo. El responsable no se hace cargo de pérdidas de datos ni de perjuicios derivados del uso o imposibilidad de uso de la aplicación.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">9. Modificaciones</h2>
                <p>Estos términos pueden actualizarse. Continuando el uso de la aplicación tras la actualización, aceptas los nuevos términos.</p>
            </section>
        </div>

        <div class="mt-12 pt-8 border-t border-slate-200">
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition">← Volver al inicio</a>
        </div>
    </main>
</body>
</html>
