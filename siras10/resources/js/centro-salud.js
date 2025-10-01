/**
 * Centro de Salud Manager
 * Extiende BaseModalManager para funcionalidad específica de centros de salud
 */

class CentroSaludManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'centroSaludModal',
            formId: 'centroSaludForm',
            entityName: 'Centro de Salud',
            baseUrl: '/centro-salud',
            primaryKey: 'centroId',
            fields: [
                'nombreCentro',
                'direccion',
                'numContacto',
                'idCiudad',
                'idTipoCentroSalud'
            ]
        });
    }

    /**
     * Sobrescribe el método de edición para mapear correctamente los datos
     */
    async editarRegistro(id) {
        this.editando = true;

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            const data = await response.json();

            this.setFormValues({
                centroId: data.idCentroSalud,
                method: 'PUT',
                nombreCentro: data.nombreCentro,
                direccion: data.direccion,
                numContacto: data.numContacto,
                idCiudad: data.idCiudad,
                idTipoCentroSalud: data.idTipoCentroSalud
            });

            this.setModalTitle('Editar Centro de Salud');
            this.setButtonText('Actualizar Centro');
            this.mostrarModal();

        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'No se pudo cargar los datos del centro', 'error');
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
                    const row = document.getElementById(`centro-${id}`);
                    if (row) {
                        row.remove();
                    }
                    this.showAlert('¡Eliminado!', data.message, 'success');
                } else {
                    this.showAlert('Error', data.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                this.showAlert('Error', 'No se pudo eliminar el centro', 'error');
            }
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Crear instancia global del manager
    window.centroSaludManager = new CentroSaludManager();

    // Crear funciones globales para compatibilidad
    window.centroSaludManager.createGlobalFunctions();
});

// Funciones específicas para Centro de Salud (compatibilidad)
window.limpiarFormulario = () => window.centroSaludManager?.limpiarFormulario();
window.editarCentro = (id) => window.centroSaludManager?.editarRegistro(id);
window.eliminarCentro = (id) => window.centroSaludManager?.eliminarRegistro(id);
window.cerrarModal = () => window.centroSaludManager?.cerrarModal();