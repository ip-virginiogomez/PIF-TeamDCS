/**
 * Base Modal Manager
 * Clase base reutilizable para gestionar modales CRUD en todos los mantenedores
 */

class BaseModalManager {
    constructor(config) {
        this.config = {
            modalId: 'crudModal',
            formId: 'crudForm',
            entityName: 'registro',
            baseUrl: '',
            primaryKey: 'id',
            fields: [],
            ...config
        };

        this.modal = document.getElementById(this.config.modalId);
        this.form = document.getElementById(this.config.formId);
        this.editando = false;

        this.initEventListeners();
    }

    /**
     * Inicializa los event listeners
     */
    initEventListeners() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.cerrarModal();
                }
            });
        }
    }

    /**
     * Muestra el modal
     */
    mostrarModal() {
        if (this.modal) {
            this.modal.classList.remove('hidden');
        }
    }

    /**
     * Cierra el modal
     */
    cerrarModal() {
        if (this.modal) {
            this.modal.classList.add('hidden');
        }
    }

    /**
     * Limpia el formulario y prepara para nuevo registro
     */
    limpiarFormulario() {
        this.editando = false;

        if (this.form) {
            this.form.reset();
        }

        // Resetear campos específicos
        const resetValues = {
            [this.config.primaryKey]: '',
            method: 'POST'
        };

        this.setFormValues(resetValues);
        this.setModalTitle(`Nuevo ${this.config.entityName}`);
        this.setButtonText(`Guardar ${this.config.entityName}`);
        this.clearValidationErrors();
        this.mostrarModal();
    }

    /**
     * Establece valores en el formulario
     */
    setFormValues(values) {
        Object.keys(values).forEach(key => {
            const element = document.getElementById(key);
            if (element) {
                element.value = values[key];
            }
        });
    }

    /**
     * Obtiene valores del formulario
     */
    getFormValues() {
        const formData = new FormData(this.form);
        const values = {};
        for (let [key, value] of formData.entries()) {
            values[key] = value;
        }
        return values;
    }

    /**
     * Establece el título del modal
     */
    setModalTitle(title) {
        const titleElement = document.getElementById('modalTitle');
        if (titleElement) {
            titleElement.textContent = title;
        }
    }

    /**
     * Establece el texto del botón
     */
    setButtonText(text) {
        const buttonElement = document.getElementById('btnTexto');
        if (buttonElement) {
            buttonElement.textContent = text;
        }
    }

    /**
     * Limpia errores de validación
     */
    clearValidationErrors() {
        document.querySelectorAll('[id^="error-"]').forEach(element => {
            element.classList.add('hidden');
            element.textContent = '';
        });

        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.classList.remove('border-red-500');
        });
    }

    /**
     * Carga datos para editar un registro
     */
    async editarRegistro(id) {
        this.editando = true;

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            const data = await response.json();

            // Preparar valores para el formulario
            const formValues = {
                [this.config.primaryKey]: data[this.config.primaryKey],
                method: 'PUT'
            };

            // Mapear campos específicos
            this.config.fields.forEach(field => {
                if (data[field]) {
                    formValues[field] = data[field];
                }
            });

            this.setFormValues(formValues);
            this.setModalTitle(`Editar ${this.config.entityName}`);
            this.setButtonText(`Actualizar ${this.config.entityName}`);
            this.mostrarModal();

        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', `No se pudo cargar los datos del ${this.config.entityName}`, 'error');
        }
    }

    /**
     * Elimina un registro con confirmación
     */
    async eliminarRegistro(id) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${this.config.baseUrl}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const row = document.getElementById(`${this.config.entityName.toLowerCase()}-${id}`);
                    if (row) {
                        row.remove();
                    }
                    this.showAlert('¡Eliminado!', data.message, 'success');
                } else {
                    this.showAlert('Error', data.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                this.showAlert('Error', `No se pudo eliminar el ${this.config.entityName}`, 'error');
            }
        }
    }

    /**
     * Maneja el envío del formulario
     */
    async handleFormSubmit(e) {
        e.preventDefault();

        const formData = new FormData(this.form);
        const entityId = document.getElementById(this.config.primaryKey).value;
        const method = document.getElementById('method').value;

        let url = this.config.baseUrl;
        if (method === 'PUT') {
            url += `/${entityId}`;
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                await this.showAlert('¡Éxito!', data.message, 'success');
                this.onSuccess(data);
            } else {
                if (data.errors) {
                    this.showValidationErrors(data.errors);
                } else {
                    this.showAlert('Error', data.message, 'error');
                }
            }

        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'Error al procesar la solicitud', 'error');
        }
    }

    /**
     * Callback que se ejecuta después de una operación exitosa
     * Se puede sobrescribir en las clases heredadas
     */
    onSuccess(data) {
        location.reload();
    }

    /**
     * Muestra errores de validación en el formulario
     */
    showValidationErrors(errors) {
        this.clearValidationErrors();

        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById(`error-${field}`);

            if (input && errorDiv) {
                input.classList.add('border-red-500');
                errorDiv.classList.remove('hidden');
                errorDiv.textContent = errors[field][0];
            }
        });
    }

    /**
     * Muestra alertas usando SweetAlert2
     */
    async showAlert(title, text, icon) {
        return await Swal.fire(title, text, icon);
    }

    /**
     * Métodos de utilidad para crear funciones globales
     */
    createGlobalFunctions(prefix = '') {
        const functionPrefix = prefix ? `${prefix}_` : '';

        window[`${functionPrefix}limpiarFormulario`] = () => this.limpiarFormulario();
        window[`${functionPrefix}editar`] = (id) => this.editarRegistro(id);
        window[`${functionPrefix}eliminar`] = (id) => this.eliminarRegistro(id);
        window[`${functionPrefix}cerrarModal`] = () => this.cerrarModal();
    }
}

// Exportar para uso global
window.BaseModalManager = BaseModalManager;