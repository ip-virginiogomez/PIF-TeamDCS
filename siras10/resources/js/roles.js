import BaseModalManager from './base-modal-manager.js';

/**
 * Rol Manager
 * Extiende BaseModalManager
 */
class RolManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'rolModal',
            formId: 'rolForm',
            entityName: 'Rol',
            entityGender: 'm',
            baseUrl: '/roles',
            primaryKey: 'id',
            tableContainerId: 'tabla-container',
            fields: ['name']
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
        
        const nameInput = this.form.querySelector('[name="name"]');
        if (nameInput && nameInput.value.trim() === '') {
            esValido = false;
            this.showValidationErrors({
                'name': ['El nombre del rol es obligatorio.']
            });
        }

        return esValido;
    }

    showCreateModal() {
        this.limpiarFormulario();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('rolForm')) {
        window.rolManager = new RolManager();
    }
});
