import BaseModalManager from './base-modal-manager.js';
import { validarCorreo } from './validators.js';
import { validarRun } from './validators.js';
/**
 * Docente Manager
 * Extiende BaseModalManager para funcionalidad específica de docentes
 */

class DocenteManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'docenteModal',
            formId: 'docenteForm',
            entityName: 'Docente',
            entityGender: 'm',
            baseUrl: '/docentes',
            primaryKey: 'runDocente',
            fields: [
                'runDocente',
                'nombresDocente',
                'apellidoPaterno',
                'apellidoMaterno',
                'fechaNacto',
                'correo',
                'profesion'
            ]
        });
        this.initFotoPreview();
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

    initFotoPreview(){
        const fotoInput = this.form.querySelector('#foto');
        const fotoPreview = document.getElementById('foto-preview');

        if (!fotoInput || !fotoPreview) return;

        fotoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            
            reader.onload = (event) => {
                fotoPreview.src = event.target.result; 
            };
            
            reader.readAsDataURL(file);
        });
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;

        const runInput = this.form.querySelector('[name="runDocente"]');
        const correoInput = this.form.querySelector('[name="correo"]');

        if (runInput && !validarRun(runInput.value)) {
            esValido = false;
            runInput.classList.add('border-red-500');
            const errorDiv = document.getElementById('error-runDocente');
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
        const runInput = this.form.querySelector('#runDocente');
        const runHelpText = document.getElementById('run-help-text');
        const fotoPreview = document.getElementById('foto-preview');

        if (runInput) {
            runInput.removeAttribute('readonly');
            runInput.closest('.mb-4').style.display = 'block';
        }
        if (runHelpText) {
            runHelpText.classList.add('hidden');
        }
        if (fotoPreview) {
            fotoPreview.src = "/storage/placeholder.png";
        }
    }

    async editarRegistro(id) {
        const data = await super.editarRegistro(id);

        const runInput = this.form.querySelector('#runDocente');
        const runHelpText = document.getElementById('run-help-text');
        const fotoPreview = document.getElementById('foto-preview');
        
        if (runInput) {
            runInput.setAttribute('readonly', true);
        }
        if (runHelpText) {
            runHelpText.classList.remove('hidden');
        }
        if (fotoPreview) {
            if (data && data.foto) {
                fotoPreview.src = `/storage/${data.foto}`;
            } else {
                fotoPreview.src = "/storage/placeholder.png";
            }
        }
    }

    async verDocumentos(id) {
        const modal = document.getElementById('documentosModal');
        const modalBody = document.getElementById('documentosModal-body');
        const modalTitle = document.getElementById('documentosModal-title');

        if (!modal || !modalBody) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex', 'items-center', 'justify-center');
        modalTitle.textContent = 'Documentos del Docente';
        modalBody.innerHTML = `<div class="flex justify-center items-center h-32"><i class="fas fa-spinner fa-spin fa-2x text-gray-500"></i></div>`;

        try {
            const response = await fetch(`/docentes/${id}/documentos`);
            if (!response.ok) throw new Error('No se pudo cargar la lista de documentos.');

            const html = await response.text();
            
            modalBody.innerHTML = html;
        } catch (error) {
            console.error('Error al ver documentos:', error);
            modalBody.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('docenteForm')) {
        new DocenteManager();
    }
});