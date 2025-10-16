import BaseModalManager from './base-modal-manager.js';
/**
 * Carrera Manager
 * Extiende BaseModalManager para funcionalidad específica de carreras
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

    showValidationErrors(errors) {
        this.clearValidationErrors();
        for (const field in errors) {
            const input = this.form.querySelector(`[name="${field}"]`);
            const errorDiv = document.getElementById(`error-${field}`);
            const errorMessage = errors[field][0];

            if (input) {
                input.classList.add('border-red-500'); 
            }

            if (errorDiv) {
                errorDiv.textContent = errorMessage;
                errorDiv.classList.remove('hidden');
            }
        }
    }

    clearValidationErrors() {
        this.form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });
        
        this.form.querySelectorAll('[id^="error-"]').forEach(errorDiv => {
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
        });
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;
        return esValido;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('carreraForm')) {
        new CarreraManager();
    }
});