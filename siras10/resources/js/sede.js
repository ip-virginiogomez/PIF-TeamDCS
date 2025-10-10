class SedeManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'sedeModal',
            formId: 'sedeForm',
            entityName: 'Sede',
            baseUrl: '/sede',
            primaryKey: 'sedeId',
            fields: [
                'nombreSede',
                'direccion',
                'idCentroFormador',
                'fechaCreacion',
                'numContacto'
            ]
        });
        this.tablaContainer = document.getElementById('tabla-container');
        this.fechaCreacionContainer = document.getElementById('fechaCreacion-container');
    }

    async editarRegistro(id) {
        this.editando = true;

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            const data = await response.json();

            this.setFormValues({
                sedeId: data.idSede,
                method: 'PUT',
                nombreSede: data.nombreSede,
                direccion: data.direccion,
                idCentroFormador: data.idCentroFormador,
                numContacto: data.numContacto
            });

            if (this.fechaCreacionContainer) {
                this.fechaCreacionContainer.style.display = 'block';
                const fechaInput = document.getElementById('fechaCreacion');
                if (fechaInput) fechaInput.readOnly = true;
            }

            this.setModalTitle('Editar Sede');
            this.setButtonText('Actualizar Sede');
            this.mostrarModal();

        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'No se pudo cargar los datos de la sede', 'error');
        }
    }

    /**
     * Sobrescribe el método de eliminación para usar el ID correcto
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
                    const row = document.getElementById(`sede-${id}`);
                    if (row) {
                        row.remove();
                    }
                    this.showAlert('¡Eliminado!', data.message, 'success');
                } else {
                    this.showAlert('Error', data.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                this.showAlert('Error', 'No se pudo eliminar la sede', 'error');
            }
        }
    }

    async submitForm(){
        const form = document.getElementById(this.config.formId);
        const formData = new FormData(form);
        const id = formData.get(this.config.primaryKey);
        const isUpdating = id && id !== 'undefined' && id !== '';

        const url = isUpdating ? `${this.config.baseUrl}/${id}` : this.config.baseUrl;
        const method = 'POST';

        if (!isUpdating) {
            const today = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
            formData.set('fechaCreacion', today);
        }

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (response.status === 422) { // Error de validación
                this.handleValidationErrors(data.errors);
                return;
            }

            if (!response.ok) {
                throw new Error(data.message || 'Ocurrió un error en el servidor.');
            }

            if (data.success) {
                this.showAlert('¡Éxito!', data.message, 'success');
                this.cerrarModal();
                this.cargarTabla(); // Recarga la tabla para mostrar los cambios
            } else {
                this.showAlert('Error', data.message, 'error');
            }

        } catch (error) {
            console.error('Error en submitForm:', error);
            this.showAlert('Error', 'No se pudo procesar la solicitud.', 'error');
        }
    }

    handleValidationErrors(errors) {
        this.config.fields.forEach(field => {
            const errorElement = document.getElementById(`error-${field}`);
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            }
        });

        for (const field in errors) {
            const errorElement = document.getElementById(`error-${field}`);
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
            }
        }
    }
    
    async cargarTabla(url = window.location.href) {
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            this.tablaContainer.innerHTML = await response.text();
        } catch (error) {
            console.error('Error al cargar la tabla:', error);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    const sedeManager = new SedeManager();
    window.cerrarModal = () => sedeManager.cerrarModal();

    const tablaContainer = document.getElementById('tabla-container');
    const nuevaSedeButton = document.getElementById('btn-nueva-sede');
    const form = document.getElementById(sedeManager.config.formId);

    if (nuevaSedeButton) {
        nuevaSedeButton.addEventListener('click', () => {
            sedeManager.limpiarFormulario();

            if (sedeManager.fechaCreacionContainer) {
                sedeManager.fechaCreacionContainer.style.display = 'none';
                const fechaInput = document.getElementById('fechaCreacion');
                if (fechaInput) fechaInput.readOnly = false;
            }
            
            sedeManager.setModalTitle('Nueva Sede');
            sedeManager.setButtonText('Crear Sede');
            sedeManager.mostrarModal();
        });
    }

    if (form) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            sedeManager.submitForm();
        });
    }

    if (!tablaContainer) {
        return;
    }

    const cargarTabla = async (url) => {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            tablaContainer.innerHTML = await response.text();
        } catch (error) {
            console.error('Error al cargar la tabla:', error);
        }

    }

    tablaContainer.addEventListener('click', function (event) {
        const link = event.target.closest('.sort-link, .pagination a');
        if (link) {
            event.preventDefault();
            const url = link.href;
            window.history.pushState({ path: url }, '', url);
            sedeManager.cargarTabla(url); // Se llama al método de la clase
            return;
        }

        const editButton = event.target.closest('.btn-edit');
        if (editButton) {
            event.preventDefault();
            const id = editButton.dataset.id;
            sedeManager.editarRegistro(id);
            return;
        }

        const deleteButton = event.target.closest('.btn-delete');
        if (deleteButton) {
            event.preventDefault();
            const id = deleteButton.dataset.id;
            sedeManager.eliminarRegistro(id);
        }
    });

    window.addEventListener('popstate', () => sedeManager.cargarTabla(location.href));
});