<div class="space-y-4">
    <div>
        <label for="nombreTipo" class="block text-sm font-medium text-gray-700">Nombre del Tipo</label>
        <input type="text" name="nombreTipo" id="nombreTipo" value="{{ old('nombreTipo', $tipo->nombreTipo ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               required>
        @error('nombreTipo')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex justify-end pt-4">
        <a href="{{ route('tipos-centro-formador.index') }}" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded mr-2">
            Cancelar
        </a>
        <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
            Guardar
        </button>
    </div>
</div>