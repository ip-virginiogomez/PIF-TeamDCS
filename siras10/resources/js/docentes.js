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
                'nombresDocente',
                'apellidoPaterno',
                'apellidoMaterno',
                'fechaNacto',
                'correo',
                'profesion',
                'foto',
                'curriculum',
                'certSuperInt',
                'certRCP',
                'certIAAS',
                'acuerdo'
            ]
        });
    }

    /**
     * Sobrescribe limpiarFormulario para manejar campos específicos de docentes
     */
    limpiarFormulario() {
        super.limpiarFormulario();

        const runInputVisible = document.getElementById('runDocenteVisible');
        const runInputHidden = document.getElementById('runDocente');
        const runHelpText = document.getElementById('run-help-text');

        if (runInputVisible) {
            runInputVisible.disabled = false;
            runInputVisible.style.backgroundColor = '';
            runInputVisible.style.cursor = '';

            // Sincronizar con el campo oculto
            runInputVisible.addEventListener('input', (e) => {
                if (runInputHidden) {
                    runInputHidden.value = e.target.value;
                }
            });
        }

        if (runHelpText) {
            runHelpText.classList.add('hidden');
        }

        // Ocultar previsualizaciones de documentos
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
        console.log('=== DEBUG EDITAR DOCENTE ===');
        console.log('RUN recibido:', runDocente);
        console.log('Base URL:', this.config.baseUrl);

        const encodedRun = encodeURIComponent(runDocente);
        console.log('RUN codificado:', encodedRun);

        const url = `${this.config.baseUrl}/${encodedRun}/edit`;
        console.log('URL final generada:', url);

        // Verificar si la URL existe haciendo una petición de prueba
        try {
            console.log('Haciendo petición a:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            console.log('Response recibida:', {
                status: response.status,
                statusText: response.statusText,
                headers: response.headers.get('content-type')
            });

            if (response.status === 405) {
                console.error('ERROR 405: Método no permitido');
                console.log('Verifica que la ruta GET /docentes/{docente}/edit exista');
                return;
            }

            if (!response.ok) {
                console.error('Error HTTP:', response.status, response.statusText);
                return;
            }

            const data = await response.json();
            console.log('Data recibida:', data);

            this.setFormValues({
                runDocente: data.runDocente,
                method: 'PUT',
                nombresDocente: data.nombresDocente,
                apellidoPaterno: data.apellidoPaterno,
                apellidoMaterno: data.apellidoMaterno || '',
                fechaNacto: data.fechaNacto,
                correo: data.correo,
                profesion: data.profesion
            });

            // Manejar el campo RUN visible - formatearlo automáticamente
            const runInputVisible = document.getElementById('runDocenteVisible');
            if (runInputVisible && data.runDocente) {
                // Formatear el RUN para mostrarlo
                const formattedRun = this.formatRun(data.runDocente);
                runInputVisible.value = formattedRun;
                runInputVisible.disabled = true;
                runInputVisible.style.backgroundColor = '#f3f4f6';
                runInputVisible.style.cursor = 'not-allowed';
            }

            // Mostrar documentos existentes
            const documentFields = ['foto', 'curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo'];
            documentFields.forEach(field => {
                if (data[field]) {
                    const previewDiv = document.getElementById(`${field}-actual`);
                    if (previewDiv) {
                        const link = previewDiv.querySelector('a');
                        if (link) {
                            link.href = `/storage/${data[field]}`;
                            link.textContent = data[field].split('/').pop();
                        }
                        previewDiv.classList.remove('hidden');
                    }
                }
            });

            const runHelpText = document.getElementById('run-help-text');
            if (runHelpText) {
                runHelpText.classList.remove('hidden');
            }

            this.setModalTitle('Editar Docente');
            this.setButtonText('Actualizar Docente');
            this.mostrarModal();

        } catch (error) {
            console.error('Error completo:', error);
            this.showAlert('Error', 'No se pudo cargar el docente: ' + error.message, 'error');
        }
    }

    /**
     * Formatear RUN para visualización
     */
    formatRun(run) {
        if (!run) return run;

        // Limpiar el RUN (solo números y K)
        const cleanRun = run.replace(/[^0-9kK]/g, '').toUpperCase();

        if (cleanRun.length < 2) return cleanRun;

        // Aplicar el mismo formato que en alumnos
        return cleanRun
            .replace(/^(\d{1,2})(\d{3})(\d{3})(\w{1})$/, '$1.$2.$3-$4')
            .replace(/^(\d{1,2})(\d{3})(\d{1,3})$/, '$1.$2.$3')
            .replace(/^(\d{1,2})(\d{1,3})$/, '$1.$2');
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
     * Manejar envío del formulario
     */
    async handleFormSubmit(e) {
        e.preventDefault();

        const formData = new FormData(this.form);
        const method = document.getElementById('method')?.value || 'POST';

        // DEBUG
        console.log('=== FORM SUBMIT DEBUG ===');
        console.log('Método:', method);

        let url = this.config.baseUrl;

        if (method === 'PUT') {
            // Para actualización, necesitamos el RUN del docente
            const runDocente = formData.get('runDocente') || document.getElementById('runDocente')?.value;
            console.log('RUN para actualizar:', runDocente);

            if (!runDocente) {
                this.showAlert('Error', 'No se pudo obtener el RUN del docente', 'error');
                return;
            }

            url += `/${encodeURIComponent(runDocente)}`;
            // Laravel espera _method=PUT en el FormData para simular PUT con POST
            formData.append('_method', 'PUT');
        }

        console.log('URL final:', url);
        console.log('Método HTTP real:', method === 'PUT' ? 'POST' : method);

        try {
            const response = await fetch(url, {
                method: method === 'PUT' ? 'POST' : method, // Laravel routing
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON válido');
            }

            const data = await response.json();

            if (data.success) {
                await this.showAlert('¡Éxito!', data.message, 'success');
                this.cerrarModal();
                location.reload();
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
    window.docenteManager.createGlobalFunctions();

    // Validación del RUN en tiempo real (igual que en alumnos)
    /*
    const runInput = document.getElementById('runDocenteVisible');
    if (runInput) {
        runInput.addEventListener('input', function (e) {
            const run = e.target.value.replace(/[^0-9kK]/g, '');
            if (run.length > 0) {
                const formatted = run
                    .replace(/^(\d{1,2})(\d{3})(\d{3})(\w{1})$/, '$1.$2.$3-$4')
                    .replace(/^(\d{1,2})(\d{3})(\d{1,3})$/, '$1.$2.$3')
                    .replace(/^(\d{1,2})(\d{1,3})$/, '$1.$2');
                e.target.value = formatted;
            }

            // Sincronizar con campo oculto
            const hiddenRun = document.getElementById('runDocente');
            if (hiddenRun) {
                hiddenRun.value = e.target.value;
            }
        });
    }
    */
});

// Funciones globales para compatibilidad
window.limpiarFormularioDocente = () => window.docenteManager?.limpiarFormulario();
window.editarDocente = (runDocente) => window.docenteManager?.editarRegistro(runDocente);
window.eliminarDocente = (runDocente) => window.docenteManager?.eliminarRegistro(runDocente);
window.cerrarModalDocente = () => window.docenteManager?.cerrarModal();