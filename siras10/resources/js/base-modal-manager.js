/**
 * Base Modal Manager
 */
export default class BaseModalManager {
    constructor(config) {
        this.config = {
            modalId: 'crudModal',
            formId: 'crudForm',
            entityName: 'registro',
            baseUrl: '',
            primaryKey: 'id',
            fields: [],
            tableContainerId: 'tabla-container',
            ...config
        };

        this.modal = document.getElementById(this.config.modalId);
        this.form = document.getElementById(this.config.formId);
        this.editando = false;

        this.initEventListeners();
    }

    /**
     * Inicializa TODOS los event listeners necesarios: formulario y página.
     */
    initEventListeners() {

        document.body.addEventListener('click', (e) => {
            const closeModalButton = e.target.closest('[data-action="close-info-modal"]');
            if (closeModalButton) {
                const modalId = closeModalButton.dataset.modalId;
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex', 'items-center', 'justify-center');
                }
            }
        });

        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                const esClicEnFondo = e.target.id === this.config.modalId;
                const esClicEnBotonCerrar = e.target.closest('[data-action="close-modal"]');

                if (esClicEnFondo || esClicEnBotonCerrar) {
                    this.cerrarModal();
                }
            });
        }
        
        document.querySelector(`[data-modal-target="${this.config.modalId}"]`)?.addEventListener('click', () => {
            this.limpiarFormulario();
        });

        const tableContainer = document.getElementById(this.config.tableContainerId);
        if (tableContainer) {
            tableContainer.addEventListener('click', (e) => {
                const link = e.target.closest('.sort-link, .pagination a');
                if (link) {
                    e.preventDefault();
                    const url = link.getAttribute('href');
                    this.refreshTable(url);
                    return;
                }

                const editButton = e.target.closest('[data-action="edit"]');
                const deleteButton = e.target.closest('[data-action="delete"]');
                const docButton = e.target.closest('[data-action="view-documents"]');

                if (editButton) {
                    this.editarRegistro(editButton.dataset.id);
                }
                if (deleteButton) {
                    this.eliminarRegistro(deleteButton.dataset.id);
                }
                if (docButton) {
                    if (typeof this.verDocumentos === 'function') {
                        this.verDocumentos(docButton.dataset.id);
                    }
                }
            });
        }
    }
    
    // ========================================================================
    // MÉTODOS DE MANEJO DE FORMULARIO (AJAX) - Lógica central
    // ========================================================================

    async handleFormSubmit(e) {
        e.preventDefault();

        if (typeof this.validate === 'function' && !this.validate()) {
            return; 
        }

        const formData = new FormData(this.form);
        let url = this.config.baseUrl;
        
        if (this.editando) {
            const entityId = formData.get(this.config.primaryKey);
            url += `/${entityId}`;
            formData.append('_method', 'PUT'); 
        }

        try {
            const response = await fetch(url, {
                method: 'POST', 
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });
            const data = await response.json();

            if (!response.ok) {
                if (response.status === 422) { 
                    this.showValidationErrors(data.errors);
                } else {
                    throw new Error(data.message || 'Ocurrió un error en el servidor');
                }
            } else {
                this.onSuccess(data);
            }
        } catch (error) {
            console.error('Error AJAX:', error);
            this.showAlert('Error', error.message, 'error');
        }
    }

    onSuccess(data) {
        this.cerrarModal();
        this.showAlert('¡Éxito!', data.message, 'success');
        this.refreshTable();
    }

    async refreshTable(url = null) {
        try {
            const fetchUrl = url ? url : `${window.location.pathname}${window.location.search}`;

            const response = await fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const html = await response.text();
            document.getElementById(this.config.tableContainerId).innerHTML = html;

            if (url) {
                window.history.pushState({}, '', url);
            }

        } catch (error) {
            console.error('Error al refrescar la tabla:', error);
            this.showAlert('Error', 'No se pudo actualizar la tabla. Recargando...', 'warning');
            setTimeout(() => location.reload(), 2000);
        }
    }

    // ========================================================================
    // MÉTODOS DE UI Y FORMULARIO 
    // ========================================================================

    limpiarFormulario() {
        this.editando = false;
        if (this.form) this.form.reset();
        
        const pkInput = this.form.querySelector(`[name="${this.config.primaryKey}"]`);
        // if (pkInput) pkInput.remove();

        const articulo = (this.config.entityGender === 'f') ? 'Nueva' : 'Nuevo';

        this.setModalTitle(`${articulo} ${this.config.entityName}`);
        this.setButtonText(`Guardar ${this.config.entityName}`);
        this.clearValidationErrors();
        this.mostrarModal();
    }
    
    async editarRegistro(id) {
        this.editando = true;
        this.clearValidationErrors();

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            if (!response.ok) throw new Error('No se pudo obtener la información del registro.');
            const data = await response.json();

            this.config.fields.forEach(field => {
                const element = this.form.querySelector(`[name="${field}"]`);
                if (element && data[field] !== undefined) {
                    if (element.type === 'date' && data[field]) {
                        element.value = data[field].substring(0, 10);
                    } else {
                        element.value = data[field];
                    }
                }
            });

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
            console.error('Error al cargar datos para editar:', error);
            this.showAlert('Error', error.message, 'error');
            return undefined;
        }
    }
    
    async eliminarRegistro(id) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
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
                    method: 'POST',
                    body: JSON.stringify({ _method: 'DELETE' }),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (!response.ok) throw new Error(data.message);

                this.showAlert('¡Eliminado!', data.message, 'success');
                this.refreshTable();

            } catch (error) {
                console.error('Error al eliminar:', error);
                this.showAlert('Error', `No se pudo eliminar el ${this.config.entityName}.`, 'error');
            }
        }
    }

    // Métodos auxiliares de UI (sin cambios)
    mostrarModal() {
        if (!this.modal) return;
        this.modal.classList.remove('hidden');
        this.modal.classList.add('flex', 'items-center', 'justify-center');
    }
    cerrarModal() {
        if (!this.modal) return;
        this.modal.classList.add('hidden');
        this.modal.classList.remove('flex', 'items-center', 'justify-center');
    }
    setModalTitle(title) { document.getElementById('modalTitle').textContent = title; }
    setButtonText(text) { document.getElementById('btnTexto').textContent = text; }
    clearValidationErrors() { /* Tu código existente aquí es correcto */ }
    showValidationErrors(errors) { /* Tu código existente aquí es correcto */ }
    async showAlert(title, text, icon) { return await Swal.fire(title, text, icon); }
}

