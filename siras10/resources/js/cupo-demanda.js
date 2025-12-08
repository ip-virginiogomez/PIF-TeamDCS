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
            fields: ['idPeriodo', 'idSedeCarrera', 'cuposSolicitados']
        });

        this.initFilter();
    }

    initFilter() {
        const periodoFilter = document.getElementById('periodo_filter');
        if (periodoFilter) {
            periodoFilter.addEventListener('change', function () {
                window.location.href = `?periodo_id=${this.value}`;
            });
        }
    }

    /**
     * Sobrescribimos para manejar el select de SedeCarrera
     */
    limpiarFormulario() {
        super.limpiarFormulario();
        const idSedeCarrera = document.getElementById('idSedeCarrera');
        if (idSedeCarrera) {
            idSedeCarrera.disabled = false;
        }
        // Asegurar que el idPeriodo se mantenga si está en el form
        const periodoFilter = document.getElementById('periodo_filter');
        const hiddenPeriodo = document.querySelector('input[name="idPeriodo"]');
        if (periodoFilter && hiddenPeriodo) {
            hiddenPeriodo.value = periodoFilter.value;
        }
    }

    /**
     * Sobrescribimos para deshabilitar SedeCarrera al editar
     */
    async editarRegistro(id) {
        // Llamamos al padre para que haga el fetch y llene los datos
        await super.editarRegistro(id);

        const idSedeCarrera = document.getElementById('idSedeCarrera');
        if (idSedeCarrera) {
            idSedeCarrera.disabled = true;
        }
    }
}

new CupoDemandaManager();
