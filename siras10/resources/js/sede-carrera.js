import BaseModalManager from './base-modal-manager.js';

class SedeCarreraManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'crudModal',
            formId: 'crudForm',
            entityName: 'Carrera',
            entityGender: 'f',
            baseUrl: '/gestion-carreras',
            primaryKey: 'idSedeCarrera',
            tableContainerId: 'tabla-container',
            fields: ['idSede', 'idCarrera', 'nombreSedeCarrera', 'codigoCarrera']
        });

        this.selectionContainer = document.getElementById('selection-container');
        if (!this.selectionContainer) return;

        this.gestionContainer = document.getElementById('gestion-container');
        this.sedeNamePlaceholder = document.getElementById('sede-name-placeholder');
        this.tableContainer = document.getElementById('tabla-container');

        // Elementos del modal de malla
        this.mallaModal = document.getElementById('mallaModal');
        this.mallaForm = document.getElementById('mallaForm');

        this.centros = JSON.parse(this.selectionContainer.dataset.centros || '[]');
        this.carreras = JSON.parse(this.selectionContainer.dataset.carreras || '[]');
        this.currentSedeId = null;

        this.mallasListModal = document.getElementById('mallasListModal');
        this.anioFiltroMallas = document.getElementById('anioFiltroMallas');
        this.mallasContainer = document.getElementById('mallas-container');

        this.initSedeCarrera();
        this.initMallaModal();
    }

    initSedeCarrera() {
        this.createSelectors();
        this.populateSelectors();
        this.attachSedeEvents();
        this.attachMallasModalEvents();
    }

    createSelectors() {
        const grid = this.selectionContainer.querySelector('.grid');
        if (!grid?.children[1]) return;

        grid.children[0].innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-2">Centro Formador</label>
            <select id="centro-selector" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">-- Seleccione centro --</option>
            </select>
        `;

        grid.children[1].innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-2">Sede</label>
            <select id="sede-selector" class="w-full rounded-md border-gray-300 shadow-sm" disabled>
                <option>-- Seleccione centro primero --</option>
            </select>
        `;

        this.centroSelector = document.getElementById('centro-selector');
        this.sedeSelector = document.getElementById('sede-selector');
    }

    populateSelectors() {
        this.centros.forEach(c => {
            this.centroSelector.add(new Option(c.nombreCentroFormador, c.idCentroFormador));
        });

        const carreraSelect = this.form.querySelector('#idCarrera');
        if (carreraSelect) {
            carreraSelect.innerHTML = '<option value="">-- Seleccione Perfil --</option>';
            this.carreras.forEach(c => {
                carreraSelect.add(new Option(c.nombreCarrera, c.idCarrera));
            });
        }
    }

    attachSedeEvents() {
        this.centroSelector.addEventListener('change', () => this.handleCentroChange());
        this.sedeSelector.addEventListener('change', () => this.handleSedeChange());

        document.querySelector('[data-modal-target="crudModal"]')
            ?.addEventListener('click', () => this.prepareCreate());

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;
            const id = btn.dataset.id;

            if (btn.dataset.action === 'edit') this.editarRegistro(id);
            if (btn.dataset.action === 'delete') this.eliminarRegistro(id);
            if (btn.dataset.action === 'malla') this.abrirModalMalla(id);
        });
    }

    handleCentroChange() {
        const centroId = this.centroSelector.value;
        this.hideGestion();
        this.updateSedeSelector(centroId);
        this.currentSedeId = null;
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        if (!this.validate()) return;

        const formData = new FormData(this.form);
        const isUpdate = this.form.querySelector('[name="_method"]')?.value === 'PUT';
        const id = this.form.dataset.id;

        // URL CORRECTA SEGÚN ACCIÓN
        const url = isUpdate
            ? `/gestion-carreras/${id}`  // PUT → POST a /gestion-carreras/1
            : '/gestion-carreras';       // POST → /gestion-carreras

        // SIEMPRE POST + _method
        if (isUpdate) {
            formData.append('_method', 'PUT');
        }

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const result = await res.json();

            if (res.ok && result.success) {
                this.onSuccess(result);
            } else {
                this.handleError(result);
            }
        } catch (err) {
            console.error(err);
            this.showAlert('Error', 'Error de red', 'error');
        }
    }

    async handleSedeChange() {
        this.currentSedeId = this.sedeSelector.value;
        if (!this.currentSedeId) return this.hideGestion();

        this.updateSedeName();
        this.showGestion();
        await this.loadTable();
    }

    updateSedeSelector(centroId) {
        this.sedeSelector.innerHTML = '<option>-- Seleccione sede --</option>';
        this.sedeSelector.disabled = true;

        if (!centroId) return;

        const centro = this.centros.find(c => c.idCentroFormador == centroId);
        if (centro?.sedes?.length) {
            centro.sedes.forEach(s => {
                this.sedeSelector.add(new Option(s.nombreSede, s.idSede));
            });
            this.sedeSelector.disabled = false;
        }
    }

    updateSedeName() {
        if (this.sedeNamePlaceholder && this.sedeSelector.selectedOptions[0]) {
            this.sedeNamePlaceholder.textContent = this.sedeSelector.selectedOptions[0].text;
        }
    }

    showGestion() { this.gestionContainer?.classList.remove('hidden'); }
    hideGestion() {
        this.gestionContainer?.classList.add('hidden');
        if (this.tableContainer) this.tableContainer.innerHTML = '';
    }

    // RECARGA LA TABLA (CLAVE)
    async loadTable() {
        if (!this.tableContainer || !this.currentSedeId) return;

        this.tableContainer.innerHTML = `
        <div class="text-center p-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="mt-3 text-gray-600">Cargando carreras...</p>
        </div>
    `;

        try {
            const res = await fetch(`/gestion-carreras/sedes/${this.currentSedeId}/tabla-html`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!res.ok) throw new Error('Error al cargar');

            const html = await res.text();
            this.tableContainer.innerHTML = html;
        } catch (err) {
            this.tableContainer.innerHTML = `
            <div class="text-center p-8 text-red-600">
                <p>Error al cargar las carreras</p>
            </div>
        `;
        }
    }

    prepareCreate() {
        if (!this.currentSedeId) {
            this.showAlert('Error', 'Selecciona una sede primero', 'error');
            return;
        }

        this.limpiarFormulario();
        this.form.querySelector('[name="idSede"]').value = this.currentSedeId;
        this.setModalTitle('Añadir Carrera');
        this.setButtonText('Guardar Carrera');
        this.mostrarModal();
    }

    async editarRegistro(id) {
        try {
            this.setModalTitle('Cargando...');
            this.mostrarModal();

            const res = await fetch(`/gestion-carreras/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const json = await res.json();
            if (!json.success) throw new Error(json.message || 'Sin success');

            // Rellenar campos
            const idSedeInput = this.form.querySelector('[name="idSede"]');
            if (idSedeInput) idSedeInput.value = json.data.idSede;

            this.form.querySelector('[name="idCarrera"]').value = json.data.idCarrera;
            this.form.querySelector('[name="nombreSedeCarrera"]').value = json.data.nombreSedeCarrera || '';
            this.form.querySelector('[name="codigoCarrera"]').value = json.data.codigoCarrera;

            // AGREGAR _method=PUT
            this._setMethodInput('PUT');
            this.form.dataset.id = id;

            this.setModalTitle('Editar Carrera');
            this.setButtonText('Actualizar');

        } catch (err) {
            console.error('Error al editar:', err);
            this.showAlert('Error', 'No se pudo cargar la carrera', 'error');
            this.cerrarModal();
        }

    }

    async eliminarRegistro(id) {
        await super.eliminarRegistro(id);
        await this.loadTable();
    }

    validate() {
        const idSede = this.form.querySelector('[name="idSede"]')?.value;
        const idCarrera = this.form.querySelector('[name="idCarrera"]')?.value;
        if (!idSede || !idCarrera) {
            this.showAlert('Error', 'Faltan datos requeridos', 'error');
            return false;
        }
        return true;
    }

    // ÉXITO → RECARGA TABLA
    onSuccess(data) {
        this.cerrarModal();
        this.showAlert('¡Éxito!', data.message || 'Guardado correctamente', 'success');
        this.loadTable(); // RECARGA INMEDIATA
    }

    // ========================================================================
    // MÉTODOS PARA MODAL DE MALLA CURRICULAR
    // ========================================================================
    mostrarArchivoSeleccionado(event) {
        const file = event.target.files[0];
        const archivoDiv = document.getElementById('archivoSeleccionado');
        const nombreSpan = document.getElementById('nombreArchivoSeleccionado');

        if (file) {
            // Validar que sea PDF
            if (file.type !== 'application/pdf') {
                this.showAlert('Error', 'Solo se permiten archivos PDF', 'error');
                event.target.value = '';
                archivoDiv.classList.add('hidden');
                return;
            }

            // Validar tamaño (2MB máximo)
            const maxSize = 2 * 1024 * 1024; // 2MB en bytes
            if (file.size > maxSize) {
                this.showAlert('Error', 'El archivo no debe superar 2MB', 'error');
                event.target.value = '';
                archivoDiv.classList.add('hidden');
                return;
            }

            // Mostrar archivo seleccionado
            nombreSpan.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + 'MB)';
            archivoDiv.classList.remove('hidden');
        } else {
            archivoDiv.classList.add('hidden');
        }
    }
    initMallaModal() {
        if (!this.mallaModal || !this.mallaForm) return;

        // Event listeners para el modal de malla
        this.mallaForm.addEventListener('submit', (e) => this.handleMallaSubmit(e));

        // Cerrar modal - MEJORAR ESTO
        this.mallaModal.addEventListener('click', (e) => {
            // Cerrar si se hace click en el fondo del modal
            if (e.target.id === 'mallaModal') {
                this.cerrarModalMalla();
            }

            // Cerrar si se hace click en el botón de cerrar
            if (e.target.closest('[data-action="close-malla-modal"]')) {
                this.cerrarModalMalla();
            }
        });

        // Event listener para la tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.mallaModal.classList.contains('hidden')) {
                this.cerrarModalMalla();
            }
        });

        // Event listener para el archivo
        const archivoInput = document.getElementById('archivoPdf');
        if (archivoInput) {
            archivoInput.addEventListener('change', (e) => this.mostrarArchivoSeleccionado(e));
        }
    }

    async abrirModalMalla(idSedeCarrera) {
        if (!this.mallaModal) return;
    
        // Limpiar formulario
        this.mallaForm.reset();
    
        const idInput = document.getElementById('mallaIdSedeCarrera');
        if (idInput) {
            idInput.value = idSedeCarrera;
        }
    
        // Establecer año actual por defecto
        const anioInput = document.getElementById('anioMalla');
        if (anioInput) {
            anioInput.value = new Date().getFullYear();
        }
    
        const archivoPreview = document.getElementById('archivoSeleccionado');
        if (archivoPreview) {
            archivoPreview.classList.add('hidden');
        }
    
        // Mostrar modal
        this.mallaModal.classList.remove('hidden');
        this.mallaModal.classList.add('flex', 'items-center', 'justify-center');
    
        // Focus en el campo de año
        setTimeout(() => {
            if (anioInput) {
                anioInput.focus();
                anioInput.select(); // Seleccionar el texto para facilitar edición
            }
        }, 100);
    }

    cerrarModalMalla() {
        if (!this.mallaModal) return;

        // Ocultar modal
        this.mallaModal.classList.add('hidden');
        this.mallaModal.classList.remove('flex', 'items-center', 'justify-center');

        // Limpiar formulario
        if (this.mallaForm) {
            this.mallaForm.reset();
        }

        // Limpiar archivo seleccionado
        const archivoPreview = document.getElementById('archivoSeleccionado');
        if (archivoPreview) {
            archivoPreview.classList.add('hidden');
        }

        // Limpiar cualquier mensaje de error previo
        const errorContainer = document.getElementById('mallaErrorContainer');
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
    }

    async cargarAniosDisponibles() {
        // Ya no es necesario cargar años, el input numérico permite ingresar cualquier año
        // Solo establecer el año actual como valor por defecto si está vacío
        const anioInput = document.getElementById('anioMalla');
        if (anioInput && !anioInput.value) {
            anioInput.value = new Date().getFullYear();
        }
    }

    async handleMallaSubmit(e) {
    e.preventDefault();
    
    if (!this.validateMallaForm()) return;
    
    const form = this.mallaForm || document.getElementById('mallaForm');
    if (!form) {
        console.error('Formulario mallaForm no encontrado');
        this.showAlert('Error', 'Error al encontrar el formulario', 'error');
        return;
    }
    
    const formData = new FormData(form);
    
    // Buscar el botón de submit
    const submitButton = form.querySelector('button[type="submit"]') || 
                        this.mallaModal?.querySelector('button[type="submit"]');
    
    const originalText = submitButton ? (submitButton.textContent || 'Guardar Malla') : 'Guardar Malla';
    
    try {
        // Solo deshabilitar si encontramos el botón
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Guardando...';
        }

        const response = await fetch('/gestion-carreras/malla', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });

        if (!response.ok) {
            let errorMessage = 'Error al guardar la malla curricular.';
            try {
                const errorData = await response.json();
                errorMessage = errorData.message || errorData.error || errorMessage;
                if (errorData.errors) {
                    const errorList = Object.values(errorData.errors).flat().join(', ');
                    errorMessage += ' ' + errorList;
                }
            } catch (e) {
                errorMessage = `Error del servidor (${response.status}): ${response.statusText}`;
            }
            throw new Error(errorMessage);
        }

        const data = await response.json();

        if (data.success) {
            this.showAlert('Éxito', data.message, 'success');
            this.cerrarModalMalla();
            if (typeof this.loadTable === 'function') {
                await this.loadTable();
            }
        } else {
            throw new Error(data.message || 'Error desconocido');
        }

    } catch (error) {
        console.error('Error completo:', error);
        this.showAlert('Error', 'Error al subir malla: ' + error.message, 'error');
    } finally {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }
}

    validateMallaForm() {
        const anio = document.getElementById('anioMalla').value;
        const nombre = document.getElementById('nombreMalla').value?.trim();
        const archivo = document.getElementById('archivoPdf').files?.[0];

        if (!anio) {
            this.showAlert('Error', 'Por favor, ingresa un año académico.', 'error');
            return false;
        }
    
        // Validar que el año sea un número válido y esté en rango razonable
        const anioNum = parseInt(anio);
        if (isNaN(anioNum) || anioNum < 2020 || anioNum > 2030) {
            this.showAlert('Error', 'Por favor, ingresa un año válido entre 2020 y 2030.', 'error');
            return false;
        }

        if (!nombre) {
            this.showAlert('Error', 'Por favor, ingresa el nombre de la malla.', 'error');
            return false;
        }

        if (!archivo) {
            this.showAlert('Error', 'Por favor, selecciona un archivo PDF.', 'error');
            return false;
        }

        if (archivo.type !== 'application/pdf') {
            this.showAlert('Error', 'Solo se permiten archivos PDF.', 'error');
            return false;
        }

        const maxSize = 2 * 1024 * 1024; // 2MB
        if (archivo.size > maxSize) {
            this.showAlert('Error', `El archivo es demasiado grande. Tamaño máximo: 2MB. Actual: ${(archivo.size / (1024 * 1024)).toFixed(2)}MB`, 'error');
            return false;
        }

        return true;
    }

    _setMethodInput(method) {
        // Eliminar input previo
        const existing = this.form.querySelector('input[name="_method"]');
        if (existing) existing.remove();

        // Crear nuevo si es necesario
        if (method) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_method';
            input.value = method;
            this.form.appendChild(input);
        }
    }

    attachMallasModalEvents() {
        // Event listener para el botón "Ver Mallas Curriculares"
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-action="ver-mallas"]');
            if (btn) {
                e.preventDefault();
                this.abrirModalMallas();
                return;
            }
    
            // Event listener para cerrar el modal de mallas
            const closeBtn = e.target.closest('[data-action="close-mallas-modal"]');
            if (closeBtn) {
                e.preventDefault();
                this.cerrarModalMallas();
                return;
            }
        });
    
        // Cambio en el campo de año (ahora es input, no select)
        if (this.anioFiltroMallas) {
            // Usar 'input' o 'change' para detectar cuando el usuario escribe
            this.anioFiltroMallas.addEventListener('input', () => {
                // Opcional: agregar un pequeño delay para evitar muchas peticiones
                clearTimeout(this.anioFiltroTimeout);
                this.anioFiltroTimeout = setTimeout(() => {
                    this.cargarMallas();
                }, 500); // Esperar 500ms después de que el usuario deje de escribir
            });
        }
    }
    
    async abrirModalMallas() {
    if (!this.currentSedeId) {
        this.showAlert('Error', 'Selecciona una sede primero', 'error');
        return;
    }

    if (!this.mallasListModal) return;

    // Limpiar el campo de año
    if (this.anioFiltroMallas) {
        this.anioFiltroMallas.value = '';
    }

    // Mostrar modal
    this.mallasListModal.classList.remove('hidden');
    this.mallasListModal.classList.add('flex', 'items-center', 'justify-center');

    // Cargar mallas iniciales (todas)
    await this.cargarMallas();
}
    
    cerrarModalMallas() {
        if (!this.mallasListModal) return;
        this.mallasListModal.classList.add('hidden');
        this.mallasListModal.classList.remove('flex', 'items-center', 'justify-center');
        this.mallasContainer.innerHTML = '<div class="text-center py-8 text-gray-500"><p>Selecciona un año para ver las mallas curriculares</p></div>';
    }
    
    
    async cargarMallas() {
        if (!this.currentSedeId || !this.mallasContainer) return;
    
        const anio = this.anioFiltroMallas?.value || ''; 
    
        this.mallasContainer.innerHTML = `
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                <p class="mt-3 text-gray-600">Cargando mallas...</p>
            </div>
        `;
    
        try {
            const url = `/gestion-carreras/sedes/${this.currentSedeId}/mallas${anio ? '?anio=' + anio : ''}`;
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
    
            if (!res.ok) throw new Error('Error al cargar');
    
            const data = await res.json();
    
            if (data.success && data.data.length > 0) {
                this.renderMallas(data.data);
            } else {
                this.mallasContainer.innerHTML = `
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-600">No se encontraron mallas curriculares${anio ? ' para el año ' + anio : ''}</p>
                    </div>
                `;
            }
        } catch (err) {
            this.mallasContainer.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <p>Error al cargar las mallas curriculares</p>
                </div>
            `;
        }
    }
    
    renderMallas(mallas) {
        this.mallasContainer.innerHTML = mallas.map(malla => `
            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">${malla.nombre}</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Carrera:</span> ${malla.carrera}
                            </div>
                            <div>
                                <span class="font-medium">Código:</span> ${malla.codigoCarrera}
                            </div>
                            <div>
                                <span class="font-medium">Año:</span> ${malla.anio}
                            </div>
                            <div>
                                <span class="font-medium">Fecha Subida:</span> ${malla.fechaSubida || 'N/A'}
                            </div>
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="/storage/${malla.documento}" 
                        target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Ver PDF
                        </a>
                    </div>
                </div>
            </div>
        `).join('');
    }
}


document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('selection-container')) {
        window.sedeCarreraManager = new SedeCarreraManager();
    }
});

export default SedeCarreraManager;