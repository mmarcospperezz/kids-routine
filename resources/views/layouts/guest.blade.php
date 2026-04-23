<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kids Routine')</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33%       { transform: translateY(-18px) rotate(6deg); }
            66%       { transform: translateY(-9px) rotate(-4deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseSoft {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50%       { opacity: 1;   transform: scale(1.08); }
        }
        .f1 { animation: float 7s   ease-in-out infinite; }
        .f2 { animation: float 9s   ease-in-out 1.2s infinite; }
        .f3 { animation: float 6s   ease-in-out 2.5s infinite; }
        .f4 { animation: float 8.5s ease-in-out 0.8s infinite; }
        .f5 { animation: float 10s  ease-in-out 3.2s infinite; }
        .f6 { animation: float 7.5s ease-in-out 4.5s infinite; }
        .fade-up  { animation: fadeInUp 0.65s ease-out both; }
        .fade-up2 { animation: fadeInUp 0.65s ease-out 0.18s both; }
        .pulse-soft { animation: pulseSoft 3s ease-in-out infinite; }
        .input-field {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            background: #fafafa;
        }
        .input-field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            background: #fff;
        }
        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            font-weight: 700;
            padding: 13px 0;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            font-size: 15px;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(99,102,241,0.35);
        }
        .btn-primary:active { transform: scale(0.98); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-violet-100 via-indigo-50 to-sky-100 flex items-center justify-center p-4 relative">

    <!-- Blobs decorativos -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute -top-40 -left-40 w-[480px] h-[480px] bg-purple-200/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-[480px] h-[480px] bg-indigo-200/40 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-pink-100/50 rounded-full blur-2xl"></div>
        <span class="f1 absolute top-[7%]  left-[7%]  text-5xl opacity-25 select-none">⭐</span>
        <span class="f2 absolute top-[10%] right-[8%] text-5xl opacity-20 select-none">🌈</span>
        <span class="f3 absolute top-[42%] left-[4%]  text-4xl opacity-25 select-none">🎈</span>
        <span class="f4 absolute bottom-[14%] left-[10%] text-4xl opacity-20 select-none">✨</span>
        <span class="f5 absolute bottom-[8%]  right-[7%] text-5xl opacity-25 select-none">🌟</span>
        <span class="f6 absolute top-[58%]  right-[5%]  text-4xl opacity-20 select-none">🎯</span>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Logo -->
        <div class="fade-up text-center mb-8">
            <div class="inline-block relative mb-4">
                <img src="{{ asset('images/logo.svg') }}" alt="Kids Routine" class="w-20 h-20 rounded-3xl shadow-xl drop-shadow-lg">
                <div class="absolute inset-0 rounded-3xl ring-4 ring-purple-300/30 animate-ping"></div>
            </div>
            <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Kids Routine
            </h1>
            <p class="text-purple-400 text-sm mt-1">Tu app de rutinas familiar ✨</p>
        </div>

        <!-- Tarjeta -->
        <div class="fade-up2 bg-white/88 backdrop-blur-sm rounded-3xl shadow-2xl p-8 border border-white/60">
            @yield('content')
        </div>

        <p class="text-center text-purple-300/70 text-xs mt-6">© {{ date('Y') }} Kids Routine · TFG DAW</p>
    </div>
</body>
</html>
