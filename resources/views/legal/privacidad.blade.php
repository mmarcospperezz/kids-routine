<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad — Kids Routine</title>
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
            <span class="text-slate-500 text-sm">Política de Privacidad</span>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 pt-28 pb-20">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Política de Privacidad</h1>
        <p class="text-slate-400 text-sm mb-10">Última actualización: mayo de 2026</p>

        <div class="prose prose-slate max-w-none space-y-8 text-sm leading-relaxed">

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">1. Responsable del tratamiento</h2>
                <p>El responsable del tratamiento de los datos recogidos en esta aplicación es <strong>Marcos Pérez</strong>, en el marco de su Trabajo de Fin de Grado del Ciclo Superior de Desarrollo de Aplicaciones Web (DAW), curso 2025-2026.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">2. Datos que recogemos</h2>
                <ul class="list-disc pl-5 space-y-1 text-slate-600">
                    <li><strong>Datos de registro:</strong> nombre y dirección de correo electrónico del padre/tutor.</li>
                    <li><strong>Datos de hijos:</strong> nombre, edad, avatar (imagen de perfil) y PIN de acceso cifrado. No se recoge ningún dato identificativo real del menor (sin email, sin DNI, sin apellidos).</li>
                    <li><strong>Datos de uso:</strong> tareas completadas, monedas ganadas, canjes solicitados, partidas de juegos. Solo se utilizan para el funcionamiento de la aplicación.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">3. Finalidad del tratamiento</h2>
                <p class="text-slate-600">Los datos se recogen exclusivamente para permitir el funcionamiento de la aplicación: gestión de tareas, recompensas y juegos educativos dentro del núcleo familiar. No se utilizan con ninguna finalidad comercial ni se ceden a terceros.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">4. Base legal</h2>
                <p class="text-slate-600">El tratamiento se basa en el consentimiento del usuario (art. 6.1.a RGPD) al registrarse en la plataforma y aceptar estos términos.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">5. Almacenamiento y seguridad</h2>
                <p class="text-slate-600">Los datos se almacenan en una base de datos MySQL alojada en Railway. Las contraseñas se almacenan cifradas con bcrypt. Las imágenes de avatar se procesan y almacenan como JPEG en base64 directamente en la base de datos. El PIN de los hijos se almacena cifrado con bcrypt.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">6. Cookies</h2>
                <p class="text-slate-600">Esta aplicación utiliza únicamente cookies de sesión estrictamente necesarias para el funcionamiento (autenticación). No se utilizan cookies de seguimiento, analítica ni publicidad.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">7. Derechos del usuario</h2>
                <p class="text-slate-600">Tienes derecho a acceder, rectificar y suprimir tus datos. Puedes eliminar tu cuenta desde el panel (Configuración → Eliminar cuenta), lo que borrará todos tus datos y los de tus hijos de forma permanente.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">8. Menores de edad</h2>
                <p class="text-slate-600">Los perfiles de hijo no contienen datos identificativos reales. El acceso de los menores siempre está supervisado por el padre/tutor registrado, que es el responsable del consentimiento.</p>
            </section>

            <section>
                <h2 class="text-xl font-extrabold text-gray-800 mb-3">9. Contacto</h2>
                <p class="text-slate-600">Para cualquier consulta sobre privacidad puedes contactar a través del repositorio del proyecto en GitHub.</p>
            </section>
        </div>

        <div class="mt-12 pt-8 border-t border-slate-200">
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition">← Volver al inicio</a>
        </div>
    </main>
</body>
</html>
