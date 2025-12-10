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

        this.initFiltros();
        this.initRunInputRestriction();
    }

    initRunInputRestriction() {
        const runInput = this.form.querySelector('[name="runUsuario"]');
        if (runInput) {
            runInput.addEventListener('input', (e) => {
                // Solo permitir números, K, k y guión
                const valor = e.target.value;
                const valorLimpio = valor.replace(/[^0-9kK\-]/g, '');
                if (valor !== valorLimpio) {
                    e.target.value = valorLimpio;
                }
            });

            runInput.addEventListener('keypress', (e) => {
                // Prevenir caracteres no permitidos antes de que se ingresen
                const char = String.fromCharCode(e.which || e.keyCode);
                if (!/[0-9kK\-]/.test(char)) {
                    e.preventDefault();
                }
            });
        }
    }

    initFiltros() {
        const inputSearch = document.getElementById('search-input');
        const btnClear = document.getElementById('btn-clear-search');
        const container = document.getElementById(this.config.tableContainerId);

        let debounceTimer;
        let currentSort = 'runUsuario'; // Default sort
        let currentDirection = 'desc'; // Default direction

        // Leer params iniciales de la URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('sort_by')) currentSort = urlParams.get('sort_by');
        if (urlParams.has('sort_direction')) currentDirection = urlParams.get('sort_direction');
        if (urlParams.has('search') && inputSearch) {
            inputSearch.value = urlParams.get('search');
            if (btnClear) btnClear.classList.remove('hidden');
        }

        const fetchResultados = async (url = null) => {
            let targetUrl;
            if (url) {
                targetUrl = new URL(url);
                // Mantener sort si es paginación
                if (currentSort && !targetUrl.searchParams.has('sort_by')) {
                    targetUrl.searchParams.set('sort_by', currentSort);
                    targetUrl.searchParams.set('sort_direction', currentDirection);
                }
                // Mantener search
                if (inputSearch && inputSearch.value && !targetUrl.searchParams.has('search')) {
                    targetUrl.searchParams.set('search', inputSearch.value);
                }
            } else {
                targetUrl = new URL(window.location.origin + this.config.baseUrl);
                const params = new URLSearchParams();
                if (inputSearch && inputSearch.value) {
                    params.set('search', inputSearch.value);
                }
                if (currentSort) {
                    params.set('sort_by', currentSort);
                    params.set('sort_direction', currentDirection);
                }
                targetUrl.search = params.toString();
            }

            if (container) container.classList.add('opacity-50', 'pointer-events-none');

            try {
                const response = await fetch(targetUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Error en la red');
                const html = await response.text();
                if (container) container.innerHTML = html;

                window.history.pushState({}, '', targetUrl);

                // Toggle clear button
                if (btnClear && inputSearch) {
                    if (inputSearch.value) btnClear.classList.remove('hidden');
                    else btnClear.classList.add('hidden');
                }

            } catch (error) {
                console.error('Error filtrando:', error);
            } finally {
                if (container) container.classList.remove('opacity-50', 'pointer-events-none');
            }
        };

        // Exponer globalmente para el onclick del HTML
        window.toggleSort = (column) => {
            if (currentSort === column) {
                currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = column;
                currentDirection = 'asc';
            }
            fetchResultados();
        };

        if (inputSearch) {
            inputSearch.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchResultados(), 300);
            });
        }

        if (btnClear) {
            btnClear.addEventListener('click', () => {
                if (inputSearch) inputSearch.value = '';
                // Reset sort to default? Or keep? Usually keep.
                // currentSort = 'runUsuario';
                // currentDirection = 'desc';
                fetchResultados();
            });
        }

        // Paginación AJAX
        if (container) {
            container.addEventListener('click', (e) => {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    fetchResultados(link.href);
                }
            });
        }
    }

    showCreateModal() {
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
            contraseniaInput.placeholder = '';
        }

        // Mostrar asteriscos rojos de contraseña en modo creación
        const passwordRequired = document.getElementById('password-required');
        if (passwordRequired) {
            passwordRequired.classList.remove('hidden');
        }
        const passwordConfirmRequired = document.getElementById('password-confirm-required');
        if (passwordConfirmRequired) {
            passwordConfirmRequired.classList.remove('hidden');
        }

        // Restaurar texto de ayuda
        const passwordHelp = document.getElementById('password-help');
        if (passwordHelp) {
            passwordHelp.textContent = 'Mínimo 8 caracteres';
        }

        // Mostrar el modal
        this.mostrarModal();
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

            // Ocultar asteriscos rojos de contraseña en modo edición
            const passwordRequired = document.getElementById('password-required');
            if (passwordRequired) {
                passwordRequired.classList.add('hidden');
            }
            const passwordConfirmRequired = document.getElementById('password-confirm-required');
            if (passwordConfirmRequired) {
                passwordConfirmRequired.classList.add('hidden');
            }

            // Cambiar texto de ayuda
            const passwordHelp = document.getElementById('password-help');
            if (passwordHelp) {
                passwordHelp.textContent = 'Dejar en blanco para mantener la actual (mínimo 8 caracteres si se cambia)';
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
                    'runUsuario': ['El RUN ingresado no es válido.']
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
            if (!this.editando && contrasenia.trim() === '') {
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
