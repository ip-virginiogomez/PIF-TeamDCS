// Importamos la función de validación que necesitamos.
import { validarTelefono } from './validators.js'; 
import BaseModalManager from './base-modal-manager.js';

/**
 * Sede Manager
 * Extiende BaseModalManager
 */
class SedeManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'sedeModal',
            formId: 'sedeForm',
            entityName: 'Sede',
            entityGender: 'f',
            baseUrl: '/sede',
            primaryKey: 'idSede',
            tableContainerId: 'tabla-container',
            fields: [
                'nombreSede',
                'direccion',
                'idCentroFormador',
                'fechaCreacion',
                'numContacto'
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
        
        const numContactoInput = this.form.querySelector('[name="numContacto"]');
        if (numContactoInput && !validarTelefono(numContactoInput.value)) {
            esValido = false;
            this.showValidationErrors({
                'numContacto': ['El formato debe ser + seguido de hasta 11 dígitos.']
            });
        }
        return esValido;
    }
}



document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('sedeForm')) {
        new SedeManager();
    }
});