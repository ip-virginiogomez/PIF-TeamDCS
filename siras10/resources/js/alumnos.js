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
        
        if (!data) return; 

        const alumno = data.alumno;
        this.form.querySelector('[name="runAlumno"]').value = alumno.runAlumno;
        this.form.querySelector('[name="nombres"]').value = alumno.nombres;
        this.form.querySelector('[name="apellidoPaterno"]').value = alumno.apellidoPaterno;
        this.form.querySelector('[name="apellidoMaterno"]').value = alumno.apellidoMaterno;
        this.form.querySelector('[name="correo"]').value = alumno.correo;
        this.form.querySelector('[name="fechaNacto"]').value = alumno.fechaNacto;
        
        const runInput = this.form.querySelector('#runAlumno');
        const runHelpText = document.getElementById('run-help-text');
        if (runInput) runInput.setAttribute('readonly', true);
        if (runHelpText) runHelpText.classList.remove('hidden');

        const fotoPreview = document.getElementById('foto-preview');
        if (fotoPreview) {
            if (data && data.alumno && data.alumno.foto) {
                fotoPreview.src = `/storage/${data.alumno.foto}`;
            } else {
                fotoPreview.src = "/storage/placeholder.png";
            }
        }
        
        const selectSedeCarrera = this.form.querySelector('[name="idSedeCarrera"]');
        if (selectSedeCarrera && data.sedesCarrerasDisponibles) {
            selectSedeCarrera.innerHTML = '<option value="">Seleccione una opción...</option>';
            
            data.sedesCarrerasDisponibles.forEach(sede => {
                const option = document.createElement('option');
                option.value = sede.idSedeCarrera;
                option.textContent = sede.nombreSedeCarrera;
                
                if (sede.idSedeCarrera == data.sedeCarreraActual.idSedeCarrera) {
                    option.selected = true;
                }
                selectSedeCarrera.appendChild(option);
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('alumnoForm')) {
        new AlumnoManager();
    }
});