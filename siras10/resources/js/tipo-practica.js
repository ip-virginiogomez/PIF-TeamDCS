import BaseModalManager from './base-modal-manager.js';
/**
 * TipoPractica Manager
 * Extiende BaseModalManager
 */
class TipoPracticaManager extends BaseModalManager {
    
    constructor() {
        super({
            modalId: 'tipoPracticaModal',
            formId: 'tipoPracticaForm',
            entityName: 'Tipo de Práctica',
            entityGender: 'm',
            baseUrl: '/tipos-practica',
            primaryKey: 'idTipoPractica',
            tableContainerId: 'tabla-container',
            fields: [
                'nombrePractica'
            ]
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tipoPracticaForm')) {
        new TipoPracticaManager();
    }
});
