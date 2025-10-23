import BaseModalManager from './base-modal-manager.js';

/**
 * Carrera Manager
 * Extiende BaseModalManager
 */
class CarreraManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'carreraModal',
            formId: 'carreraForm',
            entityName: 'Carrera',
            entityGender: 'f',
            baseUrl: '/carreras',
            primaryKey: 'idCarrera',
            fields: [
                'nombreCarrera'
            ]
        });
    }
    
    // ¡Todo el código repetido de 'showValidationErrors', 'clearValidationErrors' y 'validate' se elimina!
    // Usará los métodos del padre.
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('carreraForm')) {
        new CarreraManager();
    }
});