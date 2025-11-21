import BaseModalManager from './base-modal-manager.js';

/**
 * Sede Carrera Manager
 * Extiende BaseModalManager
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

        this.selectionContainer = document.getElementById('selection-container');
        if (!this.selectionContainer) return;

        this.gestionContainer = document.getElementById('gestion-container');
        this.sedeNamePlaceholder = document.getElementById('sede-name-placeholder');
        
        // Asignar tableContainer manualmente porque está dentro de un contenedor oculto
        this.tableContainer = document.getElementById('tabla-container');
        
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
            <select id="centro-selector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Seleccione centro --</option>
            </select>
        `;

        grid.children[1].innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-2">Sede</label>
            <select id="sede-selector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>
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

        const createBtn = document.querySelector('[data-modal-target="crudModal"]');
        if (createBtn) {
            createBtn.addEventListener('click', () => this.showCreateModal());
        }
    }

    handleCentroChange() {
        const centroId = this.centroSelector.value;
        this.hideGestion();
        this.updateSedeSelector(centroId);
        this.currentSedeId = null;
    }

    async handleSedeChange() {
        const selectedValue = this.sedeSelector.value;
        
        // Ignorar valores vacíos o el texto por defecto
        if (!selectedValue || selectedValue === '-- Seleccione sede --') {
            this.hideGestion();
            this.currentSedeId = null;
            return;
        }

        this.currentSedeId = selectedValue;
        this.updateSedeName();
        this.showGestion();
        await this.refreshTable();
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

    showGestion() {
        this.gestionContainer?.classList.remove('hidden');
    }

    hideGestion() {
        this.gestionContainer?.classList.add('hidden');
        if (this.tableContainer) this.tableContainer.innerHTML = '';
    }

    async refreshTable() {
        if (!this.tableContainer || !this.currentSedeId) return;

        this.tableContainer.innerHTML = `
            <div class="text-center p-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Cargando carreras...</p>
            </div>
        `;

        try {
            const response = await fetch(`${this.config.baseUrl}/sedes/${this.currentSedeId}/tabla-html`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) throw new Error('Error al cargar tabla');

            const html = await response.text();
            this.tableContainer.innerHTML = html;
        } catch (error) {
            console.error('Error al refrescar tabla:', error);
            this.tableContainer.innerHTML = `
                <div class="text-center p-8 text-red-600">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                    <p>Error al cargar las carreras</p>
                </div>
            `;
        }
    }

    showCreateModal() {
        if (!this.currentSedeId) {
            this.showAlert('Error', 'Selecciona una sede primero', 'error');
            return;
        }

        this.limpiarFormulario();
        this.form.querySelector('[name="idSede"]').value = this.currentSedeId;
        this.setModalTitle('Añadir Carrera');
        this.setButtonText('Guardar');
        this.mostrarModal();
    }

    async editarRegistro(id) {
        try {
            this.setModalTitle('Cargando...');
            this.mostrarModal();

            const response = await fetch(`${this.config.baseUrl}/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            if (!data.success) throw new Error(data.message || 'Error al cargar');

            // Rellenar formulario
            const sedeCarrera = data.data;
            this.form.querySelector('[name="idSede"]').value = sedeCarrera.idSede;
            this.form.querySelector('[name="idCarrera"]').value = sedeCarrera.idCarrera;
            this.form.querySelector('[name="nombreSedeCarrera"]').value = sedeCarrera.nombreSedeCarrera || '';
            this.form.querySelector('[name="codigoCarrera"]').value = sedeCarrera.codigoCarrera;

            // Configurar para edición
            this.form.dataset.id = id;
            let methodInput = this.form.querySelector('[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                this.form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            this.setModalTitle('Editar Carrera');
            this.setButtonText('Actualizar');

        } catch (error) {
            console.error('Error al editar:', error);
            this.showAlert('Error', 'No se pudo cargar la carrera', 'error');
            this.cerrarModal();
        }
    }

    showValidationErrors(errors) {
        this.clearValidationErrors();
        for (const field in errors) {
            const input = this.form.querySelector(`[name="${field}"]`);
            const errorDiv = document.getElementById(`error-${field}`);
            if (input) {
                input.classList.add('border-red-500');
            }
            if (errorDiv) {
                errorDiv.textContent = errors[field][0];
                errorDiv.classList.remove('hidden');
            }
        }
    }

    clearValidationErrors() {
        this.form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });
        this.form.querySelectorAll('[id^="error-"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    validate() {
        const idSede = this.form.querySelector('[name="idSede"]')?.value;
        const idCarrera = this.form.querySelector('[name="idCarrera"]')?.value;
        const codigoCarrera = this.form.querySelector('[name="codigoCarrera"]')?.value;

        if (!idSede || !idCarrera || !codigoCarrera) {
            this.showAlert('Error', 'Por favor complete todos los campos obligatorios', 'error');
            return false;
        }
        return true;
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        if (!this.validate()) return;

        const formData = new FormData(this.form);
        const isUpdate = this.form.querySelector('[name="_method"]')?.value === 'PUT';
        const id = this.form.dataset.id;

        const url = isUpdate ? `${this.config.baseUrl}/${id}` : this.config.baseUrl;

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                this.cerrarModal();
                this.showAlert('¡Éxito!', data.message || 'Guardado correctamente', 'success');
                await this.refreshTable();
            } else {
                if (data.errors) {
                    this.showValidationErrors(data.errors);
                } else {
                    this.showAlert('Error', data.message || 'Error al guardar', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'Error de conexión', 'error');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('selection-container')) {
        window.sedeCarreraManager = new SedeCarreraManager();
    }
});

export default SedeCarreraManager;