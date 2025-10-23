import BaseModalManager from './base-modal-manager.js';

/**
 * Centro de Salud Manager
 * Extiende BaseModalManager
 */
class CentroSaludManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'centroSaludModal',
            formId: 'centroSaludForm',
            entityName: 'Centro de Salud',
            entityGender: 'm',
            baseUrl: '/centro-salud',
            primaryKey: 'centroId',
            fields: [
                'nombreCentro',
                'direccion',
                'director',
                'correoDirector',
                'idCiudad',
                'idTipoCentroSalud'
            ]
        });
    }
    
    // ¡Todo el código repetido de 'showValidationErrors', 'clearValidationErrors' y 'validate' se elimina!
    // Usará los métodos del padre.
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('centroSaludForm')) {
        new CentroSaludManager();
    }
});