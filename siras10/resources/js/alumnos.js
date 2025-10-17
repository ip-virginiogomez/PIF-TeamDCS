import BaseModalManager from './base-modal-manager.js';
import { validarCorreo } from './validators.js';
import { validarRun } from './validators.js';

/**
 * Alumno Manager
 * Extiende BaseModalManager para funcionalidad específica de alumnos
 */

class AlumnoManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'alumnoModal',
            formId: 'alumnoForm',
            entityName: 'Alumno',
            entityGender: 'm',
            baseUrl: '/alumnos',
            primaryKey: 'runAlumno',
            fields: [
                'runAlumno',
                'nombres',
                'apellidoPaterno',
                'apellidoMaterno',
                'fechaNacto',
                'correo',
                'acuerdo'
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

        const runInput = this.form.querySelector('[name="runAlumno"]');
        const correoInput = this.form.querySelector('[name="correo"]');
        
        if (runInput && !validarRun(runInput.value)) {
            esValido = false;
            runInput.classList.add('border-red-500');
            const errorDiv = document.getElementById('error-runAlumno');
            if (errorDiv) {
                errorDiv.textContent = 'El RUN no cumple con el formato requerido.';
                errorDiv.classList.remove('hidden');
            }
        }
        if (correoInput && !validarCorreo(correoInput.value)) {
            esValido = false;
            correoInput.classList.add('border-red-500');
            const errorDiv = document.getElementById('error-correo');
            if (errorDiv) {
                errorDiv.textContent = 'Correo electrónico inválido.';
                errorDiv.classList.remove('hidden');
            }
        }
        return esValido;
    }

    limpiarFormulario() {
        super.limpiarFormulario();
        const runInput = this.form.querySelector('#runAlumno');
        const runHelpText = document.getElementById('run-help-text');

        if (runInput) {
            runInput.removeAttribute('readonly');
            runInput.closest('.mb-4').style.display = 'block';
        }
        if (runHelpText) {
            runHelpText.classList.add('hidden');
        }
    }

    async editarRegistro(id) {
        await super.editarRegistro(id);

        const runInput = this.form.querySelector('#runAlumno');
        const runHelpText = document.getElementById('run-help-text');
        
        if (runInput) {
            runInput.setAttribute('readonly', true);
        }
        if (runHelpText) {
            runHelpText.classList.remove('hidden');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('alumnoForm')) {
        new AlumnoManager();
    }
});