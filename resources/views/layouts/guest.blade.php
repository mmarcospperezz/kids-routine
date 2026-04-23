<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KidsRoutine')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-2xl font-bold text-indigo-600">
                <span class="text-3xl">⭐</span>
                <span>KidsRoutine</span>
            </a>
            <p class="text-gray-500 text-sm mt-1">Convierte las rutinas en aventuras</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>
