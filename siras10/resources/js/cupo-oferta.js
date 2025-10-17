class CupoOfertaManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'cupoOfertaModal',
            formId: 'cupoOfertaForm',
            entityName: 'Oferta de Cupo',
            baseUrl: '/cupo-ofertas',
            primaryKey: 'idCupoOferta',
            fields: [
                'idPeriodo', 'idUnidadClinica', 'idTipoPractica', 'idCarrera',
                'cantCupos', 'fechaEntrada', 'fechaSalida', 'horaEntrada', 'horaSalida'
            ]
        });
    }

    async editarRegistro(id) {
        this.editando = true;
        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            const data = await response.json();

            this.setFormValues({
                idCupoOferta: data.idCupoOferta,
                method: 'PUT',
                ...data
            });
            
            this.setModalTitle('Editar Oferta de Cupo');
            this.setButtonText('Actualizar Oferta');
            this.mostrarModal();
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'No se pudo cargar los datos de la oferta.', 'error');
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('cupoOfertaModal')) {
        window.cupoOfertaManager = new CupoOfertaManager();
        window.cupoOfertaManager.createGlobalFunctions();
    }
});

// --- FUNCIONES GLOBALES PARA LOS BOTONES ---
window.limpiarFormularioCupoOferta = () => window.cupoOfertaManager?.limpiarFormulario();
window.editarCupoOferta = (id) => window.cupoOfertaManager?.editarRegistro(id);
window.eliminarCupoOferta = (id) => window.cupoOfertaManager?.eliminarRegistro(id);
window.cerrarModalCupoOferta = () => window.cupoOfertaManager?.cerrarModal();