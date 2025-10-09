<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
    <input type="text" name="name" id="name" value="{{ old('name', $role->name ?? '') }}" 
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div class="flex justify-end pt-6">
    <a href="{{ route('roles.index') }}" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded mr-2">
        Cancelar
    </a>
    <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
        Guardar
    </button>
</div>