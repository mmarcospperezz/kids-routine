<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¿Quién eres? — Kids Routine</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            40%       { transform: translateY(-22px) rotate(8deg); }
            70%       { transform: translateY(-10px) rotate(-5deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(32px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes bounceIn {
            0%   { opacity: 0; transform: scale(0.5); }
            60%  { transform: scale(1.1); }
            80%  { transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        .f1 { animation: float 7s ease-in-out infinite; }
        .f2 { animation: float 9s ease-in-out 1.5s infinite; }
        .f3 { animation: float 6s ease-in-out 3s infinite; }
        .f4 { animation: float 8s ease-in-out 0.5s infinite; }
        .f5 { animation: float 10s ease-in-out 2s infinite; }
        .f6 { animation: float 7.5s ease-in-out 4s infinite; }
        .title-in { animation: bounceIn 0.7s ease-out both 0.1s; }
        .card-in-1 { animation: fadeInUp 0.5s ease-out both 0.2s; }
        .card-in-2 { animation: fadeInUp 0.5s ease-out both 0.3s; }
        .card-in-3 { animation: fadeInUp 0.5s ease-out both 0.4s; }
        .card-in-4 { animation: fadeInUp 0.5s ease-out both 0.5s; }
        .child-card {
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.25s ease;
        }
        .child-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0,0,0,0.18);
        }
        .child-card:active { transform: scale(0.97); }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden flex flex-col items-center justify-center p-6"
      style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 35%, #ec4899 70%, #f97316 100%);">

    <!-- Decoraciones -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-white/5 rounded-full"></div>
        <span class="f1 absolute top-[6%]  left-[8%]  text-5xl opacity-30 select-none">⭐</span>
        <span class="f2 absolute top-[10%] right-[9%] text-6xl opacity-25 select-none">🌟</span>
        <span class="f3 absolute top-[45%] left-[3%]  text-4xl opacity-25 select-none">🎈</span>
        <span class="f4 absolute bottom-[12%] left-[10%] text-5xl opacity-25 select-none">✨</span>
        <span class="f5 absolute bottom-[8%]  right-[7%]  text-6xl opacity-20 select-none">🎉</span>
        <span class="f6 absolute top-[60%]  right-[4%]  text-4xl opacity-25 select-none">🎯</span>
    </div>

    <div class="relative z-10 w-full max-w-md">

        <!-- Título -->
        <div class="title-in text-center mb-8">
            <div class="inline-block mb-4">
                <img src="{{ asset('images/logo.svg') }}" alt="Kids Routine" class="w-20 h-20 rounded-3xl shadow-2xl border-2 border-white/30">
            </div>
            <h1 class="text-4xl font-extrabold text-white drop-shadow-lg">¿Quién eres?</h1>
            <p class="text-white/80 text-base mt-2">Elige tu perfil para empezar</p>
        </div>

        <!-- Cards de hijos -->
        @if($hijos->isEmpty())
            <div class="card-in-1 bg-white/20 backdrop-blur-sm rounded-3xl p-8 text-center border border-white/30">
                <span class="text-5xl block mb-3">😔</span>
                <p class="text-white font-semibold mb-2">Aún no hay perfiles</p>
                <p class="text-white/70 text-sm">Pide a tu padre o madre que añada un perfil</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($hijos as $index => $hijo)
                    <form action="{{ route('hijo.pin') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_hijo" value="{{ $hijo->id_hijo }}">
                        <button type="submit"
                                class="child-card card-in-{{ min($index + 1, 4) }} w-full bg-white/95 backdrop-blur rounded-2xl p-5 flex items-center gap-4 text-left shadow-xl border border-white/50">
                            <!-- Avatar -->
                            @if($hijo->avatarUrl())
                                <img src="{{ $hijo->avatarUrl() }}" alt="{{ $hijo->nombre }}"
                                     class="w-16 h-16 rounded-2xl object-cover shadow-md flex-shrink-0 border-2 border-white/50">
                            @else
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-black text-3xl text-white shadow-md flex-shrink-0"
                                     style="background: {{ $hijo->avatarColor() }}">
                                    {{ mb_strtoupper(mb_substr($hijo->nombre, 0, 1)) }}
                                </div>
                            @endif
                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <p class="font-extrabold text-gray-800 text-xl leading-tight">{{ $hijo->nombre }}</p>
                                <p class="text-gray-500 text-sm mt-0.5">{{ $hijo->edad }} años</p>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-base"><x-moneda /></span>
                                    <span class="font-bold text-yellow-600 text-sm">{{ $hijo->monedas }} monedas</span>
                                </div>
                            </div>
                            <!-- Flecha -->
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-xl shadow-md flex-shrink-0"
                                 style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                →
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>
        @endif

    </div>
</body>
</html>
