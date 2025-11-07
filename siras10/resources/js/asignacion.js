document.addEventListener('DOMContentLoaded', function () {
    
    const listaCoordinadores = document.getElementById('lista-coordinadores');
    if (!listaCoordinadores) return;

    const panelDerecho = document.getElementById('panel-derecho');
    const GRUPO_ACTIVO = panelDerecho.dataset.grupo;

    const BASE_URLS = {
        campo_clinico: '/asignaciones/campo-clinico',
        rad: '/asignaciones/rad'
    };
    const BASE_URL = BASE_URLS[GRUPO_ACTIVO];

    let runUsuarioSeleccionado = null;
    let nombreUsuarioSeleccionado = null;
    let activeListItem = null;
    let claves = { id: null, nombre: null };

    // --- ELEMENTOS DEL DOM ---
    const panelInicial = document.getElementById('panel-inicial');
    const panelCarga = document.getElementById('panel-carga');
    const panelContenido = document.getElementById('panel-contenido');
    const nombreCoordinadorSpan = document.getElementById('nombre-coordinador');
    const selectDisponibles = document.getElementById('select-centros-disponibles');
    const listaAsignados = document.getElementById('lista-asignaciones-actuales');
    const btnAsignar = document.getElementById('btn-asignar');
    const sinAsignacionesMsg = document.getElementById('sin-asignaciones');
    const errorAsignacion = document.getElementById('error-asignacion');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    listaCoordinadores.addEventListener('click', function (e) {
        const item = e.target.closest('li');
        if (!item || !item.dataset.id) {
            return;
        }
        
        runUsuarioSeleccionado = item.dataset.id;
        nombreUsuarioSeleccionado = item.dataset.nombre;

        if (activeListItem) {
            activeListItem.classList.remove('bg-indigo-100', 'font-semibold');
        }
        item.classList.add('bg-indigo-100', 'font-semibold');
        activeListItem = item;

        panelInicial.classList.add('hidden');
        panelContenido.classList.add('hidden');
        panelCarga.classList.remove('hidden');

        cargarDatosAsignacion(runUsuarioSeleccionado);
    });

    async function cargarDatosAsignacion(runUsuario) {
        try {
            const response = await fetch(`${BASE_URL}/${runUsuario}/centros`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Error al cargar datos');
            
            const data = await response.json();

            claves.id = data.idKey;
            claves.nombre = data.nameKey;

            nombreCoordinadorSpan.textContent = nombreUsuarioSeleccionado;
            poblarSelect(data.disponibles);
            poblarLista(data.asignados);

            panelCarga.classList.add('hidden');
            panelContenido.classList.remove('hidden');

        } catch (error) {
            console.error(error);
            panelCarga.innerHTML = '<p class="text-red-500">No se pudieron cargar los datos.</p>';
        }
    }

    btnAsignar.addEventListener('click', async function () {
        const centroId = selectDisponibles.value;
        if (!centroId || !runUsuarioSeleccionado) return;

        errorAsignacion.classList.add('hidden');
        btnAsignar.disabled = true;
        
        try {
            const response = await fetch(`${BASE_URL}/${runUsuarioSeleccionado}/centros`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ centro_id: centroId })
            });

            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.message || 'Error al asignar');
            }
            
            cargarDatosAsignacion(runUsuarioSeleccionado);

        } catch (error) {
            errorAsignacion.textContent = error.message;
            errorAsignacion.classList.remove('hidden');
        } finally {
            btnAsignar.disabled = false;
        }
    });

    listaAsignados.addEventListener('click', async function (e) {
        const botonQuitar = e.target.closest('[data-action="quitar"]');
        if (!botonQuitar) return;

        const centroId = botonQuitar.dataset.id;

        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Vas a quitar esta asignación al coordinador.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            const response = await fetch(`${BASE_URL}/${runUsuarioSeleccionado}/centros/${centroId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                // Si el servidor da un error, lo mostramos
                const errData = await response.json();
                throw new Error(errData.message || 'No se pudo quitar la asignación');
            }
            
            // 4. (Opcional) Mostramos un toast de éxito
            Swal.fire({
                title: '¡Eliminado!',
                text: 'La asignación ha sido quitada.',
                icon: 'success',
                timer: 1500, // Se cierra solo después de 1.5s
                showConfirmButton: false
            });
            
            cargarDatosAsignacion(runUsuarioSeleccionado);

        } catch (error) {
            // 5. Reemplazamos 'alert()' con un modal de error
            Swal.fire(
                'Error',
                error.message,
                'error'
            );
        }
    });

    function poblarSelect(disponibles) {
        selectDisponibles.innerHTML = '';
        if (disponibles.length === 0) {
            selectDisponibles.innerHTML = '<option value="">No hay más centros para asignar</option>';
            btnAsignar.disabled = true;
            return;
        }
        
        btnAsignar.disabled = false;
        selectDisponibles.innerHTML = '<option value="">Seleccione un centro...</option>';
        disponibles.forEach(centro => {
            const option = document.createElement('option');
            option.value = centro[claves.id];
            option.textContent = centro[claves.nombre]; 
            selectDisponibles.appendChild(option);
        });
    }

    function poblarLista(asignados) {
        listaAsignados.innerHTML = '';
        if (asignados.length === 0) {
            sinAsignacionesMsg.classList.remove('hidden');
            return;
        }
        
        sinAsignacionesMsg.classList.add('hidden');
        asignados.forEach(centro => {
            const li = document.createElement('li');
            li.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-md border';
            li.innerHTML = `
                <span class="font-medium">${centro[claves.nombre]}</span>
                <button data-action="quitar" data-id="${centro[claves.id]}" 
                        class="text-red-500 hover:text-red-700 font-semibold text-sm"
                        title="Quitar asignación">
                    Quitar
                </button>
            `;
            listaAsignados.appendChild(li);
        });
    }
});