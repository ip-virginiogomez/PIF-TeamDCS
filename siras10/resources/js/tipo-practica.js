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

    // ¡Todo el código repetido de 'showValidationErrors', 'clearValidationErrors' y 'validate' se elimina!
    // Usará los métodos del padre.
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tipoPracticaForm')) {
        new TipoPracticaManager();
    }
});