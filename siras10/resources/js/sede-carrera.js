import BaseModalManager from './base-modal-manager.js';

/**
 * SedeCarrera Manager
 * Extiende BaseModalManager para gestión de carreras por sede
 */
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

        // Inicializar solo si existen los elementos necesarios
        this.selectionContainer = document.getElementById('selection-container');
        if (!this.selectionContainer) return;

        this.initializeSedeCarreraSpecifics();
    }

    /**
     * Inicializa la funcionalidad específica de sede-carrera
     */
    initializeSedeCarreraSpecifics() {
        // Elementos del DOM específicos
        this.gestionContainer = document.getElementById('gestion-container');
        this.sedeNamePlaceholder = document.getElementById('sede-name-placeholder');
        
        // Datos
        this.centros = JSON.parse(this.selectionContainer.dataset.centros || '[]');
        this.carreras = JSON.parse(this.selectionContainer.dataset.carreras || '[]');
        this.currentSedeId = null;

        // Crear selectores dinámicamente
        this.createSelectors();
        
        // Inicializar datos en selectores
        this.populateSelectors();
        
        // Event listeners específicos
        this.attachSedeCarreraEvents();
    }

    /**
     * Crea los selectores dinámicamente
     */
    createSelectors() {
        const gridContainer = this.selectionContainer.querySelector('.grid');
        
        // Centro Formador
        const centroContainer = gridContainer.children[0];
        centroContainer.innerHTML = `
            <label for="centro-formador-selector" class="block text-sm font-medium text-gray-700 mb-2">
                Centro Formador
            </label>
            <select id="centro-formador-selector" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">-- Seleccione un centro formador --</option>
            </select>
        `;

        // Sede
        const sedeContainer = gridContainer.children[1];
        sedeContainer.innerHTML = `
            <label for="sede-selector" class="block text-sm font-medium text-gray-700 mb-2">
                Sede
            </label>
            <select id="sede-selector" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" disabled>
                <option value="">-- Primero seleccione un centro --</option>
            </select>
        `;

        this.centroSelector = document.getElementById('centro-formador-selector');
        this.sedeSelector = document.getElementById('sede-selector');
    }

    /**
     * Llena los selectores con datos
     */
    populateSelectors() {
        // Llenar centros
        this.centros.forEach(centro => {
            const option = new Option(centro.nombreCentroFormador, centro.idCentroFormador);
            this.centroSelector.add(option);
        });

        // Llenar carreras en modal
        this.populateCarrerasModal();
    }

    /**
     * Llena el selector de carreras en el modal
     */
    populateCarrerasModal() {
        const carreraSelector = document.getElementById('idCarrera');
        if (!carreraSelector) return;

        carreraSelector.innerHTML = '<option value="">-- Seleccione una carrera --</option>';
        this.carreras.forEach(carrera => {
            const option = new Option(carrera.nombreCarrera, carrera.idCarrera);
            carreraSelector.add(option);
        });
    }

    /**
     * Event listeners específicos
     */
    attachSedeCarreraEvents() {
        this.centroSelector.addEventListener('change', () => this.handleCentroChange());
        this.sedeSelector.addEventListener('change', () => this.handleSedeChange());

        // Sobrescribir comportamiento del botón añadir
        const addButton = document.querySelector('[data-modal-target="crudModal"]');
        if (addButton) {
            addButton.addEventListener('click', () => this.prepareCreateModal());
        }
    }

    /**
     * Maneja cambio de centro formador
     */
    handleCentroChange() {
        const centroId = this.centroSelector.value;
        this.hideGestionContainer();
        this.updateSedeSelector(centroId);
        this.currentSedeId = null;
    }

    /**
     * Maneja cambio de sede
     */
    async handleSedeChange() {
        this.currentSedeId = this.sedeSelector.value;
        
        if (!this.currentSedeId) {
            this.hideGestionContainer();
            return;
        }

        this.updateSedeNameDisplay();
        this.showGestionContainer();
        await this.loadSedeCarrerasTable();
    }

    /**
     * Actualiza selector de sedes según centro seleccionado
     */
    updateSedeSelector(centroId) {
        this.sedeSelector.innerHTML = '<option value="">-- Seleccione una sede --</option>';
        this.sedeSelector.disabled = true;

        if (!centroId) return;

        const centro = this.centros.find(c => c.idCentroFormador == centroId);
        if (centro?.sedes?.length > 0) {
            centro.sedes.forEach(sede => {
                const option = new Option(sede.nombreSede, sede.idSede);
                this.sedeSelector.add(option);
            });
            this.sedeSelector.disabled = false;
        } else {
            this.sedeSelector.innerHTML = '<option value="">No hay sedes disponibles</option>';
        }
    }

    /**
     * Actualiza el nombre de la sede en el encabezado
     */
    updateSedeNameDisplay() {
        if (this.sedeNamePlaceholder) {
            const selectedSedeName = this.sedeSelector.options[this.sedeSelector.selectedIndex].text;
            this.sedeNamePlaceholder.textContent = selectedSedeName;
        }
    }

    /**
     * Muestra el contenedor de gestión
     */
    showGestionContainer() {
        if (this.gestionContainer) {
            this.gestionContainer.classList.remove('hidden');
        }
    }

    /**
     * Oculta el contenedor de gestión
     */
    hideGestionContainer() {
        if (this.gestionContainer) {
            this.gestionContainer.classList.add('hidden');
        }
        if (this.tableContainer) {
            this.tableContainer.innerHTML = '';
        }
    }

    /**
     * Carga la tabla de carreras de la sede
     */
    async loadSedeCarrerasTable() {
        if (!this.tableContainer) {
            this.tableContainer = document.getElementById('tabla-container');
            if (!this.tableContainer) {
                console.error('No se encontró el contenedor de tabla');
                return;
            }
        }

        this.tableContainer.innerHTML = '<div class="text-center p-8 text-gray-500">Cargando carreras...</div>';
        
        try {
            const response = await fetch(`/gestion-carreras/sedes/${this.currentSedeId}/tabla-html`, {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }

            const html = await response.text();
            this.tableContainer.innerHTML = html;

        } catch (error) {
            console.error('Error al cargar tabla:', error);
            this.tableContainer.innerHTML = `
                <div class="text-center p-8 text-red-500">
                    <p>Error al cargar las carreras.</p>
                    <p class="text-sm mt-2">${error.message}</p>
                </div>
            `;
        }
    }

    /**
     * Prepara el modal para crear nueva carrera
     */
    prepareCreateModal() {
        if (!this.currentSedeId) {
            this.showAlert('Advertencia', 'Debe seleccionar una sede primero.', 'warning');
            return;
        }
        
        this.limpiarFormulario();
        const idSedeField = document.querySelector('#crudForm input[name="idSede"]');
        if (idSedeField) {
            idSedeField.value = this.currentSedeId;
        }
    }

    /**
     * Validación personalizada
     */
    validate() {
        const idSede = this.form.querySelector('[name="idSede"]')?.value;
        const idCarrera = this.form.querySelector('[name="idCarrera"]')?.value;

        if (!idSede) {
            this.showAlert('Error', 'No se ha seleccionado una sede válida.', 'error');
            return false;
        }

        if (!idCarrera) {
            this.showAlert('Error', 'Debe seleccionar una carrera base.', 'error');
            return false;
        }

        return true;
    }

    /**
     * Sobrescribe refreshTable para actualizar tabla de carreras
     */
    async refreshTable() {
        if (this.currentSedeId) {
            await this.loadSedeCarrerasTable();
        }
    }

    /**
     * Sobrescribe onSuccess con mensaje personalizado
     */
    onSuccess(data) {
        this.cerrarModal();
        this.showAlert('¡Éxito!', data.message || 'Operación completada correctamente', 'success');
        this.refreshTable();
    }
}

// Inicializar solo si existe el formulario
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('selection-container')) {
        new SedeCarreraManager();
    }
});