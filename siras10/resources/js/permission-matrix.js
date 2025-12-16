document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_id_selector');
    const userSelect = document.getElementById('user_select');
    const menuSelect = document.getElementById('menu_select');
    const submenuSelect = document.getElementById('submenu_select'); // Aún se usa internamente
    const filterForm = document.getElementById('filterForm');
    const noPermissionsMessage = document.getElementById('no-permissions-message');

    // Si no existen los elementos necesarios, salir del script
    if (!roleSelect || !userSelect || !menuSelect || !filterForm) {
        return;
    }

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

        const bulkButtons = document.getElementById('bulk-action-buttons');

        if (!menuId) {
            submenuSelect.innerHTML = '<option value="">-- Selecciona un menú --</option>';
            bulkButtons.style.display = 'none'; // Ocultar botones si no hay menú
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
                    bulkButtons.style.display = 'flex'; // Mostrar botones si hay permisos
                } else {
                    bulkButtons.style.display = 'none'; // Ocultar botones si no hay permisos
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
                // Obtener el runUsuario seleccionado desde el atributo data del form
                const selectedUserRun = filterForm.dataset.selectedUserRun || '';
                
                users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.runUsuario;
                    option.textContent = `${user.nombreUsuario} ${user.apellidoPaterno} ${user.apellidoMaterno}`;
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

    // --- FUNCIONALIDAD DE SELECCIONAR/DESELECCIONAR TODOS LOS PERMISOS DEL MENÚ ---
    const selectAllBtn = document.getElementById('select-all-menu');
    const deselectAllBtn = document.getElementById('deselect-all-menu');

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            // Seleccionar todos los checkboxes visibles
            document.querySelectorAll('.permission-group').forEach(group => {
                if (group.style.display !== 'none') {
                    group.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = true;
                    });
                }
            });
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            // Deseleccionar todos los checkboxes visibles
            document.querySelectorAll('.permission-group').forEach(group => {
                if (group.style.display !== 'none') {
                    group.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            });
        });
    }
});
