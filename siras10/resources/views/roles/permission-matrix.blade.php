<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Permisos por Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros</h3>
                    <form method="GET" action="{{ route('roles.permission_matrix') }}" id="filterForm" data-selected-user-run="{{ $selectedUser->runUsuario ?? '' }}">
                        
                        {{-- CAMBIO: Reducido a 3 columnas, ya que la 4ta se oculta --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="role_id_selector" class="block text-sm font-medium text-gray-700">1. Rol</label>
                                <select name="role_id" id="role_id_selector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">-- Elige un rol --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @if(isset($selectedRole) && $selectedRole->id == $role->id) selected @endif>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="user_select" class="block text-sm font-medium text-gray-700">2. Usuario</label>
                                <select name="user_run" id="user_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" disabled>
                                    <option value="">-- Selecciona un rol --</option>
                                </select>
                            </div>

                            <div>
                                <label for="menu_select" class="block text-sm font-medium text-gray-700">3. Menú</label>
                                <select id="menu_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" disabled>
                                    <option value="">-- Selecciona un usuario --</option>
                                    @foreach ($menus as $menu)
                                        <option value="{{ $menu->idMenu }}">{{ $menu->nombreMenu }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- CAMBIO: Div de Submenú oculto --}}
                            <div style="display: none;">
                                <label for="submenu_select" class="block text-sm font-medium text-gray-700">4. Submenú</label>
                                <select id="submenu_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" disabled>
                                    <option value="">-- Selecciona un menú --</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (isset($selectedUser))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form action="{{ route('roles.sync_permissions') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_run" value="{{ $selectedUser->runUsuario }}">
                        <div class="p-6">
                            @if (session('success'))
                                <div class="bg-green-100 border-green-400 text-green-700 border-l-4 p-4 mb-4" role="alert"><p>{{ session('success') }}</p></div>
                            @endif
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Permisos para: <span class="font-extrabold text-blue-600">{{ $selectedUser->nombres }} {{ $selectedUser->apellidoPaterno }}</span>
                            </h3>

                            <div class="space-y-6">
                                @foreach ($permissions as $resource => $permissionList)
                                    <div class="permission-group" data-resource-name="{{ $resource }}" style="display: none;">
                                        <p class="font-bold text-gray-700">{{ ucfirst($resource) }}</p>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                            @foreach ($permissionList as $permission)
                                                @php
                                                    $action = explode('.', $permission->name)[1] ?? $permission->name;
                                                    $translations = [
                                                        'create' => ['nombre' => 'crear', 'descripcion' => 'Permite crear nuevos registros'],
                                                        'read' => ['nombre' => 'leer', 'descripcion' => 'Permite ver y consultar registros existentes'],
                                                        'update' => ['nombre' => 'editar', 'descripcion' => 'Permite modificar registros existentes'],
                                                        'delete' => ['nombre' => 'eliminar', 'descripcion' => 'Permite eliminar registros']
                                                    ];
                                                    $actionData = $translations[$action] ?? ['nombre' => $action, 'descripcion' => 'Acción personalizada'];
                                                @endphp
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded" @if ($selectedUser->getAllPermissions()->contains('name', $permission->name)) checked @endif>
                                                    <span class="ml-2 text-sm text-gray-600">{{ $actionData['nombre'] }}</span>
                                                    <span class="ml-1 text-gray-400 cursor-help" title="{{ $actionData['descripcion'] }}">
                                                        <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{-- CAMBIO: Texto del mensaje actualizado --}}
                                <div id="no-permissions-message" class="text-center text-gray-500 py-4" style="display: none;">
                                    Selecciona un menú para ver los permisos, o este menú no tiene permisos asociados.
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6 bg-white text-right">
                            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">Guardar Permisos para este Usuario</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

@vite(['resources/js/permission-matrix.js'])
</x-app-layout>