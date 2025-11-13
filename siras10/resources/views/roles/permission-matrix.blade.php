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
                    <form method="GET" action="{{ route('roles.permission_matrix') }}" id="filterForm">
                        
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
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded" @if ($selectedUser->getAllPermissions()->contains('name', $permission->name)) checked @endif>
                                                    <span class="ml-2 text-sm text-gray-600">{{ explode('.', $permission->name)[1] ?? $permission->name }}</span>
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

{{-- ===================================================================== --}}
{{-- CAMBIO: LÓGICA COMPLETA DEL SCRIPT ACTUALIZADA --}}
{{-- ===================================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_id_selector');
    const userSelect = document.getElementById('user_select');
    const menuSelect = document.getElementById('menu_select');
    const submenuSelect = document.getElementById('submenu_select'); // Aún se usa internamente
    const filterForm = document.getElementById('filterForm');
    const noPermissionsMessage = document.getElementById('no-permissions-message');

    // --- NUEVAS FUNCIONES DE AYUDA ---

    /**
     * Oculta TODOS los grupos de permisos y muestra el mensaje de "vacío".
     */
    function hideAllPermissionGroups() {
        document.querySelectorAll('.permission-group').forEach(group => {
            group.style.display = 'none';
        });
        noPermissionsMessage.style.display = 'block'; 
    }

    /**
     * Muestra UN grupo de permisos por su nombre (resourceName).
     * Retorna 'true' si lo encontró y 'false' si no.
     */
    function showPermissionGroup(resourceName) {
        const group = document.querySelector(`.permission-group[data-resource-name="${resourceName}"]`);
        if (group) {
            group.style.display = 'block';
            return true; // Lo encontró y lo mostró
        }
        return false; // No lo encontró
    }

    // --- Lógica para Rol y Usuario (recarga de página) ---
    // (Esta parte no cambia)
    roleSelect.addEventListener('change', () => {
        userSelect.value = ''; // Limpia la selección de usuario
        filterForm.submit();
    });
    userSelect.addEventListener('change', () => {
        if (userSelect.value) {
            filterForm.submit();
        }
    });
    
    // --- LÓGICA MODIFICADA DE MENÚ -> PERMISOS ---
    menuSelect.addEventListener('change', function() {
        const menuId = this.value;

        // 1. Ocultamos todos los permisos anteriores
        hideAllPermissionGroups();
        
        // (Usamos el select oculto para mostrar "cargando" al usuario)
        submenuSelect.innerHTML = '<option value="">Cargando...</option>';
        submenuSelect.disabled = true; // Sigue deshabilitado (y oculto)

        if (!menuId) {
            submenuSelect.innerHTML = '<option value="">-- Selecciona un menú --</option>';
            return;
        }

        // 2. Buscamos los submenús de este menú
        fetch(`/api/menus/${menuId}/submenus`)
            .then(response => response.json())
            .then(submenus => {
                // Ya no poblamos el select, solo lo usamos para feedback
                submenuSelect.innerHTML = '<option value="">Submenús cargados</option>'; 
                let permissionsFound = false; // Flag para ver si encontramos algo

                // 3. ¡LA MAGIA! Iteramos por CADA submenú encontrado...
                submenus.forEach(submenu => {
                    
                    // ...y mostramos su grupo de permisos correspondiente.
                    if (showPermissionGroup(submenu.nombreSubmenu)) {
                        permissionsFound = true; // Marcamos que sí encontramos permisos
                    }
                });
                
                // 4. Si encontramos al menos un permiso, ocultamos el mensaje de "vacío".
                if (permissionsFound) {
                    noPermissionsMessage.style.display = 'none';
                }
                
                // (Ya no es necesario habilitar el select de submenú)
            });
    });

    // --- LÓGICA ELIMINADA ---
    // Ya no necesitamos un "listener" para el dropdown de submenú.
    // submenuSelect.addEventListener('change', ...); // <--- ESTO SE FUE

    // --- Lógica de Inicialización al cargar la página ---
    // (Esta parte no cambia)
    function populateUsersOnLoad() {
        const roleId = roleSelect.value;
        if (!roleId) return;

        userSelect.disabled = true;
        userSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch(`/api/roles/${roleId}/users`)
            .then(response => response.json())
            .then(users => {
                userSelect.innerHTML = '<option value="">-- Elige un usuario --</option>';
                const selectedUserRun = '{{ $selectedUser->runUsuario ?? '' }}';
                
                users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.runUsuario;
                    option.textContent = `${user.nombreUsuario} ${user.apellidoPaterno}`;
                    if (user.runUsuario === selectedUserRun) {
                        option.selected = true;
                    }
                    userSelect.appendChild(option);
                                });
                
                userSelect.disabled = false;

                // Activamos el selector de Menú SOLO si hay un usuario seleccionado
                menuSelect.disabled = !selectedUserRun;
            });
    }
    
    populateUsersOnLoad();
});
</script>
</x-app-layout>