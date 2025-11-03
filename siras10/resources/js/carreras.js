import BaseModalManager from './base-modal-manager.js';

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
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('carreraForm')) {
        new CarreraManager();
    }
});