<div class="space-y-4">
    <div>
        <label for="nombreCentroFormador" class="block text-sm font-medium text-gray-700">Nombre del Centro</label>
        <input type="text" name="nombreCentroFormador" id="nombreCentroFormador" value="{{ old('nombreCentroFormador', $centro->nombreCentroFormador ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               required>
        @error('nombreCentroFormador')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="idTipoCentroFormador" class="block text-sm font-medium text-gray-700">Tipo de Centro</label>
        <select name="idTipoCentroFormador" id="idTipoCentroFormador" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="">Seleccione un tipo</option>
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo->idTipoCentroFormador }}" 
                    {{ old('idTipoCentroFormador', $centro->idTipoCentroFormador ?? '') == $tipo->idTipoCentroFormador ? 'selected' : '' }}>
                    {{ $tipo->nombreTipo }}
                </option>
            @endforeach
        </select>
        @error('idTipoCentroFormador')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end pt-4">
        <a href="{{ route('centros-formadores.index') }}" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded mr-2">
            Cancelar
        </a>
        <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
            Guardar
        </button>
    </div>
</div>