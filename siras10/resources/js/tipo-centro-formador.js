import BaseModalManager from './base-modal-manager.js';

class TipoCentroFormadorManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'tipoCentroModal',
            formId: 'tipoCentroForm',
            entityName: 'Tipo de Centro Formador',
            entityGender: 'm',
            baseUrl: '/tipos-centro-formador',
            primaryKey: 'idTipoCentroFormador',
            tableContainerId: 'tabla-container',
            fields: [
                'nombreTipo',
                'fechaCreacion'
            ]
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tipoCentroForm')) {
        new TipoCentroFormadorManager();
    }
});