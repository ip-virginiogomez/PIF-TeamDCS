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
                'idCiudad',
                'idTipoCentroSalud'
            ]
        });
    }

    mostrarModal() {
        const modal = document.getElementById(this.config.modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }
    }

    cerrarModal() {
        const modal = document.getElementById(this.config.modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        }
        this.limpiarFormulario();
    }

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

    const tablaContainer = document.getElementById('tabla-container');
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
            const html = await response.text();
            tablaContainer.innerHTML = html;
        } catch (error) {
            console.error('Error al cargar la tabla:', error);
            alert('Ocurrio un error al intentar ordenar la tabla.');
        }

    }

    tablaContainer.addEventListener('click', function (event) {
        
        const target = event.target.closest('.sort-link, .pagination a');

        if (!target) {
            return;
        }
        event.preventDefault(); 
        const url = target.href;
        window.history.pushState({ path: url }, '', url);

        cargarTabla(url);
    });

    window.addEventListener('popstate', function () {
        cargarTabla(location.href);
    });
});

// Funciones específicas para Centro de Salud (compatibilidad)
window.limpiarFormulario = () => window.centroSaludManager?.limpiarFormulario();
window.editarCentro = (id) => window.centroSaludManager?.editarRegistro(id);
window.eliminarCentro = (id) => window.centroSaludManager?.eliminarRegistro(id);
window.cerrarModal = () => window.centroSaludManager?.cerrarModal();