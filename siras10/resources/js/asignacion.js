document.addEventListener('DOMContentLoaded', function () {
    const listaCoordinadores = document.getElementById('lista-coordinadores');
    if (!listaCoordinadores) {
        return; 
    }
    
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
    
    let runUsuarioSeleccionado = null;
    let nombreUsuarioSeleccionado = null;
    let activeListItem = null;


    // --- 1. Al hacer clic en un Coordinador de la lista ---
    listaCoordinadores.addEventListener('click', function (e) {
        const item = e.target.closest('li');
        if (!item || !item.dataset.id) return;

        runUsuarioSeleccionado = item.dataset.id;
        nombreUsuarioSeleccionado = item.dataset.nombre;

        // Resalta el item seleccionado
        if (activeListItem) {
            activeListItem.classList.remove('bg-indigo-100', 'font-semibold');
        }
        item.classList.add('bg-indigo-100', 'font-semibold');
        activeListItem = item;

        // Mostrar spinner
        panelInicial.classList.add('hidden');
        panelContenido.classList.add('hidden');
        panelCarga.classList.remove('hidden');

        // Cargar los datos
        cargarDatosAsignacion(runUsuarioSeleccionado);
    });

    // --- 2. Función para Cargar Datos (AJAX GET) ---
    async function cargarDatosAsignacion(runUsuario) {
        try {
            const response = await fetch(`/coordinadores/${runUsuario}/centros`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Error al cargar datos');
            
            const data = await response.json();

            // Llenar datos en el panel derecho
            nombreCoordinadorSpan.textContent = nombreUsuarioSeleccionado;
            poblarSelect(data.disponibles);
            poblarLista(data.asignados);

            // Mostrar contenido
            panelCarga.classList.add('hidden');
            panelContenido.classList.remove('hidden');

        } catch (error) {
            console.error(error);
            panelCarga.innerHTML = '<p class="text-red-500">No se pudieron cargar los datos.</p>';
        }
    }

    // --- 3. Al hacer clic en el botón "Asignar" (AJAX POST) ---
    btnAsignar.addEventListener('click', async function () {
        const centroId = selectDisponibles.value;
        if (!centroId || !runUsuarioSeleccionado) return;

        errorAsignacion.classList.add('hidden');
        btnAsignar.disabled = true;
        
        try {
            const response = await fetch(`/coordinadores/${runUsuarioSeleccionado}/centros`, {
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
            
            // Éxito: Recargar la lista
            cargarDatosAsignacion(runUsuarioSeleccionado);

        } catch (error) {
            errorAsignacion.textContent = error.message;
            errorAsignacion.classList.remove('hidden');
        } finally {
            btnAsignar.disabled = false;
        }
    });

    // --- 4. Al hacer clic en "Quitar" (AJAX DELETE) ---
    listaAsignados.addEventListener('click', async function (e) {
        const botonQuitar = e.target.closest('[data-action="quitar"]');
        if (!botonQuitar) return;

        const centroId = botonQuitar.dataset.id;
        if (!confirm(`¿Estás seguro de que quieres quitar este centro al coordinador?`)) return;

        try {
            const response = await fetch(`/coordinadores/${runUsuarioSeleccionado}/centros/${centroId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            if (!response.ok) throw new Error('No se pudo quitar la asignación');
            
            // Éxito: Recargar la lista
            cargarDatosAsignacion(runUsuarioSeleccionado);

        } catch (error) {
            alert(error.message);
        }
    });


    // --- Funciones de Ayuda ---
    function poblarSelect(disponibles) {
        selectDisponibles.innerHTML = ''; // Limpiar
        if (disponibles.length === 0) {
            selectDisponibles.innerHTML = '<option value="">No hay más centros para asignar</option>';
            btnAsignar.disabled = true;
            return;
        }
        
        btnAsignar.disabled = false;
        selectDisponibles.innerHTML = '<option value="">Seleccione un centro...</option>';
        disponibles.forEach(centro => {
            const option = document.createElement('option');
            option.value = centro.idCentroFormador;
            option.textContent = centro.nombreCentroFormador;
            selectDisponibles.appendChild(option);
        });
    }

    function poblarLista(asignados) {
        listaAsignados.innerHTML = ''; // Limpiar
        if (asignados.length === 0) {
            sinAsignacionesMsg.classList.remove('hidden');
            return;
        }
        
        sinAsignacionesMsg.classList.add('hidden');
        asignados.forEach(centro => {
            const li = document.createElement('li');
            li.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-md border';
            li.innerHTML = `
                <span class="font-medium">${centro.nombreCentroFormador}</span>
                <button data-action="quitar" data-id="${centro.idCentroFormador}" 
                        class="text-red-500 hover:text-red-700 font-semibold text-sm"
                        title="Quitar asignación">
                    Quitar
                </button>
            `;
            listaAsignados.appendChild(li);
        });
    }
});