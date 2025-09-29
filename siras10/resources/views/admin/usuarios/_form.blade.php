<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="nombres" class="block text-sm font-medium text-gray-700">Nombres</label>
        <input type="text" name="nombres" id="nombres" value="{{ old('nombres', $usuario->nombres ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('nombres')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
        <input type="text" name="apellidoPaterno" id="apellidoPaterno" value="{{ old('apellidoPaterno', $usuario->apellidoPaterno ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('apellidoPaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
        <input type="text" name="apellidoMaterno" id="apellidoMaterno" value="{{ old('apellidoMaterno', $usuario->apellidoMaterno ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('apellidoMaterno')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="runUsuario" class="block text-sm font-medium text-gray-700">RUN</label>
        <input type="text" name="runUsuario" id="runUsuario" value="{{ old('runUsuario', $usuario->runUsuario ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('runUsuario')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="nombreUsuario" class="block text-sm font-medium text-gray-700">Nombre de Usuario</label>
        <input type="text" name="nombreUsuario" id="nombreUsuario" value="{{ old('nombreUsuario', $usuario->nombreUsuario ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('nombreUsuario')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
        <input type="email" name="correo" id="correo" value="{{ old('correo', $usuario->correo ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('correo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="contrasenia" class="block text-sm font-medium text-gray-700">Contraseña</label>
        <input type="password" name="contrasenia" id="contrasenia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" {{ isset($usuario) ? '' : 'required' }}>
        @isset($usuario)<small class="text-gray-500">Dejar en blanco para no cambiar</small>@endisset
        @error('contrasenia')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="contrasenia_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
        <input type="password" name="contrasenia_confirmation" id="contrasenia_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700">Roles</label>
    <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($roles as $role)
            <label class="inline-flex items-center">
                <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    @if(isset($usuario) && $usuario->roles->contains($role->id)) checked @endif
                >
                <span class="ms-2 text-sm text-gray-600">{{ $role->name }}</span>
            </label>
        @endforeach
    </div>
    @error('roles')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div class="flex justify-end pt-6">
    <a href="{{ route('usuarios.index') }}" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded mr-2">
        Cancelar
    </a>
    <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
        Guardar
    </button>
</div>