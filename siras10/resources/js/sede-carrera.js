// Modal para historial de programas
let programasModal = null;
let programasModalContent = null;
function initProgramasModal() {
    programasModal = document.getElementById('programasModal');
    programasModalContent = document.getElementById('programasModalContent');
    if (!programasModal) return;

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action="view-all-programas"]');
        if (!btn) return;
        const asignaturaId = btn.dataset.id;
        if (!asignaturaId) return;
        programasModal.classList.remove('hidden');
        programasModalContent.innerHTML = '<div class="p-8 text-center text-gray-400">Cargando...</div>';
        try {
            const resp = await fetch(`/gestion-carreras/asignaturas/${asignaturaId}/programas`);
            if (!resp.ok) throw new Error('No se pudo cargar el historial');
            const html = await resp.text();
            programasModalContent.innerHTML = html;
        } catch (err) {
            programasModalContent.innerHTML = '<div class="p-8 text-center text-red-400">Error al cargar el historial</div>';
        }
    });

    // Cerrar modal
    programasModal.addEventListener('click', (e) => {
        if (e.target === programasModal) {
            programasModal.classList.add('hidden');
        }
    });
    document.querySelectorAll('[data-action="close-programas-modal"]').forEach(btn =>
        btn.addEventListener('click', () => programasModal.classList.add('hidden'))
    );
}

document.addEventListener('DOMContentLoaded', () => {
    initProgramasModal();
});
import BaseModalManager from './base-modal-manager.js';
import Swal from 'sweetalert2';

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

        // Elementos del modal de asignatura
        this.asignaturaModal = document.getElementById('asignaturaModal');
        this.asignaturaForm = document.getElementById('asignaturaForm');

        this.initSedeCarrera();
        this.initMallaModal();
        this.initAsignaturaModal();
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
                <option value="">-- Seleccione centro primero --</option>
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
        const selectedValue = this.sedeSelector.value;
        
        // Ignorar si el valor está vacío o no ha cambiado
        if (!selectedValue || selectedValue === this.currentSedeId) {
            if (!selectedValue) this.hideGestion();
            return;
        }

        this.currentSedeId = selectedValue;
        this.updateSedeName();
        this.showGestion();
        
        // Re-obtener tableContainer después de mostrar gestion-container
        await this.$nextTick();
        this.tableContainer = document.getElementById('tabla-container');
        
        await this.loadTable();
    }
    
    // Helper para esperar el próximo tick del DOM
    $nextTick() {
        return new Promise(resolve => setTimeout(resolve, 0));
    }

    updateSedeSelector(centroId) {
        this.sedeSelector.innerHTML = '<option value="">-- Seleccione sede --</option>';
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
            // Event listener para el botón "Ver Programas de Asignatura"
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-action="ver-programas"]');
                if (btn) {
                    e.preventDefault();
                    this.abrirModalMallas();
                    return;
                }
            });

            // Event listener para cerrar el modal de mallas
            const closeBtn = e.target.closest('[data-action="close-mallas-modal"]');
            if (closeBtn) {
                e.preventDefault();
                this.cerrarModalMallas();
                return;
            }

            // Event listener para cerrar el modal de mallas presionando en el fondo
            const overlay = document.getElementById('mallasListModal');
            if (overlay && e.target === overlay) {
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

    // ========================================================================
    // MÉTODOS PARA MODAL DE ASIGNATURA
    // ========================================================================
    initAsignaturaModal() {
        if (!this.asignaturaModal || !this.asignaturaForm) return;

        // Event listeners para el modal de asignatura
        this.asignaturaForm.addEventListener('submit', (e) => this.handleAsignaturaSubmit(e));

        // Cerrar modal
        this.asignaturaModal.addEventListener('click', (e) => {
            if (e.target.id === 'asignaturaModal') {
                this.cerrarModalAsignatura();
            }

            if (e.target.closest('[data-action="close-asignatura-modal"]')) {
                this.cerrarModalAsignatura();
            }
        });

        // Event listener para la tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.asignaturaModal && !this.asignaturaModal.classList.contains('hidden')) {
                this.cerrarModalAsignatura();
            }
        });
    }

    async abrirModalAsignatura(idSedeCarrera, asignaturaId = null) {
        if (!this.asignaturaModal) return;

        // Limpiar formulario
        this.asignaturaForm.reset();

        const idInput = document.getElementById('asignaturaIdSedeCarrera');
        if (idInput) {
            idInput.value = idSedeCarrera;
        }

        if (asignaturaId) {
            // Modo edición: cargar datos de la asignatura
            await this.cargarDatosAsignatura(asignaturaId);

            const modalTitle = document.getElementById('asignaturaModalTitle');
            if (modalTitle) modalTitle.textContent = 'Editar Asignatura';

            const submitBtn = this.asignaturaForm.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.textContent = 'Actualizar Asignatura';

            this.asignaturaForm.dataset.isEdit = 'true';
            this.asignaturaForm.dataset.asignaturaId = asignaturaId;
        } else {
            // Modo creación
            const modalTitle = document.getElementById('asignaturaModalTitle');
            if (modalTitle) modalTitle.textContent = 'Crear Asignatura';

            const submitBtn = this.asignaturaForm.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.textContent = 'Guardar Asignatura';

            delete this.asignaturaForm.dataset.isEdit;
            delete this.asignaturaForm.dataset.asignaturaId;
        }

        // Mostrar modal
        this.asignaturaModal.classList.remove('hidden');
        this.asignaturaModal.classList.add('flex', 'items-center', 'justify-center');

        // Focus en el primer campo
        setTimeout(() => {
            const nombreInput = document.getElementById('nombreAsignatura');
            if (nombreInput) nombreInput.focus();
        }, 100);
    }

    async cargarDatosAsignatura(asignaturaId) {
        try {
            const response = await fetch(`/gestion-carreras/asignaturas/${asignaturaId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Error al cargar los datos de la asignatura');
            }

            const result = await response.json();

            if (result.success) {
                // Rellenar el formulario con los datos
                document.getElementById('nombreAsignatura').value = result.data.nombreAsignatura || '';
                document.getElementById('codAsignatura').value = result.data.codAsignatura || '';
                document.getElementById('Semestre').value = result.data.Semestre || '';
                document.getElementById('idTipoPractica').value = result.data.idTipoPractica || '';
            } else {
                throw new Error(result.message || 'Error al cargar los datos');
            }

        } catch (error) {
            console.error('Error al cargar asignatura:', error);
            this.showAlert('Error', 'No se pudo cargar la asignatura', 'error');
            this.cerrarModalAsignatura();
        }
    }

    cerrarModalAsignatura() {
        if (!this.asignaturaModal) return;

        // Ocultar modal
        this.asignaturaModal.classList.add('hidden');
        this.asignaturaModal.classList.remove('flex', 'items-center', 'justify-center');

        // Limpiar formulario
        if (this.asignaturaForm) {
            this.asignaturaForm.reset();
            delete this.asignaturaForm.dataset.isEdit;
            delete this.asignaturaForm.dataset.asignaturaId;
        }
    }

    async handleAsignaturaSubmit(e) {
        e.preventDefault();

        if (!this.validateAsignaturaForm()) return;

        const form = this.asignaturaForm || document.getElementById('asignaturaForm');
        if (!form) {
            console.error('Formulario asignaturaForm no encontrado');
            this.showAlert('Error', 'Error al encontrar el formulario', 'error');
            return;
        }

        const formData = new FormData(form);
        const isEdit = form.dataset.isEdit === 'true';
        const asignaturaId = form.dataset.asignaturaId;

        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton ? (submitButton.textContent || 'Guardar Asignatura') : 'Guardar Asignatura';

        try {
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Guardando...';
            }

            const url = isEdit
                ? `/gestion-carreras/asignaturas/${asignaturaId}`
                : '/gestion-carreras/asignaturas';

            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error en la petición');
            }

            const data = await response.json();

            if (data.success) {
                this.cerrarModalAsignatura();
                this.showAlert('¡Éxito!', data.message || 'Asignatura guardada correctamente', 'success');

                // Recargar la tabla de asignaturas si existe
                if (typeof this.recargarTablaAsignaturas === 'function') {
                    await this.recargarTablaAsignaturas();
                }
            } else {
                this.showAlert('Error', data.message || 'Error al guardar la asignatura', 'error');
            }

        } catch (error) {
            console.error('Error completo:', error);
            this.showAlert('Error', 'Error al guardar asignatura: ' + error.message, 'error');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        }
    }

    validateAsignaturaForm() {
        const nombreAsignatura = document.getElementById('nombreAsignatura')?.value?.trim();
        const codAsignatura = document.getElementById('codAsignatura')?.value?.trim();
        const semestre = document.getElementById('Semestre')?.value;
        const idTipoPractica = document.getElementById('idTipoPractica')?.value;

        if (!nombreAsignatura) {
            this.showAlert('Error', 'Por favor, ingresa el nombre de la asignatura.', 'error');
            return false;
        }

        if (!codAsignatura) {
            this.showAlert('Error', 'Por favor, ingresa el código de la asignatura.', 'error');
            return false;
        }

        if (!semestre) {
            this.showAlert('Error', 'Por favor, selecciona el semestre.', 'error');
            return false;
        }

        const semestreNum = parseInt(semestre);
        if (isNaN(semestreNum) || semestreNum < 1 || semestreNum > 12) {
            this.showAlert('Error', 'El semestre debe estar entre 1 y 12.', 'error');
            return false;
        }

        if (!idTipoPractica) {
            this.showAlert('Error', 'Por favor, selecciona el tipo de práctica.', 'error');
            return false;
        }

        return true;
    }

    async eliminarAsignatura(asignaturaId, nombreAsignatura) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            html: `¿Estás seguro de eliminar la asignatura "<strong>${nombreAsignatura}</strong>"?<br>Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/gestion-carreras/asignaturas/${asignaturaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Error al eliminar');

            const data = await response.json();

            if (data.success) {
                this.showAlert('¡Eliminado!', data.message || 'Asignatura eliminada correctamente', 'success');

                // Recargar la tabla de asignaturas
                if (typeof this.recargarTablaAsignaturas === 'function') {
                    await this.recargarTablaAsignaturas();
                }
            } else {
                this.showAlert('Error', data.message || 'Error al eliminar la asignatura', 'error');
            }

        } catch (error) {
            console.error('Error al eliminar asignatura:', error);
            this.showAlert('Error', 'Error al eliminar la asignatura', 'error');
        }
    }

    async cargarAsignaturasPorSedeCarrera(sedeCarreraId) {
        try {
            const response = await fetch(`/gestion-carreras/sede-carreras/${sedeCarreraId}/asignaturas`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Error al cargar asignaturas');
            }

            const data = await response.json();

            if (data.success) {
                return data.data;
            } else {
                throw new Error(data.message || 'Error al cargar asignaturas');
            }

        } catch (error) {
            console.error('Error al cargar asignaturas:', error);
            this.showAlert('Error', 'Error al cargar las asignaturas', 'error');
            return [];
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
                        <a href="/storage/${malla.doc}" 
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

function initArchivosPage() {
    initArchivosPreview();

    const toggleModal = (modal, show) => {
        if (!modal) return;
        modal.classList.toggle('hidden', !show);
        if (show) {
            modal.classList.add('flex', 'items-center', 'justify-center');
        } else {
            modal.classList.remove('flex', 'items-center', 'justify-center');
        }
    };

    const resetMallaFormMode = () => {
        if (!mallaForm) return;
        delete mallaForm.dataset.isEdit;
        delete mallaForm.dataset.mallaId;
        mallaForm.action = '/gestion-carreras/malla';

        const modalTitle = document.getElementById('mallaModalTitle');
        if (modalTitle) modalTitle.textContent = 'Gestionar Malla Curricular';

        const submitBtn = mallaForm.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.textContent = 'Guardar Malla';
    };

    // --------- MALLA ----------
    const mallaModal = document.getElementById('mallaModal');
    const mallaForm = document.getElementById('mallaForm');
    const archivoInput = document.getElementById('archivoPdf');
    const archivoPreview = document.getElementById('archivoSeleccionado');
    const archivoNombre = document.getElementById('nombreArchivoSeleccionado');
    const anioInput = document.getElementById('anioMalla');
    const nombreInput = document.getElementById('nombreMalla');
    const idSedeInput = document.getElementById('mallaIdSedeCarrera');

    // Abrir modal de malla (modo crear)
    document.querySelectorAll('[data-open-malla]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (idSedeInput) idSedeInput.value = btn.dataset.idSedeCarrera || btn.dataset.id;
            if (anioInput) anioInput.value = new Date().getFullYear();
            if (nombreInput) nombreInput.value = '';
            if (archivoInput) archivoInput.value = '';
            if (archivoPreview) archivoPreview.classList.add('hidden');

            resetMallaFormMode();

            toggleModal(mallaModal, true);
            setTimeout(() => anioInput?.focus(), 100);
        });
    });

    // Cerrar modal de malla
    document.querySelectorAll('[data-action="close-malla-modal"]').forEach(btn =>
        btn.addEventListener('click', () => {
            toggleModal(mallaModal, false);
            resetMallaFormMode();
        })
    );

    // Validación y preview del archivo
    archivoInput?.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) return archivoPreview?.classList.add('hidden');

        if (file.type !== 'application/pdf') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permiten archivos PDF.',
                confirmButtonColor: '#3085d6'
            });
            archivoInput.value = '';
            archivoPreview?.classList.add('hidden');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El archivo no debe superar los 2MB.',
                confirmButtonColor: '#3085d6'
            });
            archivoInput.value = '';
            archivoPreview?.classList.add('hidden');
            return;
        }

        if (archivoNombre) {
            archivoNombre.textContent = `${file.name} (${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
        }
        archivoPreview?.classList.remove('hidden');
    });

    // Cerrar modal al hacer clic en el fondo
    mallaModal?.addEventListener('click', (e) => {
        if (e.target === mallaModal) {
            toggleModal(mallaModal, false);
            resetMallaFormMode();
        }
    });

    const validateMallaForm = (isEdit = false) => {
        const anio = anioInput?.value;
        const nombre = nombreInput?.value?.trim();
        const archivo = archivoInput?.files?.[0];

        if (!anio) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor, ingresa un año académico.',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        if (!nombre) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor, ingresa el nombre de la malla.',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        if (!isEdit && !archivo) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo requerido',
                text: 'Por favor, selecciona un archivo PDF.',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        if (archivo && archivo.type !== 'application/pdf') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permiten archivos PDF.',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        if (archivo && archivo.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El archivo no debe superar los 2MB.',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        return true;
    };

    // Manejar crear/editar malla
    if (mallaForm) {
        mallaForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const isEdit = mallaForm.dataset.isEdit === 'true';
            const mallaId = mallaForm.dataset.mallaId;
            if (!validateMallaForm(isEdit)) return;

            const formData = new FormData(mallaForm);
            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            const submitButton = mallaForm.querySelector('button[type="submit"]');
            const originalText = submitButton?.textContent || 'Guardar Malla';

            try {
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = isEdit ? 'Actualizando...' : 'Guardando...';
                }

                const url = isEdit
                    ? `/gestion-carreras/malla/${mallaId}`
                    : '/gestion-carreras/malla';

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: isEdit ? 'Malla curricular actualizada correctamente' : 'Malla curricular guardada correctamente',
                        confirmButtonColor: '#3085d6'
                    });
                    toggleModal(mallaModal, false);
                    resetMallaFormMode();
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Error al guardar la malla');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar la malla: ' + error.message,
                    confirmButtonColor: '#3085d6'
                });
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            }
        });
    }

    // Editar malla
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action="edit-malla"]');
        if (!btn) return;

        e.preventDefault();

        if (idSedeInput) idSedeInput.value = btn.dataset.idSedeCarrera;
        if (nombreInput) nombreInput.value = btn.dataset.nombre || '';
        if (anioInput) anioInput.value = btn.dataset.anio || new Date().getFullYear();
        if (archivoInput) archivoInput.value = '';
        if (archivoPreview) archivoPreview.classList.add('hidden');

        if (mallaForm) {
            mallaForm.dataset.isEdit = 'true';
            mallaForm.dataset.mallaId = btn.dataset.id;
            mallaForm.action = `/gestion-carreras/malla/${btn.dataset.id}`;
        }

        const modalTitle = document.getElementById('mallaModalTitle');
        if (modalTitle) modalTitle.textContent = 'Editar Malla Curricular';

        const submitBtn = mallaForm?.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.textContent = 'Actualizar Malla';

        toggleModal(mallaModal, true);
        setTimeout(() => nombreInput?.focus(), 100);
    });

    // Eliminar malla
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action="delete-malla"]');
        if (!btn) return;

        e.preventDefault();
        const { id, nombre } = btn.dataset;

        const result = await Swal.fire({
            title: '¿Estás seguro?',
            html: `¿Estás seguro de eliminar la malla "<strong>${nombre}</strong>"?<br>Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            const response = await fetch(`/gestion-carreras/malla/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: 'Malla eliminada correctamente',
                    confirmButtonColor: '#3085d6'
                });
                window.location.reload();
            } else {
                throw new Error(data.message || 'Error al eliminar la malla');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al eliminar la malla',
                confirmButtonColor: '#3085d6'
            });
        }
    });

    // --------- PROGRAMA ----------
    const programaModal = document.getElementById('programaModal');
    const programaForm = document.getElementById('programaForm');
    const programaNombre = document.getElementById('programaNombre');
    const programaAsignaturaName = document.getElementById('programaAsignaturaName');

    // --------- ASIGNATURA ----------
    const asignaturaModal = document.getElementById('asignaturaModal');
    const asignaturaForm = document.getElementById('asignaturaForm');

    // Abrir modal de asignatura (modo crear)
    document.querySelectorAll('[data-open-asignatura]').forEach(btn => {
        btn.addEventListener('click', () => {
            const idSedeCarrera = btn.dataset.idSedeCarrera;

            if (window.sedeCarreraManager && typeof window.sedeCarreraManager.abrirModalAsignatura === 'function') {
                window.sedeCarreraManager.abrirModalAsignatura(idSedeCarrera);
            } else {
                // Fallback: abrir modal manualmente si sedeCarreraManager no está disponible
                if (asignaturaModal && asignaturaForm) {
                    const idInput = document.getElementById('asignaturaIdSedeCarrera');
                    if (idInput) idInput.value = idSedeCarrera;

                    asignaturaForm.reset();
                    delete asignaturaForm.dataset.isEdit;
                    delete asignaturaForm.dataset.asignaturaId;

                    const modalTitle = document.getElementById('asignaturaModalTitle');
                    if (modalTitle) modalTitle.textContent = 'Crear Asignatura';

                    const submitBtn = asignaturaForm.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.textContent = 'Guardar Asignatura';

                    toggleModal(asignaturaModal, true);
                    setTimeout(() => {
                        const nombreInput = document.getElementById('nombreAsignatura');
                        if (nombreInput) nombreInput.focus();
                    }, 100);
                }
            }
        });
    });

    // Cerrar modal de asignatura
    document.querySelectorAll('[data-action="close-asignatura-modal"]').forEach(btn =>
        btn.addEventListener('click', () => {
            if (window.sedeCarreraManager && typeof window.sedeCarreraManager.cerrarModalAsignatura === 'function') {
                window.sedeCarreraManager.cerrarModalAsignatura();
            } else {
                toggleModal(asignaturaModal, false);
            }
        })
    );

    // Cerrar modal al hacer clic en el fondo
    asignaturaModal?.addEventListener('click', (e) => {
        if (e.target === asignaturaModal) {
            if (window.sedeCarreraManager && typeof window.sedeCarreraManager.cerrarModalAsignatura === 'function') {
                window.sedeCarreraManager.cerrarModalAsignatura();
            } else {
                toggleModal(asignaturaModal, false);
            }
        }
    });

    // Manejar submit del formulario de asignatura
    if (asignaturaForm) {
        asignaturaForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (window.sedeCarreraManager && typeof window.sedeCarreraManager.handleAsignaturaSubmit === 'function') {
                await window.sedeCarreraManager.handleAsignaturaSubmit(e);
            } else {
                // Fallback: enviar formulario manualmente
                const formData = new FormData(asignaturaForm);
                const isEdit = asignaturaForm.dataset.isEdit === 'true';
                const asignaturaId = asignaturaForm.dataset.asignaturaId;

                const submitButton = asignaturaForm.querySelector('button[type="submit"]');
                const originalText = submitButton?.textContent || 'Guardar';

                try {
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Guardando...';
                    }

                    const url = isEdit
                        ? `/gestion-carreras/asignaturas/${asignaturaId}`
                        : '/gestion-carreras/asignaturas';

                    if (isEdit) {
                        formData.append('_method', 'PUT');
                    }

                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        toggleModal(asignaturaModal, false);
                        await Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message || 'Asignatura guardada correctamente',
                            confirmButtonColor: '#3085d6'
                        });
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Error al guardar');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Error al guardar la asignatura',
                        confirmButtonColor: '#3085d6'
                    });
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    }
                }
            }
        });
    }

    // Editar asignatura
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action="edit-asignatura"]');
        if (!btn) return;

        e.preventDefault();
        const asignaturaId = btn.dataset.id;
        const idSedeCarrera = btn.dataset.idSedeCarrera;

        if (window.sedeCarreraManager && typeof window.sedeCarreraManager.abrirModalAsignatura === 'function') {
            await window.sedeCarreraManager.abrirModalAsignatura(idSedeCarrera, asignaturaId);
        } else {
            // Fallback: cargar y mostrar datos manualmente
            try {
                const response = await fetch(`/gestion-carreras/asignaturas/${asignaturaId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Error al cargar los datos');

                const result = await response.json();

                if (result.success && asignaturaForm) {
                    document.getElementById('nombreAsignatura').value = result.data.nombreAsignatura || '';
                    document.getElementById('codAsignatura').value = result.data.codAsignatura || '';
                    document.getElementById('Semestre').value = result.data.Semestre || '';
                    document.getElementById('idTipoPractica').value = result.data.idTipoPractica || '';

                    const idInput = document.getElementById('asignaturaIdSedeCarrera');
                    if (idInput) idInput.value = idSedeCarrera;

                    asignaturaForm.dataset.isEdit = 'true';
                    asignaturaForm.dataset.asignaturaId = asignaturaId;

                    const modalTitle = document.getElementById('asignaturaModalTitle');
                    if (modalTitle) modalTitle.textContent = 'Editar Asignatura';

                    const submitBtn = asignaturaForm.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.textContent = 'Actualizar Asignatura';

                    toggleModal(asignaturaModal, true);
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la asignatura',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    });

    // Eliminar asignatura
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action="delete-asignatura"]');
        if (!btn) return;

        e.preventDefault();
        const asignaturaId = btn.dataset.id;
        const nombreAsignatura = btn.dataset.nombre;

        if (window.sedeCarreraManager && typeof window.sedeCarreraManager.eliminarAsignatura === 'function') {
            await window.sedeCarreraManager.eliminarAsignatura(asignaturaId, nombreAsignatura);
        } else {
            // Fallback: eliminar manualmente
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                html: `¿Estás seguro de eliminar la asignatura "<strong>${nombreAsignatura}</strong>"?<br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`/gestion-carreras/asignaturas/${asignaturaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message || 'Asignatura eliminada correctamente',
                        confirmButtonColor: '#3085d6'
                    });
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Error al eliminar');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al eliminar la asignatura',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    });

    // Método para recargar la página después de operaciones exitosas en asignaturas
    if (window.sedeCarreraManager) {
        window.sedeCarreraManager.recargarTablaAsignaturas = () => {
            window.location.reload();
        };
    }

    document.querySelectorAll('[data-open-programa]').forEach(btn => {
        btn.addEventListener('click', () => {
            const asignaturaId = btn.dataset.idAsignatura;
            const nombreAsignatura = btn.dataset.nombreAsignatura || 'Asignatura';

            if (programaForm) {
                programaForm.action = `/gestion-carreras/asignaturas/${asignaturaId}/programa`;
                programaForm.reset();
            }

            if (programaAsignaturaName) programaAsignaturaName.textContent = nombreAsignatura;

            // Limpiar preview de archivo
            const programaArchivoPreview = document.getElementById('programaArchivoSeleccionado');
            if (programaArchivoPreview) programaArchivoPreview.classList.add('hidden');

            toggleModal(programaModal, true);
            setTimeout(() => {
                const programaArchivoInput = document.getElementById('programaArchivo');
                if (programaArchivoInput) programaArchivoInput.focus();
            }, 100);
        });
    });

    // Validación y preview del archivo de programa
    const programaArchivoInput = document.getElementById('programaArchivo');
    const programaArchivoPreview = document.getElementById('programaArchivoSeleccionado');
    const programaNombreArchivo = document.getElementById('programaNombreArchivo');

    programaArchivoInput?.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) {
            programaArchivoPreview?.classList.add('hidden');
            return;
        }

        if (file.type !== 'application/pdf') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permiten archivos PDF.',
                confirmButtonColor: '#3085d6'
            });
            programaArchivoInput.value = '';
            programaArchivoPreview?.classList.add('hidden');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El archivo no debe superar los 2MB.',
                confirmButtonColor: '#3085d6'
            });
            programaArchivoInput.value = '';
            programaArchivoPreview?.classList.add('hidden');
            return;
        }

        if (programaNombreArchivo) {
            programaNombreArchivo.textContent = `${file.name} (${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
        }
        programaArchivoPreview?.classList.remove('hidden');
    });

    // Manejar envío del formulario de programa
    if (programaForm) {
        programaForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const archivo = programaArchivoInput?.files?.[0];

            if (!archivo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivo requerido',
                    text: 'Por favor, selecciona un archivo PDF.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const submitBtn = programaForm.querySelector('button[type="submit"]');
            const originalText = submitBtn?.textContent || 'Guardar Programa';

            try {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Guardando...';
                }

                const formData = new FormData(programaForm);
                const response = await fetch(programaForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    toggleModal(programaModal, false);
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message || 'Programa guardado correctamente',
                        confirmButtonColor: '#3085d6'
                    });
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Error al guardar el programa');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al guardar el programa',
                    confirmButtonColor: '#3085d6'
                });
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            }
        });
    }

    document.querySelectorAll('[data-action="close-programa-modal"]').forEach(btn =>
        btn.addEventListener('click', () => toggleModal(programaModal, false))
    );

    programaModal?.addEventListener('click', (e) => {
        if (e.target === programaModal) toggleModal(programaModal, false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleModal(mallaModal, false);
            toggleModal(programaModal, false);
            toggleModal(asignaturaModal, false);
        }
    });


    function initArchivosPreview() {
        const pdfModal = document.getElementById('pdfPreviewModal');
        if (!pdfModal) return;

        const pdfViewer = document.getElementById('pdfViewer');
        const pdfModalTitle = document.getElementById('pdfModalTitle');
        const pdfModalInfo = document.getElementById('pdfModalInfo');
        const pdfDownloadBtn = document.getElementById('pdfDownloadBtn');
        const pdfFallbackLink = document.getElementById('pdfFallbackLink');

        // Manejar clic en botones de previsualización de MALLA
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-action="preview-malla"]');
            if (!btn) return;

            e.preventDefault();
            const url = btn.dataset.url;
            const title = btn.dataset.title || 'Malla Curricular';
            const info = btn.dataset.info || '';

            if (pdfViewer) pdfViewer.src = url;
            if (pdfModalTitle) pdfModalTitle.textContent = title;
            if (pdfModalInfo) pdfModalInfo.textContent = info;
            if (pdfDownloadBtn) pdfDownloadBtn.href = url;
            if (pdfFallbackLink) pdfFallbackLink.href = url;

            pdfModal.classList.remove('hidden');
        });

        // Manejar clic en botones de previsualización de PROGRAMA
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-action="preview-programa"]');
            if (!btn) return;

            e.preventDefault();
            const url = btn.dataset.url;
            const title = btn.dataset.title || 'Programa de Asignatura';
            const asignatura = btn.dataset.asignatura || '';
            const fecha = btn.dataset.fecha || '';
            const info = `${asignatura} · Subida el ${fecha}`;

            if (pdfViewer) pdfViewer.src = url;
            if (pdfModalTitle) pdfModalTitle.textContent = title;
            if (pdfModalInfo) pdfModalInfo.textContent = info;
            if (pdfDownloadBtn) pdfDownloadBtn.href = url;
            if (pdfFallbackLink) pdfFallbackLink.href = url;

            pdfModal.classList.remove('hidden');
        });

        // Cerrar modal
        const closeBtn = pdfModal.querySelector('[data-action="close-pdf-modal"]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                pdfModal.classList.add('hidden');
                if (pdfViewer) pdfViewer.src = '';
            });
        }

        // Cerrar al hacer clic en el fondo
        pdfModal.addEventListener('click', (e) => {
            if (e.target === pdfModal) {
                pdfModal.classList.add('hidden');
                if (pdfViewer) pdfViewer.src = '';
            }
        });

        // Cerrar con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !pdfModal.classList.contains('hidden')) {
                pdfModal.classList.add('hidden');
                if (pdfViewer) pdfViewer.src = '';
            }
        });
    }


}


document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('selection-container')) {
        window.sedeCarreraManager = new SedeCarreraManager();
    }

    initArchivosPage();
});

export default SedeCarreraManager;