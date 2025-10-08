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
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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

                            <div>
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
                                <div id="no-permissions-message" class="text-center text-gray-500 py-4" style="display: none;">Selecciona un submenú para ver los permisos.</div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id_selector');
        const userSelect = document.getElementById('user_select');
        const menuSelect = document.getElementById('menu_select');
        const submenuSelect = document.getElementById('submenu_select');
        const filterForm = document.getElementById('filterForm');
        const noPermissionsMessage = document.getElementById('no-permissions-message');

        function showPermissionGroup(resourceName) {
            document.querySelectorAll('.permission-group').forEach(group => {
                group.style.display = group.dataset.resourceName === resourceName ? 'block' : 'none';
            });
            const hasVisibleGroup = resourceName && document.querySelector(`.permission-group[data-resource-name="${resourceName}"]`);
            noPermissionsMessage.style.display = hasVisibleGroup ? 'none' : 'block';
        }

        // --- Lógica para Rol y Usuario (recarga de página) ---
        roleSelect.addEventListener('change', () => {
            userSelect.value = ''; // Limpia la selección de usuario
            filterForm.submit();
        });
        userSelect.addEventListener('change', () => {
            if (userSelect.value) {
                filterForm.submit();
            }
        });
        
        // --- Lógica para Menú -> Submenú (AJAX) ---
        menuSelect.addEventListener('change', function() {
            const menuId = this.value;
            submenuSelect.innerHTML = '<option value="">Cargando...</option>';
            submenuSelect.disabled = true;
            showPermissionGroup(null);

            if (!menuId) {
                submenuSelect.innerHTML = '<option value="">-- Selecciona un menú --</option>';
                return;
            }

            fetch(`/api/menus/${menuId}/submenus`)
                .then(response => response.json())
                .then(submenus => {
                    submenuSelect.innerHTML = '<option value="">-- Elige un submenú --</option>';
                    submenus.forEach(submenu => {
                        const option = document.createElement('option');
                        option.value = submenu.nombreSubmenu;
                        option.textContent = submenu.nombreSubmenu;
                        submenuSelect.appendChild(option);
                    });
                    submenuSelect.disabled = false;
                });
        });

        // --- Lógica para Submenú -> Mostrar Permisos ---
        submenuSelect.addEventListener('change', function() {
            showPermissionGroup(this.value);
        });

        // --- Lógica de Inicialización al cargar la página ---
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
                        option.textContent = `${user.nombres} ${user.apellidoPaterno}`;
                        if (user.runUsuario === selectedUserRun) {
                            option.selected = true;
                        }
                        userSelect.appendChild(option);
                    });
                    
                    userSelect.disabled = false;

                    // --- LÍNEA CLAVE CORREGIDA ---
                    // Activamos el selector de Menú SOLO si hay un usuario seleccionado
                    menuSelect.disabled = !selectedUserRun;
                });
        }
        
        populateUsersOnLoad();
    });
</script>
</x-app-layout>