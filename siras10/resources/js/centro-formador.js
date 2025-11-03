import BaseModalManager from './base-modal-manager.js';

class CentroFormadorManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'centroFormadorModal',
            formId: 'centroFormadorForm',
            entityName: 'Centro Formador',
            entityGender: 'm',
            baseUrl: '/centros-formadores',
            primaryKey: 'idCentroFormador',
            tableContainerId: 'tabla-container',
            fields: [
                'idTipoCentroFormador',
                'nombreCentroFormador',
                'fechaCreacion',
            ]
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('centroFormadorForm')) {
        new CentroFormadorManager();
    }
});


