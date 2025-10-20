
class UnidadClinicaManager extends BaseModalManager {
    constructor() {
        // 1. Configuramos el manager para que trabaje con "Unidad Clínica"
        super({
            modalId: 'unidadClinicaModal',
            formId: 'unidadClinicaForm',
            entityName: 'Unidad Clínica',
            baseUrl: '/unidad-clinicas',
            primaryKey: 'idUnidadClinica', // Usamos el ID correcto de la tabla
            fields: [
                'nombreUnidad',
                'idCentroSalud'
            ]
        });
    }

    // 2. Personalizamos el método de edición para llenar los campos correctos
    async editarRegistro(id) {
        this.editando = true;

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            const data = await response.json();

            this.setFormValues({
                idUnidadClinica: data.idUnidadClinica,
                method: 'PUT',
                nombreUnidad: data.nombreUnidad,
                idCentroSalud: data.idCentroSalud
            });
            
            this.setModalTitle('Editar Unidad Clínica');
            this.setButtonText('Actualizar Unidad');
            this.mostrarModal();

        } catch (error) {
            console.error('Error al cargar datos para editar:', error);
            this.showAlert('Error', `No se pudo cargar los datos de la ${this.config.entityName}.`, 'error');
        }
    }
}

// 3. Inicializamos el manager cuando la página esté lista
document.addEventListener('DOMContentLoaded', function () {
    // Nos aseguramos de que el modal de unidad clínica exista en esta página
    if (document.getElementById('unidadClinicaModal')) {
        // Crear instancia global del manager
        window.unidadClinicaManager = new UnidadClinicaManager();

        // Crear funciones globales para que los botones del HTML puedan llamarlas
        window.unidadClinicaManager.createGlobalFunctions(); 
    }
});

// 4. Creamos funciones globales con nombres únicos para este módulo
// Esto permite que los botones en el HTML (onclick) llamen a los métodos del manager
window.limpiarFormularioUnidadClinica = () => window.unidadClinicaManager?.limpiarFormulario();
window.editarUnidadClinica = (id) => window.unidadClinicaManager?.editarRegistro(id);
window.eliminarUnidadClinica = (id) => window.unidadClinicaManager?.eliminarRegistro(id);
window.cerrarModalUnidadClinica = () => window.unidadClinicaManager?.cerrarModal();