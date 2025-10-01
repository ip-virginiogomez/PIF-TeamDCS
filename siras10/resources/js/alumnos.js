/**
 * Alumno Manager
 * Extiende BaseModalManager para funcionalidad específica de alumnos
 */

class AlumnoManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'alumnoModal',
            formId: 'alumnoForm',
            entityName: 'Alumno',
            baseUrl: '/alumnos',
            primaryKey: 'runAlumno',
            fields: [
                'nombres',
                'apellidoPaterno',
                'apellidoMaterno',
                'fechaNacto',
                'correo',
                'acuerdo'
            ]
        });
    }

    /**
     * Sobrescribe limpiarFormulario para habilitar el campo RUN
     */
    limpiarFormulario() {
        super.limpiarFormulario();

        // Habilitar y resetear estilos del RUN cuando se crea nuevo
        const runInput = document.getElementById('runAlumnoVisible');
        const runHidden = document.getElementById('runAlumno');
        const runHelpText = document.getElementById('run-help-text');

        if (runInput) {
            runInput.disabled = false;
            runInput.style.backgroundColor = '';
            runInput.style.cursor = '';
            runInput.addEventListener('input', (e) => {
                // Sincronizar con el campo oculto
                if (runHidden) {
                    runHidden.value = e.target.value;
                }
            });

            // Ocultar texto de ayuda
            if (runHelpText) {
                runHelpText.classList.add('hidden');
            }
        }

        // Ocultar archivo de acuerdo actual
        const acuerdoActual = document.getElementById('acuerdo-actual');
        if (acuerdoActual) {
            acuerdoActual.classList.add('hidden');
        }
    }

    /**
     * Sobrescribe el método de edición para mapear correctamente los datos
     */
    async editarRegistro(runAlumno) {
        this.editando = true;

        try {
            // Codificar el RUN para URL
            const encodedRun = encodeURIComponent(runAlumno);
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

            this.setFormValues({
                runAlumno: data.runAlumno,
                method: 'PUT',
                nombres: data.nombres,
                apellidoPaterno: data.apellidoPaterno,
                apellidoMaterno: data.apellidoMaterno || '',
                fechaNacto: data.fechaNacto,
                correo: data.correo
                // No incluimos 'acuerdo' porque es un archivo y no se puede pre-llenar
            });

            // Establecer el RUN en el campo visible también
            const runInputVisible = document.getElementById('runAlumnoVisible');
            if (runInputVisible) {
                runInputVisible.value = data.runAlumno;
            }

            // Deshabilitar completamente el campo RUN visible en modo edición
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

            // Mostrar archivo de acuerdo actual si existe
            const acuerdoActual = document.getElementById('acuerdo-actual');
            const acuerdoLink = document.getElementById('acuerdo-link');
            if (data.acuerdo && acuerdoActual && acuerdoLink) {
                acuerdoLink.href = `/storage/${data.acuerdo}`;
                acuerdoLink.textContent = data.acuerdo.split('/').pop(); // Solo el nombre del archivo
                acuerdoActual.classList.remove('hidden');
            } else if (acuerdoActual) {
                acuerdoActual.classList.add('hidden');
            }

            this.setModalTitle('Editar Alumno');
            this.setButtonText('Actualizar Alumno');
            this.mostrarModal();

        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error', 'No se pudo cargar los datos del alumno', 'error');
        }
    }

    /**
     * Sobrescribe el método de eliminación para usar el RUN correcto
     */
    async eliminarRegistro(runAlumno) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará permanentemente al alumno',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                // Codificar el RUN para URL
                const encodedRun = encodeURIComponent(runAlumno);
                const response = await fetch(`${this.config.baseUrl}/${encodedRun}`, {
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
                    const row = document.getElementById(`alumno-${runAlumno}`);
                    if (row) {
                        row.remove();
                    }
                    this.showAlert('¡Eliminado!', data.message, 'success');
                } else {
                    this.showAlert('Error', data.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                this.showAlert('Error', 'No se pudo eliminar el alumno', 'error');
            }
        }
    }

    /**
     * Maneja el archivo de foto si está presente
     */
    async handleFormSubmit(e) {
        e.preventDefault();

        // Asegurar que el campo oculto tenga el valor correcto del RUN
        const runInputVisible = document.getElementById('runAlumnoVisible');
        const runInputHidden = document.getElementById('runAlumno');

        if (runInputVisible && runInputHidden && !this.editando) {
            // Solo para crear nuevos alumnos, sincronizar el valor
            runInputHidden.value = runInputVisible.value;
        }

        const formData = new FormData(this.form);
        const runAlumno = document.getElementById(this.config.primaryKey).value;
        const method = document.getElementById('method').value;

        let url = this.config.baseUrl;
        if (method === 'PUT') {
            // Codificar el RUN para URL
            const encodedRun = encodeURIComponent(runAlumno);
            url += `/${encodedRun}`;
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

            // Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON válido');
            }

            const data = await response.json();
            console.log('Response data:', data); // Debug

            if (data.success) {
                await this.showAlert('¡Éxito!', data.message, 'success');
                this.cerrarModal();
                location.reload(); // Recargar para mostrar el nuevo alumno
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

            // Manejar el campo runAlumno especialmente
            if (field === 'runAlumno') {
                inputElement = document.getElementById('runAlumnoVisible');
                errorDiv = document.getElementById('error-runAlumno');
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
    // Crear instancia global del manager
    window.alumnoManager = new AlumnoManager();

    // Crear funciones globales para compatibilidad
    window.alumnoManager.createGlobalFunctions();
});

// Funciones específicas para Alumnos (compatibilidad)
window.limpiarFormularioAlumno = () => window.alumnoManager?.limpiarFormulario();
window.editarAlumno = (runAlumno) => window.alumnoManager?.editarRegistro(runAlumno);
window.eliminarAlumno = (runAlumno) => window.alumnoManager?.eliminarRegistro(runAlumno);
window.cerrarModalAlumno = () => window.alumnoManager?.cerrarModal();