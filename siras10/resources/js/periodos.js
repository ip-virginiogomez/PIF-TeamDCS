class PeriodoManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'periodoModal',
            formId: 'periodoForm',
            entityName: 'Período',
            baseUrl: '/periodos',
            primaryKey: 'idPeriodo',
            fields: [
                'Año',
                'fechaInicio',
                'fechaFin'
            ]
        });
    }

    async editarRegistro(id) {
        this.editando = true;
        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            const data = await response.json();

            this.setFormValues({
                idPeriodo: data.idPeriodo,
                method: 'PUT',
                Año: data.Año,
                fechaInicio: data.fechaInicio,
                fechaFin: data.fechaFin
            });
            
            this.setModalTitle('Editar Período');
            this.setButtonText('Actualizar Período');
            this.mostrarModal();
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'No se pudo cargar los datos del período.', 'error');
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('periodoModal')) {
        window.periodoManager = new PeriodoManager();
        // Creamos las funciones globales que usarán los botones
        window.periodoManager.createGlobalFunctions(); 
    }
});

// --- FUNCIONES GLOBALES PARA LOS BOTONES ---
// Usamos nombres específicos para evitar conflictos con otros CRUDs
window.limpiarFormularioPeriodo = () => window.periodoManager?.limpiarFormulario();
window.editarPeriodo = (id) => window.periodoManager?.editarRegistro(id);
window.eliminarPeriodo = (id) => window.periodoManager?.eliminarRegistro(id);
window.cerrarModalPeriodo = () => window.periodoManager?.cerrarModal();
