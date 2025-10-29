import BaseModalManager from './base-modal-manager.js';

/**
 * Unidad Clínica Manager
 * Extiende BaseModalManager
 */
class UnidadClinicaManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'unidadClinicaModal',
            formId: 'unidadClinicaForm',
            entityName: 'Unidad Clínica',
            entityGender: 'f',
            baseUrl: '/unidad-clinicas',
            primaryKey: 'idUnidadClinica',
            tableContainerId: 'tabla-container',
            fields: [
                'nombreUnidad',
                'idCentroSalud'
            ]
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('unidadClinicaForm')) { 
        new UnidadClinicaManager();
    }
});
