/**
 * Carrera Manager
 * Extiende BaseModalManager para funcionalidad específica de carreras
 */

class CarreraManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'carreraModal',
            formId: 'carreraForm',
            entityName: 'Carrera',
            baseUrl: '/carreras',
            primaryKey: 'idCarrera',
            fields: [
                'nombreCarrera'
            ]
        });
    }

    /**
     * Sobrescribe el método de edición para mapear correctamente los datos
     */
    async editarRegistro(id) {
        this.editando = true;

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            this.setFormValues({
                idCarrera: data.idCarrera,
                method: 'PUT',
                nombreCarrera: data.nombreCarrera
            });

            this.setModalTitle('Editar Carrera');
            this.setButtonText('Actualizar Carrera');
            this.mostrarModal();

        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'No se pudo cargar los datos de la carrera', 'error');
        }
    }

    /**
     * Sobrescribe el método de eliminación para usar el ID correcto
     */
    async eliminarRegistro(id) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará permanentemente la carrera',
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
                    const row = document.getElementById(`carrera-${id}`);
                    if (row) {
                        row.remove();
                    }
                    this.showAlert('¡Eliminado!', data.message, 'success');
                } else {
                    this.showAlert('Error', data.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                this.showAlert('Error', 'No se pudo eliminar la carrera', 'error');
            }
        }
    }

    /**
     * Callback que se ejecuta después de una operación exitosa
     */
    onSuccess(data) {
        this.cerrarModal();
        location.reload(); // Recargar para mostrar los cambios
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Crear instancia global del manager
    window.carreraManager = new CarreraManager();

    // Crear funciones globales para compatibilidad
    window.carreraManager.createGlobalFunctions();
});

// Funciones específicas para Carreras (compatibilidad)
window.limpiarFormularioCarrera = () => window.carreraManager?.limpiarFormulario();
window.editarCarrera = (id) => window.carreraManager?.editarRegistro(id);
window.eliminarCarrera = (id) => window.carreraManager?.eliminarRegistro(id);
window.cerrarModalCarrera = () => window.carreraManager?.cerrarModal();