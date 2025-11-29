// Importamos la función de validación que necesitamos.
import { validarRun, validarTelefono } from './validators.js'; 
import BaseModalManager from './base-modal-manager.js';


/**
 * Usuario Manager
 * Extiende BaseModalManager
 */
class UsuarioManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'usuarioModal',
            formId: 'usuarioForm',
            entityName: 'Usuario',
            entityGender: 'm',
            baseUrl: '/usuarios',
            primaryKey: 'runUsuario',
            tableContainerId: 'tabla-container',
            fields: [
                'runUsuario',
                'nombreUsuario',
                'apellidoPaterno',
                'apellidoMaterno',
                'correo',
                'telefono',
                'contrasenia',
                'contrasenia_confirmation',
                'roles'
            ]
        });
    }

    showCreateModal() {
        super.showCreateModal();
        
        // En modo crear, el RUN es editable
        const runInput = this.form.querySelector('[name="runUsuario"]');
        if (runInput) {
            runInput.removeAttribute('readonly');
            runInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        }

        // En modo crear, la contraseña es obligatoria
        const contraseniaInput = this.form.querySelector('[name="contrasenia"]');
        if (contraseniaInput) {
            contraseniaInput.setAttribute('required', 'required');
        }
    }

    async editarRegistro(id) {
        this.editando = true;
        this.clearValidationErrors();

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) throw new Error('Error al cargar el usuario');

            const data = await response.json();
            
            // Poblar campos desde data.usuario
            const usuario = data.usuario;
            this.config.fields.forEach(field => {
                const element = this.form.querySelector(`[name="${field}"]`);
                if (element && usuario[field] !== undefined && field !== 'contrasenia' && field !== 'contrasenia_confirmation' && field !== 'roles') {
                    element.value = usuario[field] || '';
                }
            });
            
            // En modo editar, el RUN es readonly
            const runInput = this.form.querySelector('[name="runUsuario"]');
            if (runInput) {
                runInput.setAttribute('readonly', 'readonly');
                runInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            }

            // En modo editar, la contraseña es opcional
            const contraseniaInput = this.form.querySelector('[name="contrasenia"]');
            if (contraseniaInput) {
                contraseniaInput.removeAttribute('required');
                contraseniaInput.value = '';
                contraseniaInput.placeholder = 'Dejar en blanco para mantener la actual';
            }
            
            const contraseniaConfirmInput = this.form.querySelector('[name="contrasenia_confirmation"]');
            if (contraseniaConfirmInput) {
                contraseniaConfirmInput.value = '';
            }

            // Poblar roles (checkboxes)
            const rolesCheckboxes = this.form.querySelectorAll('input[name="roles[]"]');
            rolesCheckboxes.forEach(checkbox => {
                checkbox.checked = data.usuario_roles.includes(checkbox.value);
            });
            
            // Agregar hidden input con el ID
            let pkInput = this.form.querySelector(`[name="${this.config.primaryKey}"]`);
            if (!pkInput) {
                pkInput = document.createElement('input');
                pkInput.type = 'hidden';
                pkInput.name = this.config.primaryKey;
                this.form.appendChild(pkInput);
            }
            pkInput.value = id;

            this.setModalTitle(`Editar ${this.config.entityName}`);
            this.setButtonText(`Actualizar ${this.config.entityName}`);
            this.mostrarModal();

            return data;
        } catch (error) {
            console.error('Error al cargar el usuario:', error);
            alert('Error al cargar el usuario');
        }
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
        
        // Validar RUN (solo en modo crear, en editar es readonly)
        const runInput = this.form.querySelector('[name="runUsuario"]');
        if (runInput && !runInput.hasAttribute('readonly')) {
            if (!validarRun(runInput.value)) {
                esValido = false;
                this.showValidationErrors({
                    'runUsuario': ['El RUN debe tener el formato correcto (12345678-9).']
                });
            }
        }

        // Validar teléfono
        const telefonoInput = this.form.querySelector('[name="telefono"]');
        if (telefonoInput && telefonoInput.value.trim() !== '') {
            if (!validarTelefono(telefonoInput.value)) {
                esValido = false;
                this.showValidationErrors({
                    'telefono': ['El formato debe ser + seguido de hasta 11 dígitos.']
                });
            }
        }

        // Validar que al menos un rol esté seleccionado
        const rolesCheckboxes = this.form.querySelectorAll('input[name="roles[]"]:checked');
        if (rolesCheckboxes.length === 0) {
            esValido = false;
            this.showValidationErrors({
                'roles': ['Debe seleccionar al menos un rol.']
            });
        }

        // Validar contraseñas coincidan (si se ingresó alguna)
        const contraseniaInput = this.form.querySelector('[name="contrasenia"]');
        const contraseniaConfirmInput = this.form.querySelector('[name="contrasenia_confirmation"]');
        
        if (contraseniaInput && contraseniaConfirmInput) {
            const contrasenia = contraseniaInput.value;
            const contraseniaConfirm = contraseniaConfirmInput.value;
            
            // Si estamos creando, la contraseña es obligatoria
            if (!this.isEditing && contrasenia.trim() === '') {
                esValido = false;
                this.showValidationErrors({
                    'contrasenia': ['La contraseña es obligatoria al crear un usuario.']
                });
            }
            
            // Si se ingresó contraseña, validar longitud
            if (contrasenia.trim() !== '' && contrasenia.length < 8) {
                esValido = false;
                this.showValidationErrors({
                    'contrasenia': ['La contraseña debe tener al menos 8 caracteres.']
                });
            }

            // Validar que coincidan
            if (contrasenia !== contraseniaConfirm) {
                esValido = false;
                this.showValidationErrors({
                    'contrasenia_confirmation': ['Las contraseñas no coinciden.']
                });
            }
        }

        return esValido;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('usuarioForm')) {
        new UsuarioManager();
    }
});
