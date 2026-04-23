@extends('layouts.app')

@section('title', 'Nueva tarea')

@section('content')
<div class="mb-6">
    <a href="{{ route('padre.tareas.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm flex items-center gap-1">
        ← Volver a tareas
    </a>
</div>

<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Nueva tarea</h1>
    <p class="text-gray-500 text-sm mb-6">Define una tarea para uno de tus hijos</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('padre.tareas.store') }}" method="POST" class="space-y-5" id="formTarea">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asignar a *</label>
                <select name="id_hijo" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Selecciona un hijo</option>
                    @foreach($hijos as $hijo)
                        <option value="{{ $hijo->id_hijo }}" {{ old('id_hijo', request('hijo')) == $hijo->id_hijo ? 'selected' : '' }}>
                            {{ $hijo->avatarEmoji() }} {{ $hijo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_hijo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título de la tarea *</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="200"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: Hacer la cama">
                @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción (opcional)</label>
                <textarea name="descripcion" rows="2" maxlength="500"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                          placeholder="Instrucciones adicionales...">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monedas de recompensa *</label>
                <input type="number" name="monedas_recompensa" value="{{ old('monedas_recompensa', 5) }}" required min="0" max="9999"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('monedas_recompensa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de tarea *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-400 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                        <input type="radio" name="tipo" value="PUNTUAL" {{ old('tipo', 'PUNTUAL') == 'PUNTUAL' ? 'checked' : '' }} class="text-indigo-600" onchange="toggleRecurrencia()">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Puntual</p>
                            <p class="text-xs text-gray-500">Una sola vez</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-400 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                        <input type="radio" name="tipo" value="RECURRENTE" {{ old('tipo') == 'RECURRENTE' ? 'checked' : '' }} class="text-indigo-600" onchange="toggleRecurrencia()">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Recurrente</p>
                            <p class="text-xs text-gray-500">Se repite</p>
                        </div>
                    </label>
                </div>
            </div>

            <div id="seccionRecurrencia" class="{{ old('tipo') == 'RECURRENTE' ? '' : 'hidden' }} space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frecuencia *</label>
                    <div class="space-y-2">
                        @foreach(['DIARIA' => 'Todos los días', 'SEMANAL' => 'Una vez a la semana (lunes)', 'PERSONALIZADA' => 'Días personalizados'] as $val => $label)
                        <label class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-400 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="recurrencia" value="{{ $val }}" {{ old('recurrencia') == $val ? 'checked' : '' }} class="text-indigo-600" onchange="toggleDias()">
                            <span class="text-sm text-gray-800">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('recurrencia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div id="seccionDias" class="{{ old('recurrencia') == 'PERSONALIZADA' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona los días</label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'] as $num => $letra)
                        <label class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 cursor-pointer font-medium text-sm hover:border-indigo-400 has-[:checked]:bg-indigo-600 has-[:checked]:text-white has-[:checked]:border-indigo-600">
                            <input type="checkbox" name="dias[]" value="{{ $num }}" class="hidden"
                                {{ in_array($num, old('dias', [])) ? 'checked' : '' }}>
                            {{ $letra }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de fin (opcional)</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition">
                    Crear tarea
                </button>
                <a href="{{ route('padre.tareas.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-xl transition text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRecurrencia() {
    const tipo = document.querySelector('input[name="tipo"]:checked')?.value;
    document.getElementById('seccionRecurrencia').classList.toggle('hidden', tipo !== 'RECURRENTE');
}
function toggleDias() {
    const rec = document.querySelector('input[name="recurrencia"]:checked')?.value;
    document.getElementById('seccionDias').classList.toggle('hidden', rec !== 'PERSONALIZADA');
}

// Handle dias_semana as comma-separated on submit
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
