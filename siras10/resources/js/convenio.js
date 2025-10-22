import BaseModalManager from './base-modal-manager.js';

/**
 * Convenio Manager
 * Extiende BaseModalManager para manejar la lógica de convenios.
 */
class ConvenioManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'convenioModal',
            formId: 'convenioForm',
            entityName: 'Convenio',
            entityGender: 'm',
            baseUrl: '/convenios',
            primaryKey: 'idConvenio',
            tableContainerId: 'tabla-container',
            fields: [
                'idCentroFormador',
                'anioValidez',
                'fechaSubida'
            ]
        });

        this.initFileUpload();
    }

    /**
     * Sobrescribe el método del padre para manejar la lógica de llenado
     * del formulario, evitando el campo de archivo.
     */
    fillForm(data) {
        console.log('📝 Llenando formulario de convenio con:', data);

        // Llama al método del padre, pero solo con los campos seguros
        super.fillForm(data);

        // Limpiar el campo de archivo (esto es seguro)
        const documentoInput = document.getElementById('documento');
        if (documentoInput) {
            documentoInput.value = '';
        }

        // Mostrar información del documento actual si existe
        if (data.documento) {
            this.mostrarDocumentoActual(data);
        }

        // Mostrar fecha de subida si existe
        if (data.fechaSubida) {
            this.mostrarFechaSubida(data.fechaSubida);
        }
    }

    /**
     * Sobrescribe el método del padre para limpiar elementos específicos
     * del formulario de convenios.
     */
    clearForm() {
        super.clearForm();

        // Limpiar previsualizaciones específicas
        const preview = document.getElementById('archivo-preview');
        if (preview) {
            preview.innerHTML = '';
        }

        const fechaContainer = document.getElementById('fechaSubida-container');
        if (fechaContainer) {
            fechaContainer.innerHTML = '';
        }
    }

    /**
     * Muestra una previsualización del documento actual al editar.
     */
    mostrarDocumentoActual(convenio) {
        const preview = document.getElementById('archivo-preview');
        if (preview) {
            preview.innerHTML = `
                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Documento actual disponible</p>
                                <p class="text-xs text-blue-700">Subir nuevo archivo solo si desea reemplazarlo</p>
                            </div>
                        </div>
                        <button type="button" onclick="verDocumento(${convenio.idConvenio})" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver actual
                        </button>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Muestra la fecha de subida original al editar.
     */
    mostrarFechaSubida(fechaSubida) {
        const fechaContainer = document.getElementById('fechaSubida-container');
        if (fechaContainer) {
            const fecha = new Date(fechaSubida).toLocaleDateString('es-ES');
            fechaContainer.innerHTML = `
                <div class="p-3 bg-gray-50 border border-gray-200 rounded">
                    <p class="text-sm text-gray-600">
                        <strong>Fecha de subida original:</strong> ${fecha}
                    </p>
                </div>
            `;
        }
    }

    /**
     * Inicializa la funcionalidad de previsualización de archivos.
     */
    initFileUpload() {
        const fileInput = document.getElementById('documento');
        const preview = document.getElementById('archivo-preview');

        if (fileInput && preview) {
            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    preview.innerHTML = `
                        <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded">
                            <p class="text-sm font-medium text-blue-900">Nuevo archivo: ${file.name}</p>
                            <p class="text-xs text-blue-700">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        </div>
                    `;
                }
            });
        }
    }

    // Los métodos showValidationErrors, clearValidationErrors y validate
    // son opcionales si la lógica del padre es suficiente.
    // Por consistencia con sede.js, los dejamos.
    showValidationErrors(errors) {
        super.showValidationErrors(errors);
    }

    clearValidationErrors() {
        super.clearValidationErrors();
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;

        // Validar centro formador
        if (!this.form.querySelector('[name="idCentroFormador"]').value) {
            esValido = false;
            this.showValidationErrors({ 'idCentroFormador': ['Debe seleccionar un centro formador.'] });
        }

        // Validar año de validez
        if (!this.form.querySelector('[name="anioValidez"]').value) {
            esValido = false;
            this.showValidationErrors({ 'anioValidez': ['El año de validez es obligatorio.'] });
        }

        // Validar documento (solo en creación)
        if (!this.editando) {
            const docInput = this.form.querySelector('[name="documento"]');
            if (!docInput.files || docInput.files.length === 0) {
                esValido = false;
                this.showValidationErrors({ 'documento': ['Debe seleccionar un documento.'] });
            }
        }
        return esValido;
    }
}


// ============================================================================
// INICIALIZACIÓN Y FUNCIONES GLOBALES
// ============================================================================

let convenioManager;

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('convenioForm')) {
        console.log('🚀 Inicializando ConvenioManager...');
        convenioManager = new ConvenioManager();
    }
});

/**
 * ✅ AGREGAR: Funciones globales para que los botones onclick funcionen.
 */
window.abrirModal = function () {
    if (convenioManager) {
        convenioManager.limpiarFormulario();
    } else {
        console.error('ConvenioManager no está inicializado.');
    }
};

window.editarConvenio = function (id) {
    if (convenioManager) {
        convenioManager.editarRegistro(id);
    } else {
        console.error('ConvenioManager no está inicializado.');
    }
};

window.eliminarConvenio = function (id) {
    if (convenioManager) {
        convenioManager.eliminarRegistro(id);
    } else {
        console.error('ConvenioManager no está inicializado.');
    }
};

window.verDocumento = function (id) {
    window.open(`/convenios/${id}/documento/ver`, '_blank');
};

window.descargarDocumento = function (id) {
    window.location.href = `/convenios/${id}/documento/descargar`;
};