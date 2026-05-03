@extends('layouts.app')

@section('title', 'Nueva tarea')

@section('content')
<div class="mb-5">
    <a href="{{ route('padre.tareas.index') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 text-sm font-medium transition">
        ← Tareas
    </a>
</div>

<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Nueva tarea ✅</h1>
        <p class="text-slate-500 text-sm mt-1">Define una tarea para uno de tus hijos</p>
    </div>

    {{-- Plantillas rápidas --}}
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-4">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Plantillas rápidas</p>
        <div class="flex flex-wrap gap-2" id="plantillas">
            @foreach(\App\Models\Tarea::categoriasPredefinidas() as $nombre => $icono)
                <button type="button"
                        onclick="usarPlantilla('{{ $nombre }}', '{{ $icono }}')"
                        class="bg-white border border-slate-200 hover:border-indigo-400 hover:bg-indigo-50 text-slate-700 text-xs font-medium px-3 py-1.5 rounded-xl transition">
                    {{ $icono }} {{ $nombre }}
                </button>
            @endforeach
            @foreach([
                ['Hacer la cama','🛏️','Higiene personal'],
                ['Recoger la habitación','🧹','Orden y limpieza'],
                ['Ducharse','🚿','Higiene personal'],
                ['Poner la mesa','🍽️','Tareas del hogar'],
                ['Leer 20 minutos','📖','Lectura'],
                ['Sacar a pasear al perro','🐕','Mascotas'],
            ] as [$titulo, $icono, $cat])
                <button type="button"
                        onclick="usarPlantilla('{{ $titulo }}', '{{ $icono }}', '{{ $cat }}')"
                        class="bg-white border border-slate-200 hover:border-indigo-400 hover:bg-indigo-50 text-slate-700 text-xs font-medium px-3 py-1.5 rounded-xl transition">
                    {{ $icono }} {{ $titulo }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('padre.tareas.store') }}" method="POST" class="space-y-5" id="formTarea">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Asignar a *</label>
                <select name="id_hijo" required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                    <option value="">— Selecciona un hijo —</option>
                    @foreach($hijos as $hijo)
                        <option value="{{ $hijo->id_hijo }}"
                                {{ old('id_hijo', request('hijo')) == $hijo->id_hijo ? 'selected' : '' }}>
                            {{ $hijo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_hijo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Título de la tarea *</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="200"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white"
                       placeholder="Ej: Hacer la cama">
                @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Descripción (opcional)</label>
                <textarea name="descripcion" rows="2" maxlength="500"
                          class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white resize-none"
                          placeholder="Instrucciones adicionales...">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Monedas de recompensa *</label>
                <div class="flex items-center gap-3">
                    <span class="text-2xl"><x-moneda /></span>
                    <input type="number" name="monedas_recompensa" value="{{ old('monedas_recompensa', 5) }}" required min="0" max="9999"
                           class="flex-1 border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                </div>
                @error('monedas_recompensa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Categoría</label>
                    <select name="categoria"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white" id="campoCat">
                        <option value="">— Sin categoría —</option>
                        @foreach(\App\Models\Tarea::categoriasPredefinidas() as $nombre => $icono)
                            <option value="{{ $nombre }}" {{ old('categoria') == $nombre ? 'selected' : '' }}>
                                {{ $icono }} {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Franja horaria</label>
                    <select name="franja"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-slate-50 hover:bg-white">
                        <option value="CUALQUIERA" {{ old('franja', 'CUALQUIERA') == 'CUALQUIERA' ? 'selected' : '' }}>🕐 Cualquier hora</option>
                        <option value="MAÑANA"     {{ old('franja') == 'MAÑANA' ? 'selected' : '' }}>🌅 Mañana</option>
                        <option value="TARDE"      {{ old('franja') == 'TARDE' ? 'selected' : '' }}>☀️ Tarde</option>
                        <option value="NOCHE"      {{ old('franja') == 'NOCHE' ? 'selected' : '' }}>🌙 Noche</option>
                    </select>
                </div>
            </div>

            <!-- Tipo de tarea -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tipo de tarea *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 border-2 border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-300 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                        <input type="radio" name="tipo" value="PUNTUAL" {{ old('tipo', 'PUNTUAL') == 'PUNTUAL' ? 'checked' : '' }}
                               class="text-indigo-600 w-4 h-4" onchange="toggleRecurrencia()">
                        <div>
                            <p class="text-sm font-bold text-gray-800">📌 Puntual</p>
                            <p class="text-xs text-slate-500">Una sola vez</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 border-2 border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-300 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                        <input type="radio" name="tipo" value="RECURRENTE" {{ old('tipo') == 'RECURRENTE' ? 'checked' : '' }}
                               class="text-indigo-600 w-4 h-4" onchange="toggleRecurrencia()">
                        <div>
                            <p class="text-sm font-bold text-gray-800">🔄 Recurrente</p>
                            <p class="text-xs text-slate-500">Se repite</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Opciones de recurrencia -->
            <div id="seccionRecurrencia" class="{{ old('tipo') == 'RECURRENTE' ? '' : 'hidden' }} space-y-4 bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Frecuencia *</label>
                    <div class="space-y-2">
                        @foreach(['DIARIA' => ['🌅', 'Todos los días'], 'SEMANAL' => ['📆', 'Una vez a la semana'], 'PERSONALIZADA' => ['🗓️', 'Días personalizados']] as $val => [$icon, $label])
                            <label class="flex items-center gap-3 bg-white border-2 border-slate-200 rounded-xl px-4 py-2.5 cursor-pointer hover:border-indigo-300 transition has-[:checked]:border-indigo-500">
                                <input type="radio" name="recurrencia" value="{{ $val }}"
                                       {{ old('recurrencia') == $val ? 'checked' : '' }}
                                       class="text-indigo-600 w-4 h-4" onchange="toggleDias()">
                                <span class="text-base">{{ $icon }}</span>
                                <span class="text-sm font-medium text-gray-800">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('recurrencia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Días personalizados -->
                <div id="seccionDias" class="{{ old('recurrencia') == 'PERSONALIZADA' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Selecciona los días</label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'] as $num => $letra)
                            <label class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-slate-200 cursor-pointer font-bold text-sm hover:border-indigo-400 has-[:checked]:bg-indigo-600 has-[:checked]:text-white has-[:checked]:border-indigo-600 transition">
                                <input type="checkbox" name="dias[]" value="{{ $num }}" class="hidden"
                                       {{ in_array($num, old('dias', [])) ? 'checked' : '' }}>
                                {{ $letra }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Fecha de fin (opcional)</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-sm">
                    Crear tarea →
                </button>
                <a href="{{ route('padre.tareas.index') }}"
                   class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function usarPlantilla(titulo, icono, categoria) {
    document.querySelector('input[name="titulo"]').value = icono + ' ' + titulo;
    if (categoria) {
        const sel = document.getElementById('campoCat');
        for (let i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === categoria) { sel.selectedIndex = i; break; }
        }
    }
}
function toggleRecurrencia() {
    const tipo = document.querySelector('input[name="tipo"]:checked')?.value;
    document.getElementById('seccionRecurrencia').classList.toggle('hidden', tipo !== 'RECURRENTE');
}
function toggleDias() {
    const rec = document.querySelector('input[name="recurrencia"]:checked')?.value;
    document.getElementById('seccionDias').classList.toggle('hidden', rec !== 'PERSONALIZADA');
}
document.getElementById('formTarea').addEventListener('submit', function(e) {
    const diasCheck = document.querySelectorAll('input[name="dias[]"]:checked');
    if (diasCheck.length > 0) {
        const vals = Array.from(diasCheck).map(c => c.value).join(',');
        let hidden = document.querySelector('input[name="dias_semana"]');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'dias_semana';
            this.appendChild(hidden);
        }
        hidden.value = vals;
    }
});
</script>
@endsection
