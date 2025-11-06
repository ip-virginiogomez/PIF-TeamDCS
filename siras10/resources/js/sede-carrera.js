import BaseModalManager from './base-modal-manager.js';

class SedeCarreraManager {
    constructor() {
        this.selectionContainer = document.getElementById('selection-container');
        if (!this.selectionContainer) return;

        // --- Selectores del DOM ---
        this.centroSelector = document.getElementById('centro-formador-selector');
        this.sedeSelector = document.getElementById('sede-selector');
        this.gestionContainer = document.getElementById('gestion-container');
        this.tableContainer = document.getElementById('tabla-container'); // Contenedor específico para la tabla
        this.sedeNamePlaceholder = document.getElementById('sede-name-placeholder'); // Span para el nombre de la sede
        this.idSedeField = document.querySelector('#crudForm input[name="idSede"]');

        // --- Datos y Estado ---
        this.centros = JSON.parse(this.selectionContainer.dataset.centros);
        this.crudManager = null;
        this.currentSedeId = null;

        this.attachEventListeners();
        this.initializeCRUDManager();
    }

    /**
     * Asigna los listeners de eventos principales.
     */
    attachEventListeners() {
        this.centroSelector.addEventListener('change', () => this.handleCentroChange());
        this.sedeSelector.addEventListener('change', () => this.handleSedeChange());

        // Listener para inyectar el idSede correcto antes de abrir el modal de creación.
        // Esto asegura que el BaseModalManager siempre tenga el contexto correcto.
        const addButton = document.querySelector('[data-modal-target="crudModal"]');
        if (addButton) {
            addButton.addEventListener('click', () => {
                if (this.currentSedeId) {
                    this.idSedeField.value = this.currentSedeId;
                }
            });
        }
    }

    /**
     * Maneja el cambio en el selector de Centro, reseteando la vista.
     */
    handleCentroChange() {
        const centroId = this.centroSelector.value;
        this.gestionContainer.classList.add('hidden');
        this.updateSedeSelector(centroId);
    }

    /**
     * Maneja el cambio en el selector de Sede, cargando la tabla de carreras.
     */
    async handleSedeChange() {
        this.currentSedeId = this.sedeSelector.value;
        if (!this.currentSedeId) {
            this.gestionContainer.classList.add('hidden');
            this.tableContainer.innerHTML = '';
            return;
        }

        // Mostrar el contenedor de gestión y actualizar el nombre de la sede
        const selectedSedeName = this.sedeSelector.options[this.sedeSelector.selectedIndex].text;
        this.sedeNamePlaceholder.textContent = selectedSedeName;
        this.gestionContainer.classList.remove('hidden');

        // Cargar solo la tabla
        await this.loadTableContainer();
    }

    async loadTableContainer() {
        if (!this.tableContainer) {
            console.error('El elemento #tabla-container no se encontró en el DOM.');
            return;
        }

        this.tableContainer.innerHTML = `<div class="text-center p-8 text-gray-500">Cargando tabla...</div>`;
        try {
            // Apuntar a la nueva ruta que solo devuelve la tabla
            const url = `/sedes/${this.currentSedeId}/tabla-html`;
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });

            if (!response.ok) {
                throw new Error(`Error al cargar la tabla. Estado: ${response.status}`);
            }

            const htmlResponse = await response.text();
            this.tableContainer.innerHTML = htmlResponse;

            // Reinicializar el gestor de modales para que detecte los nuevos botones de la tabla
            this.initializeCRUDManager();

        } catch (error) {
            console.error('Error en loadTableContainer:', error);
            this.tableContainer.innerHTML = `<div class="text-center p-8 text-red-500">${error.message}</div>`;
        }
    }

    /**
     * Actualiza las opciones del selector de Sede.
     */
    updateSedeSelector(centroId) {
        this.sedeSelector.innerHTML = '<option value="">-- Seleccione una sede --</option>';
        this.sedeSelector.disabled = true;
        if (!centroId) return;

        const centro = this.centros.find(c => c.idCentroFormador == centroId);
        if (centro?.sedes.length > 0) {
            centro.sedes.forEach(sede => this.sedeSelector.add(new Option(sede.nombreSede, sede.idSede)));
            this.sedeSelector.disabled = false;
        } else {
            this.sedeSelector.innerHTML = '<option value="">No hay sedes</option>';
        }
    }

    /**
     * Inicializa el BaseModalManager con la configuración correcta.
     */
    initializeCRUDManager() {
        // Busca el contenedor de la tabla que ahora tiene la URL de refresco
        const tableContainer = document.getElementById('tabla-container');
        if (!tableContainer) return;

        this.crudManager = new BaseModalManager({
            modalId: 'crudModal',
            formId: 'crudForm',
            entityName: 'Carrera',
            entityGender: 'f',
            baseUrl: '/sede-carrera',
            primaryKey: 'idSedeCarrera',
            fields: ['idCarrera', 'nombreSedeCarrera', 'codigoCarrera'],
            tableContainerId: 'tabla-container',
            // El BaseModalManager usará la URL del atributo data-refresh-url
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new SedeCarreraManager();
});