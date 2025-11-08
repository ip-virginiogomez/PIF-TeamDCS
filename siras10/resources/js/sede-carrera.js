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

        this.centros = JSON.parse(this.selectionContainer.dataset.centros || '[]');
        this.carreras = JSON.parse(this.selectionContainer.dataset.carreras || '[]');
        this.currentSedeId = null;

        this.initSedeCarrera();
    }

    initSedeCarrera() {
        this.createSelectors();
        this.populateSelectors();
        this.attachSedeEvents();
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
}


document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('selection-container')) {
        window.sedeCarreraManager = new SedeCarreraManager();
    }
});

export default SedeCarreraManager;