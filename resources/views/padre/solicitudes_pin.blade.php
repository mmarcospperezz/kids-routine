@extends('layouts.app')

@section('title', 'Solicitudes de cambio de PIN')

@section('content')

<div class="mb-5">
    <h1 class="text-2xl font-extrabold text-gray-900">🔑 Solicitudes de cambio de PIN</h1>
    <p class="text-slate-500 text-sm mt-1">Tus hijos pueden solicitar cambiar su PIN desde su panel</p>
</div>

@if(session('exito'))
    <div class="bg-green-100 border border-green-200 text-green-800 rounded-2xl px-4 py-3 text-sm mb-5">
        {{ session('exito') }}
    </div>
@endif

@if($solicitudes->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <span class="text-5xl block mb-3">✅</span>
        <p class="text-gray-600 font-bold">No hay solicitudes pendientes</p>
    </div>
@else
    <div class="space-y-3 max-w-2xl">
        @foreach($solicitudes as $sol)
        <div class="bg-white rounded-2xl border border-amber-200 shadow-sm p-5 flex items-center gap-4">
            @if($sol->hijo->avatarUrl())
                <img src="{{ $sol->hijo->avatarUrl() }}" class="w-12 h-12 rounded-xl object-cover">
            @else
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-extrabold text-lg"
                     style="background: {{ $sol->hijo->avatarColor() }}">
                    {{ mb_strtoupper(mb_substr($sol->hijo->nombre, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1">
                <p class="font-extrabold text-gray-800">{{ $sol->hijo->nombre }}</p>
                <p class="text-xs text-slate-400">Solicitado {{ $sol->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('padre.solicitudes_pin.aprobar', $sol) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-4 py-2 rounded-xl transition">
                        ✓ Aprobar
                    </button>
                </form>
                <form action="{{ route('padre.solicitudes_pin.rechazar', $sol) }}" method="POST">
                    @csrf
                    <button class="bg-red-100 hover:bg-red-200 text-red-700 text-sm font-bold px-4 py-2 rounded-xl transition">
                        ✗ Rechazar
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
