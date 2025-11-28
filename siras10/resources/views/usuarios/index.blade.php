<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <button 
                data-modal-target="usuarioModal" 
                data-modal-toggle="usuarioModal" 
                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nuevo Usuario
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div id="tabla-container">
                        @include('usuarios._tabla', [
                            'usuarios' => $usuarios,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-crud-modal 
        modalId="usuarioModal" 
        formId="usuarioForm" 
        title="Gestión de Usuario"
        primaryKey="runUsuario">
        
        {{-- RUN --}}
        <div class="mb-4">
            <label for="runUsuario" class="block text-sm font-medium text-gray-700">RUN *</label>
            <input type="text" id="runUsuario" name="runUsuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ej: 12345678-9" required>
            <div id="run-help-text" class="text-xs text-amber-600 mt-1 hidden">
                El RUN no puede modificarse al editar un usuario existente
            </div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-runUsuario"></div>
        </div>
        
        {{-- Nombre --}}
        <div class="mb-4">
            <label for="nombreUsuario" class="block text-sm font-medium text-gray-700">Nombre *</label>
            <input type="text" id="nombreUsuario" name="nombreUsuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreUsuario"></div>
        </div>

        {{-- Apellido Paterno --}}
        <div class="mb-4">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Primer Apellido</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-apellidoPaterno"></div>
        </div>

        {{-- Apellido Materno --}}
        <div class="mb-4">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Segundo Apellido</label>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-apellidoMaterno"></div>
        </div>

        {{-- Correo --}}
        <div class="mb-4">
            <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
            <input type="email" id="correo" name="correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-correo"></div>
        </div>

        {{-- Teléfono --}}
        <div class="mb-4">
            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
            <input type="text" id="telefono" name="telefono" placeholder="+56912345678" maxlength="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-telefono"></div>
        </div>

        {{-- Contraseña --}}
        <div class="mb-4">
            <label for="contrasenia" class="block text-sm font-medium text-gray-700">Contraseña <span id="password-required">*</span></label>
            <input type="password" id="contrasenia" name="contrasenia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div class="text-xs text-gray-500 mt-1" id="password-help">Mínimo 8 caracteres</div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-contrasenia"></div>
        </div>

        {{-- Confirmar Contraseña --}}
        <div class="mb-4">
            <label for="contrasenia_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña <span id="password-confirm-required">*</span></label>
            <input type="password" id="contrasenia_confirmation" name="contrasenia_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-contrasenia_confirmation"></div>
        </div>

        {{-- Roles --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Roles *</label>
            <div class="space-y-2" id="roles-container">
                @foreach($roles as $role)
                    <label class="flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">{{ $role->name }}</span>
                    </label>
                @endforeach
            </div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-roles"></div>
        </div>

    </x-crud-modal>
    
    @vite(['resources/js/app.js'])
</x-app-layout>