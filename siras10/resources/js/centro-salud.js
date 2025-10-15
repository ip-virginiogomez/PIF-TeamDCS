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
                'director',
                'correoDirector',
                'idCiudad',
                'idTipoCentroSalud'
            ]
        });
    }

    /**
     * Sobrescribir limpiarFormulario
     */
    limpiarFormulario() {
        super.limpiarFormulario();

        this.editando = false;

        // Resetear títulos para nuevo registro
        this.setModalTitle('Nuevo Centro de Salud');
        this.setButtonText('Guardar Centro de Salud');
    }

    /**
     * Sobrescribir editarRegistro
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
                director: data.director || '',
                correoDirector: data.correoDirector || '',
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
                    await this.showAlert('¡Eliminado!', data.message, 'success');

                    // ✅ CAMBIAR: Actualizar tabla en lugar de recargar
                    await this.actualizarTabla();

                } else {
                    this.showAlert('Error', data.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                this.showAlert('Error', 'No se pudo eliminar el centro', 'error');
            }
        }
    }

    /**
     * Manejar envío del formulario - SIN RECARGAR PÁGINA
     */
    async handleFormSubmit(e) {
        e.preventDefault();

        const formData = new FormData(this.form);
        const centroId = document.getElementById(this.config.primaryKey).value;
        const method = document.getElementById('method').value;

        let url = this.config.baseUrl;
        if (method === 'PUT') {
            url += `/${centroId}`;
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

            // Verificar respuesta
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Verificar content-type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON válido');
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                await this.showAlert('¡Éxito!', data.message, 'success');
                this.cerrarModal();

                // ✅ CAMBIAR: En lugar de recargar, actualizar la tabla dinámicamente
                await this.actualizarTabla();

            } else {
                if (data.errors) {
                    this.showValidationErrors(data.errors);
                } else {
                    this.showAlert('Error', data.message || 'Error desconocido', 'error');
                }
            }

        } catch (error) {
            console.error('Error completo:', error);
            this.showAlert('Error', `Error al procesar la solicitud: ${error.message}`, 'error');
        }
    }

    /**
     * ✅ NUEVO MÉTODO: Actualizar tabla sin recargar página
     */
    async actualizarTabla() {
        try {
            const tablaContainer = document.getElementById('tabla-container');
            if (tablaContainer) {
                // Obtener la URL actual con sus parámetros de ordenamiento
                const currentUrl = new URL(window.location);

                const response = await fetch(currentUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const html = await response.text();
                    tablaContainer.innerHTML = html;
                    console.log('Tabla actualizada exitosamente');
                }
            }
        } catch (error) {
            console.error('Error al actualizar la tabla:', error);
            // Si falla la actualización dinámica, recargar como fallback
            location.reload();
        }
    }
}

// Inicialización simple
document.addEventListener('DOMContentLoaded', function () {
    console.log('Inicializando CentroSaludManager...');

    // Crear instancia global del manager
    window.centroSaludManager = new CentroSaludManager();

    // Crear funciones globales
    window.centroSaludManager.createGlobalFunctions();

    // FUNCIONALIDAD DE TABLA (mantener la existente)
    const tablaContainer = document.getElementById('tabla-container');
    if (tablaContainer) {
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
                alert('Ocurrió un error al intentar ordenar la tabla.');
            }
        };

        tablaContainer.addEventListener('click', function (event) {
            const target = event.target.closest('.sort-link, .pagination a');
            if (!target) return;

            event.preventDefault();
            const url = target.href;
            window.history.pushState({ path: url }, '', url);
            cargarTabla(url);
        });

        window.addEventListener('popstate', function () {
            cargarTabla(location.href);
        });
    }
});

// Funciones globales para compatibilidad
window.limpiarFormulario = () => window.centroSaludManager?.limpiarFormulario();
window.editarCentro = (id) => window.centroSaludManager?.editarRegistro(id);
window.eliminarCentro = (id) => window.centroSaludManager?.eliminarRegistro(id);
window.cerrarModal = () => window.centroSaludManager?.cerrarModal();