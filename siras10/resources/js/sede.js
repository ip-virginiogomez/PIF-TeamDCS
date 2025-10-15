/**
 * Sede Manager
 * Extiende BaseModalManager para funcionalidad específica de sedes
 */
import { validarTelefono } from "./validators"; 

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
        this.initValidations();
    }

    initValidations() {
        const form = document.getElementById(this.config.formId);
        if (!form) return;

        form.addEventListener('submit', (event) => {
            this.limpiarErrores();
            
            let esValido = true;
            
            const numContactoInput = form.querySelector('[name="numContacto"]');
            if (numContactoInput && !validarTelefonoInternacional(numContactoInput.value)) {
                esValido = false;
                this.mostrarErrorCampo(numContactoInput, 'El formato debe ser + seguido de hasta 11 dígitos.');
            }

            if (!esValido) {
                event.preventDefault();            }
        });
    }
    limpiarErrores() {
        const form = document.getElementById(this.config.formId);
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }

    async editarRegistro(id) {
        this.editando = true;

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            this.setFormValues({
                sedeId: data.idSede,
                method: 'PUT',
                nombreSede: data.nombreSede,
                direccion: data.direccion,
                idCentroFormador: data.idCentroFormador,
                fechaCreacion: data.fechaCreacion,
                numContacto: data.numContacto
            });

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
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
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
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    window.sedeManager = new SedeManager();
    window.sedeManager.createGlobalFunctions();
});

// Funciones específicas para Sede (compatibilidad)
window.limpiarFormulario = () => window.sedeManager?.limpiarFormulario();
window.editarSede = (id) => window.sedeManager?.editarRegistro(id);
window.eliminarSede = (id) => window.sedeManager?.eliminarRegistro(id);
window.cerrarModal = () => window.sedeManager?.cerrarModal();