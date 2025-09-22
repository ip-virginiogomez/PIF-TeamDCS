<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="runAlumno" class="block text-sm font-medium text-gray-700">RUN</label>
        <input type="text" name="runAlumno" id="runAlumno" value="{{ old('runAlumno', $alumno->runAlumno ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('runAlumno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="nombres" class="block text-sm font-medium text-gray-700">Nombres</label>
        <input type="text" name="nombres" id="nombres" value="{{ old('nombres', $alumno->nombres ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('nombres')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
        <input type="text" name="apellidoPaterno" id="apellidoPaterno" value="{{ old('apellidoPaterno', $alumno->apellidoPaterno ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('apellidoPaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
        <input type="text" name="apellidoMaterno" id="apellidoMaterno" value="{{ old('apellidoMaterno', $alumno->apellidoMaterno ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('apellidoMaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
        <input type="email" name="correo" id="correo" value="{{ old('correo', $alumno->correo ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('correo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="fechaNacto" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
        <input type="date" name="fechaNacto" id="fechaNacto" value="{{ old('fechaNacto', $alumno->fechaNacto ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('fechaNacto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="foto" class="block text-sm font-medium text-gray-700">Foto del Estudiante</label>
        <input type="file" name="foto" id="foto" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
        @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

        @isset($alumno->foto)
            <div class="mt-4">
                <p class="text-sm text-gray-600">Foto Actual:</p>
                <img src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto del estudiante" class="h-20 w-20 object-cover rounded-full">
            </div>
        @endisset
    </div>
    <div>
        <label for="acuerdo" class="block text-sm font-medium text-gray-700">Acuerdo de Confidencialidad</label>
        <input type="file" name="acuerdo" id="acuerdo" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
        @error('acuerdo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        
        @isset($alumno->acuerdo)
            <div class="mt-4">
                <a href="{{ asset('storage/' . $alumno->acuerdo) }}" target="_blank" class="text-blue-500 hover:underline">Ver Acuerdo Actual</a>
            </div>
        @endisset
    </div>
</div>

<div class="flex justify-end pt-6">
    <a href="{{ route('alumnos.index') }}" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded mr-2">
        Cancelar
    </a>
    <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
        Guardar
    </button>
</div>