/**
 * Docente Manager
 * Extiende BaseModalManager para funcionalidad específica de docentes
 */

class DocenteManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'docenteModal',
            formId: 'docenteForm',
            entityName: 'Docente',
            baseUrl: '/docentes',
            primaryKey: 'runDocente',
            fields: [
                'runDocente',
                'nombresDocente',
                'apellidoPaterno',
                'apellidoMaterno',
                'fechaNacto',
                'correo',
                'profesion'
            ]
        });
    }

    /**
     * Sobrescribe limpiarFormulario para manejar campos específicos de docentes
     */
    limpiarFormulario() {
        super.limpiarFormulario();

        this.editando = false;

        // ✅ ADAPTADO DE ALUMNOS: Habilitar y resetear estilos del RUN cuando se crea nuevo
        const runInput = document.getElementById('runDocenteVisible');
        const runHidden = document.getElementById('runDocente');
        const runHelpText = document.getElementById('run-help-text');

        if (runInput) {
            runInput.disabled = false;
            runInput.style.backgroundColor = '';
            runInput.style.cursor = '';

            // ✅ IGUAL QUE ALUMNOS: Agregar event listener para sincronización
            runInput.addEventListener('input', (e) => {
                // Sincronizar con el campo oculto
                if (runHidden) {
                    runHidden.value = e.target.value;
                }
            });

            // ✅ IGUAL QUE ALUMNOS: Ocultar texto de ayuda
            if (runHelpText) {
                runHelpText.classList.add('hidden');
            }
        }

        // ✅ ADAPTADO DE ALUMNOS: Ocultar previsualizaciones de documentos
        const documentFields = ['foto', 'curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo'];
        documentFields.forEach(field => {
            const previewDiv = document.getElementById(`${field}-actual`);
            if (previewDiv) {
                previewDiv.classList.add('hidden');
            }
        });
    }

    /**
     * Sobrescribe el método de edición para mapear correctamente los datos
     */
    async editarRegistro(runDocente) {
        this.editando = true;

        try {
            // ✅ IGUAL QUE ALUMNOS: Codificar el RUN para URL
            const encodedRun = encodeURIComponent(runDocente);
            const response = await fetch(`${this.config.baseUrl}/${encodedRun}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // ✅ ADAPTADO DE ALUMNOS: setFormValues con campos específicos de docentes
            this.setFormValues({
                runDocente: data.runDocente,
                method: 'PUT',
                nombresDocente: data.nombresDocente,
                apellidoPaterno: data.apellidoPaterno,
                apellidoMaterno: data.apellidoMaterno || '',
                fechaNacto: data.fechaNacto,
                correo: data.correo,
                profesion: data.profesion
                // No incluimos archivos porque no se pueden pre-llenar
            });

            // ✅ IGUAL QUE ALUMNOS: Establecer el RUN en el campo visible también
            const runInputVisible = document.getElementById('runDocenteVisible');
            if (runInputVisible) {
                runInputVisible.value = data.runDocente;
            }

            // ✅ IGUAL QUE ALUMNOS: Deshabilitar completamente el campo RUN visible en modo edición
            const runHelpText = document.getElementById('run-help-text');
            if (runInputVisible) {
                runInputVisible.disabled = true;
                runInputVisible.style.backgroundColor = '#f3f4f6'; // Color gris claro para indicar que no es editable
                runInputVisible.style.cursor = 'not-allowed';

                // Mostrar texto de ayuda
                if (runHelpText) {
                    runHelpText.classList.remove('hidden');
                }
            }

            // ✅ ADAPTADO DE ALUMNOS: Mostrar archivos actuales si existen
            const documentFields = ['foto', 'curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo'];
            documentFields.forEach(field => {
                if (data[field]) {
                    const actualDiv = document.getElementById(`${field}-actual`);
                    const link = document.getElementById(`${field}-link`);
                    if (actualDiv && link) {
                        link.href = `/storage/${data[field]}`;
                        link.textContent = data[field].split('/').pop(); // Solo el nombre del archivo
                        actualDiv.classList.remove('hidden');
                    } else if (actualDiv) {
                        actualDiv.classList.add('hidden');
                    }
                }
            });

            this.setModalTitle('Editar Docente');
            this.setButtonText('Actualizar Docente');
            this.mostrarModal();

        } catch (error) {
            console.error('Error:', error); // ✅ IGUAL QUE ALUMNOS
            this.showAlert('Error', 'No se pudo cargar los datos del docente', 'error'); // ✅ ADAPTADO
        }
    }

    /**
     * Sobrescribe el método de eliminación
     */
    async eliminarRegistro(runDocente) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará permanentemente al docente',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const encodedRun = encodeURIComponent(runDocente);
                const response = await fetch(`${this.config.baseUrl}/${encodedRun}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    await this.showAlert('¡Eliminado!', data.message, 'success');
                    // Recargar la página para mostrar el estado actualizado
                    location.reload();
                } else {
                    this.showAlert('Error', data.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                this.showAlert('Error', 'No se pudo eliminar el docente', 'error');
            }
        }
    }

    /**
     * Manejar envío del formulario - ADAPTADO DE ALUMNOS
     */
    async handleFormSubmit(e) {
        e.preventDefault();

        // ✅ IGUAL QUE ALUMNOS: Asegurar que el campo oculto tenga el valor correcto del RUN
        const runInputVisible = document.getElementById('runDocenteVisible');
        const runInputHidden = document.getElementById('runDocente');

        if (runInputVisible && runInputHidden && !this.editando) {
            // Solo para crear nuevos docentes, sincronizar el valor
            runInputHidden.value = runInputVisible.value;
        }

        const formData = new FormData(this.form);
        const runDocente = document.getElementById(this.config.primaryKey).value; // ✅ IGUAL QUE ALUMNOS
        const method = document.getElementById('method').value; // ✅ IGUAL QUE ALUMNOS

        let url = this.config.baseUrl;
        if (method === 'PUT') {
            // ✅ IGUAL QUE ALUMNOS: Codificar el RUN para URL
            const encodedRun = encodeURIComponent(runDocente);
            url += `/${encodedRun}`;
        }

        try {
            const response = await fetch(url, {
                method: 'POST', // ✅ IGUAL QUE ALUMNOS: Siempre POST (Laravel maneja PUT con _method)
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // ✅ IGUAL QUE ALUMNOS: Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // ✅ IGUAL QUE ALUMNOS: Verificar content-type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON válido');
            }

            const data = await response.json();
            console.log('Response data:', data); // ✅ IGUAL QUE ALUMNOS: Debug

            if (data.success) {
                await this.showAlert('¡Éxito!', data.message, 'success');
                this.cerrarModal();
                location.reload(); // ✅ IGUAL QUE ALUMNOS: Recargar para mostrar el nuevo docente
            } else {
                if (data.errors) {
                    this.showValidationErrors(data.errors);
                } else {
                    this.showAlert('Error', data.message || 'Error desconocido', 'error');
                }
            }

        } catch (error) {
            console.error('Error completo:', error); // ✅ IGUAL QUE ALUMNOS
            this.showAlert('Error', `Error al procesar la solicitud: ${error.message}`, 'error');
        }
    }

    /**
     * Sobrescribe showValidationErrors para manejar el campo RUN visible
     */
    showValidationErrors(errors) {
        this.clearValidationErrors();

        Object.keys(errors).forEach(field => {
            let inputElement;
            let errorDiv;

            // Manejar el campo runDocente especialmente
            if (field === 'runDocente') {
                inputElement = document.getElementById('runDocenteVisible');
                errorDiv = document.getElementById('error-runDocente');
            } else {
                inputElement = document.getElementById(field);
                errorDiv = document.getElementById(`error-${field}`);
            }

            if (inputElement && errorDiv) {
                inputElement.classList.add('border-red-500');
                errorDiv.classList.remove('hidden');
                errorDiv.textContent = errors[field][0];
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    window.docenteManager = new DocenteManager();

    // ✅ AGREGAR: Crear funciones globales para compatibilidad (como en alumnos)
    window.docenteManager.createGlobalFunctions();
});

// Funciones globales para compatibilidad
window.limpiarFormularioDocente = () => window.docenteManager?.limpiarFormulario();
window.editarDocente = (runDocente) => window.docenteManager?.editarRegistro(runDocente);
window.eliminarDocente = (runDocente) => window.docenteManager?.eliminarRegistro(runDocente);
window.cerrarModalDocente = () => window.docenteManager?.cerrarModal();