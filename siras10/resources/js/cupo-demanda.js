import BaseModalManager from './base-modal-manager.js';

class CupoDemandaManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'demandaModal',
            formId: 'demandaForm',
            entityName: 'Demanda de Cupo',
            baseUrl: '/cupo-demandas',
            primaryKey: 'idDemandaCupo',
            tableContainerId: 'tabla-demandas',
            fields: ['idPeriodo', 'idSedeCarrera', 'cuposSolicitados', 'asignatura']
        });

        this.initFilter();
        this.initBulkCreate();
        this.initDependencyLogic();
        this.rowIndex = 0;
    }

    initFilter() {
        const periodoFilter = document.getElementById('periodo_filter');
        if (periodoFilter) {
            periodoFilter.addEventListener('change', function () {
                window.location.href = `?periodo_id=${this.value}`;
            });
        }
    } initBulkCreate() {
        const addRowBtn = document.getElementById('addRowBtn');
        if (addRowBtn) {
            addRowBtn.addEventListener('click', () => this.addRow());
        }

        // Event delegation for remove buttons
        const tableBody = document.querySelector('#demandasTable tbody');
        if (tableBody) {
            tableBody.addEventListener('click', (e) => {
                if (e.target.closest('.remove-row-btn')) {
                    e.target.closest('tr').remove();
                }
            });
        }
    }

    initDependencyLogic() {
        // Single Edit
        const singleSedeSelect = document.getElementById('idSedeCarrera');
        const singleAsignaturaSelect = document.getElementById('asignatura');

        if (singleSedeSelect && singleAsignaturaSelect) {
            singleSedeSelect.addEventListener('change', (e) => {
                this.loadAsignaturas(e.target.value, singleAsignaturaSelect);
            });
        }

        // Bulk Create (Event Delegation)
        const tableBody = document.querySelector('#demandasTable tbody');
        if (tableBody) {
            tableBody.addEventListener('change', (e) => {
                if (e.target.classList.contains('sede-carrera-select')) {
                    const row = e.target.closest('tr');
                    const asignaturaSelect = row.querySelector('.asignatura-select');
                    this.loadAsignaturas(e.target.value, asignaturaSelect);
                }
            });
        }
    }

    async loadAsignaturas(sedeCarreraId, targetSelect, selectedValue = null) {
        // Reset select
        targetSelect.innerHTML = '<option value="">Cargando...</option>';
        targetSelect.disabled = true;

        if (!sedeCarreraId) {
            targetSelect.innerHTML = '<option value="">Seleccione Sede-Carrera primero...</option>';
            targetSelect.disabled = false;
            return;
        }

        try {
            // Use baseUrl to ensure consistency with other requests
            const url = `${this.config.baseUrl}/asignaturas/${sedeCarreraId}`;

            const response = await fetch(url);
            if (!response.ok) throw new Error(`Error al cargar asignaturas: ${response.statusText}`);

            const asignaturas = await response.json();

            targetSelect.innerHTML = '<option value="">Seleccione...</option>';

            if (asignaturas.length === 0) {
                const option = document.createElement('option');
                option.text = "No hay asignaturas disponibles";
                targetSelect.appendChild(option);
            }

            asignaturas.forEach(asignatura => {
                const option = document.createElement('option');
                option.value = asignatura.nombreAsignatura;
                option.textContent = asignatura.nombreAsignatura;
                if (selectedValue && asignatura.nombreAsignatura.trim() === selectedValue.trim()) {
                    option.selected = true;
                }
                targetSelect.appendChild(option);
            });
        } catch (error) {
            console.error(error);
            targetSelect.innerHTML = '<option value="">Error al cargar</option>';
        } finally {
            targetSelect.disabled = false;
        }
    } addRow() {
        const template = document.getElementById('demandaRowTemplate');
        const tbody = document.querySelector('#demandasTable tbody');

        if (template && tbody) {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('tr');

            // Replace INDEX placeholder
            row.innerHTML = row.innerHTML.replace(/INDEX/g, this.rowIndex++);

            tbody.appendChild(row);
        }
    }

    /**
     * Sobrescribimos para manejar la UI de creación masiva vs edición simple
     */
    limpiarFormulario() {
        super.limpiarFormulario();

        // Mostrar campos de creación masiva, ocultar edición simple
        document.getElementById('bulk-create-fields').classList.remove('hidden');
        document.getElementById('single-edit-fields').classList.add('hidden');

        // Limpiar tabla
        const tbody = document.querySelector('#demandasTable tbody');
        if (tbody) tbody.innerHTML = '';
        this.rowIndex = 0;

        // Agregar una fila inicial
        this.addRow();

        // Asegurar que el idPeriodo se mantenga
        const periodoFilter = document.getElementById('periodo_filter');
        const hiddenPeriodo = document.querySelector('input[name="idPeriodo"]');
        if (periodoFilter && hiddenPeriodo) {
            hiddenPeriodo.value = periodoFilter.value;
        }

        // Deshabilitar validación HTML5 de los campos ocultos de edición simple
        document.getElementById('idSedeCarrera').removeAttribute('required');
        document.getElementById('cuposSolicitados').removeAttribute('required');
        document.getElementById('asignatura').removeAttribute('required');
    }

    /**
     * Sobrescribimos para manejar la UI de edición simple
     */
    async editarRegistro(id) {
        // Mostrar campos de edición simple, ocultar creación masiva
        document.getElementById('bulk-create-fields').classList.add('hidden');
        document.getElementById('single-edit-fields').classList.remove('hidden');

        // Habilitar validación HTML5 para edición simple
        document.getElementById('idSedeCarrera').setAttribute('required', 'required');
        document.getElementById('cuposSolicitados').setAttribute('required', 'required');
        document.getElementById('asignatura').setAttribute('required', 'required');

        let asignaturaValue = null;

        // Pre-cargar asignaturas antes de que super llene el formulario
        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.idSedeCarrera) {
                    const asignaturaSelect = document.getElementById('asignatura');
                    asignaturaValue = data.asignatura;
                    await this.loadAsignaturas(data.idSedeCarrera, asignaturaSelect, data.asignatura);
                }
            }
        } catch (e) {
            console.error("Error loading dependencies", e);
        }

        // Llamamos al padre para que haga el fetch y llene los datos
        await super.editarRegistro(id);

        // Re-asignar valor por si acaso super.editarRegistro lo reseteó o falló
        if (asignaturaValue) {
            const asignaturaSelect = document.getElementById('asignatura');
            if (asignaturaSelect) asignaturaSelect.value = asignaturaValue;
        }
    }
}

new CupoDemandaManager();
