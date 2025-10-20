class TipoPracticaManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'tipoPracticaModal',
            formId: 'tipoPracticaForm',
            entityName: 'Tipo de Práctica',
            baseUrl: '/tipos-practica',
            primaryKey: 'idTipoPractica',
            fields: [
                'nombrePractica'
            ]
        });
    }

    async editarRegistro(id) {
        this.editando = true;
        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            const data = await response.json();

            this.setFormValues({
                idTipoPractica: data.idTipoPractica,
                method: 'PUT',
                nombrePractica: data.nombrePractica
            });
            
            this.setModalTitle('Editar Tipo de Práctica');
            this.setButtonText('Actualizar Tipo');
            this.mostrarModal();
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', `No se pudo cargar los datos del ${this.config.entityName}.`, 'error');
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('tipoPracticaModal')) {
        window.tipoPracticaManager = new TipoPracticaManager();
        window.tipoPracticaManager.createGlobalFunctions();
    }
});

// Funciones globales específicas para este módulo
window.limpiarFormularioTipoPractica = () => window.tipoPracticaManager?.limpiarFormulario();
window.editarTipoPractica = (id) => window.tipoPracticaManager?.editarRegistro(id);
window.eliminarTipoPractica = (id) => window.tipoPracticaManager?.eliminarRegistro(id);
window.cerrarModalTipoPractica = () => window.tipoPracticaManager?.cerrarModal();