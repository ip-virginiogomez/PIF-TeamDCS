@csrf
<div class="space-y-4">
    <div>
        <label for="nombreCarrera" class="block text-sm font-medium text-gray-700">Nombre de la Carrera</label>
        <input type="text" name="nombreCarrera" id="nombreCarrera" value="{{ old('nombreCarrera', $carrera->nombreCarrera ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('nombreCarrera')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="fechaCreacion" class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
        <input type="date" name="fechaCreacion" id="fechaCreacion" value="{{ old('fechaCreacion', $carrera->fechaCreacion ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('fechaCreacion')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="flex justify-end mt-6">
    <a href="{{ route('carreras.index') }}" class="mr-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Cancelar
    </a>
    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
        Guardar
    </button>
</div>