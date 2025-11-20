import BaseModalManager from './base-modal-manager.js';
import { validarCorreo } from './validators.js';
import { validarRun } from './validators.js';
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
            entityGender: 'm',
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
        this.initFotoPreview();
        this.addDocenteListeners();
        this.initSearch();
    }

    setModalMaxWidth(panel, sizeClass) {
        if (!panel) return;

        panel.classList.remove(
            'max-w-sm', 'sm:max-w-sm',
            'max-w-md', 'sm:max-w-md',
            'max-w-lg', 'sm:max-w-lg',
            'max-w-xl', 'sm:max-w-xl',
            'max-w-2xl', 'sm:max-w-2xl',
            'max-w-3xl', 'sm:max-w-3xl',
            'max-w-4xl', 'sm:max-w-4xl',
            'max-w-5xl', 'sm:max-w-5xl',
            'max-w-6xl', 'sm:max-w-6xl',
            'max-w-7xl', 'sm:max-w-7xl',
            'w-full'
        );

        panel.classList.add(sizeClass, 'w-full');
    }

    addDocenteListeners() {
        if (document.body.dataset.docenteListenersActive === 'true') {
            return;
        }

        document.body.dataset.docenteListenersActive = 'true';

        document.body.addEventListener('click', (e) => {

            const btnEdit = e.target.closest('[data-action="edit"]');
            const documentosModal = document.getElementById('documentosModal');
            const btnChangeDoc = e.target.closest('[data-action="change-doc"]');

            if (btnEdit && documentosModal && documentosModal.contains(btnEdit)) {
                e.preventDefault();
                const id = btnEdit.dataset.id;
                this.cerrarModalDocumentos();
                this.editarRegistro(id);
                return;
            }

            const btnPreview = e.target.closest('[data-action="preview-doc"]');
            if (btnPreview) {
                e.preventDefault();
                const url = btnPreview.dataset.url;
                const title = btnPreview.dataset.title;
                this.mostrarPreviewDocumento(url, title);
                return;
            }

            if (btnChangeDoc) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const docKey = btnChangeDoc.dataset.docKey;
                const docenteId = btnChangeDoc.dataset.docenteId;
                const fileInput = document.querySelector(`#form-change-${docKey}-${docenteId} input[type="file"]`);
                if (fileInput) {
                    fileInput.click();
                }
                return;
            }

            const btnBack = e.target.closest('#btn-cerrar-preview');
            if (btnBack) {
                e.preventDefault();
                this.cerrarPreviewDocumento();
                return;
            }

            if (documentosModal && !documentosModal.classList.contains('hidden')) {
                if (e.target === documentosModal || e.target.closest('[data-dismiss="modal"]')) {
                    this.cerrarModalDocumentos();
                }
            }
        });
    }

    mostrarPreviewDocumento(url, title) {
        const listaContainer = document.getElementById('lista-documentos-container');
        const previewContainer = document.getElementById('preview-documento-container');
        const iframe = document.getElementById('doc-viewer-iframe');
        const titleSpan = document.getElementById('preview-titulo');
        const modalTitle = document.getElementById('documentosModal-title');
        const modalPanel = document.getElementById('documentosModal-panel');

        if (listaContainer && previewContainer && iframe) {
            listaContainer.classList.add('hidden');
            previewContainer.classList.remove('hidden');

            const extension = url.split('.').pop().toLowerCase();
            const officeExtensions = ['doc', 'docx'];

            if(officeExtensions.includes(extension)) {
                iframe.src = `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
            }else{
                iframe.src = url;
            }

            if (titleSpan) titleSpan.textContent = title;
            if (modalTitle) modalTitle.textContent = 'Visualizando Documento';

            this.setModalMaxWidth(modalPanel, 'sm:max-w-7xl');
        }
    }

    cerrarPreviewDocumento() {
        const listaContainer = document.getElementById('lista-documentos-container');
        const previewContainer = document.getElementById('preview-documento-container');
        const iframe = document.getElementById('doc-viewer-iframe');
        const modalTitle = document.getElementById('documentosModal-title');

        const modal = document.getElementById('documentosModal');
        const modalPanel = modal.querySelector('div[class*="bg-white"]') || modal.querySelector('div[class*="max-w-"]');

        if (listaContainer && previewContainer && iframe) {
            iframe.src = '';

            previewContainer.classList.add('hidden');
            listaContainer.classList.remove('hidden');

            if (modalTitle) modalTitle.textContent = 'Documentos del Docente';

            this.setModalMaxWidth(modalPanel, 'sm:max-w-3xl');
        }
    }

    cerrarModalDocumentos() {
        const modal = document.getElementById('documentosModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    showValidationErrors(errors) {
        this.clearValidationErrors();
        for (const field in errors) {
            const input = this.form.querySelector(`[name="${field}"]`);
            const errorDiv = document.getElementById(`error-${field}`);
            const errorMessage = errors[field][0];

            if (input) {
                input.classList.add('border-red-500');
            }

            if (errorDiv) {
                errorDiv.textContent = errorMessage;
                errorDiv.classList.remove('hidden');
            }
        }
    }

    clearValidationErrors() {
        this.form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });

        this.form.querySelectorAll('[id^="error-"]').forEach(errorDiv => {
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
        });
    }

    initFotoPreview() {
        if (!this.form) return;
        const fotoInput = this.form.querySelector('#foto');
        const fotoPreview = document.getElementById('foto-preview');

        if (!fotoInput || !fotoPreview) return;

        fotoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = (event) => {
                fotoPreview.src = event.target.result;
            };

            reader.readAsDataURL(file);
        });
    }

    initSearch() {
        const searchInput = document.getElementById('search-input');
        const clearBtn = document.getElementById('btn-clear-search');
        const tablaContainer = document.getElementById('tabla-container');

        if (!searchInput || !tablaContainer) return;
        if (clearBtn && searchInput.value.trim().length > 0) {
            clearBtn.classList.remove('hidden');
        }

        const fetchTabla = async (url) => {
            tablaContainer.style.opacity = '0.5';

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Error al buscar');
                
                const html = await response.text();
                tablaContainer.innerHTML = html;
                window.history.pushState(null, '', url);

            } catch (error) {
                console.error('Error en búsqueda:', error);
            } finally {
                tablaContainer.style.opacity = '1';
            }
        };

        let timeoutId;
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value;
            if (clearBtn) {
                if (query.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                const currentUrl = new URL(window.location.href);
                
                if (query) {
                    currentUrl.searchParams.set('search', query);
                } else {
                    currentUrl.searchParams.delete('search');
                }
                
                currentUrl.searchParams.set('page', 1);

                fetchTabla(currentUrl.toString());
            }, 400);
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchInput.focus();
                
                clearBtn.classList.add('hidden');

                const cleanUrl = new URL(window.location.href);
                cleanUrl.searchParams.delete('search');
                cleanUrl.searchParams.delete('page');
                
                fetchTabla(cleanUrl.toString());
            });
        }

        const searchForm = document.getElementById('search-form');
        if(searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
            });
        }

        tablaContainer.addEventListener('click', (e) => {
            const link = e.target.closest('.pagination a'); 
            if (!link) return;
            const pageLink = e.target.closest('a[href*="page="]'); 

            if (pageLink) {
                e.preventDefault();
                fetchTabla(pageLink.href);
            }
        });
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;

        const runInput = this.form.querySelector('[name="runDocente"]');
        const correoInput = this.form.querySelector('[name="correo"]');

        if (runInput && !validarRun(runInput.value)) {
            esValido = false;
            runInput.classList.add('border-red-500');
            const errorDiv = document.getElementById('error-runDocente');
            if (errorDiv) {
                errorDiv.textContent = 'El RUN no cumple con el formato requerido.';
                errorDiv.classList.remove('hidden');
            }
        }
        if (correoInput && !validarCorreo(correoInput.value)) {
            esValido = false;
            correoInput.classList.add('border-red-500');
            const errorDiv = document.getElementById('error-correo');
            if (errorDiv) {
                errorDiv.textContent = 'Correo electrónico inválido.';
                errorDiv.classList.remove('hidden');
            }
        }

        return esValido;
    }

    limpiarFormulario() {
        super.limpiarFormulario();
        const runInput = this.form.querySelector('#runDocente');
        const runHelpText = document.getElementById('run-help-text');
        const fotoPreview = document.getElementById('foto-preview');

        if (runInput) {
            runInput.removeAttribute('readonly');
            runInput.closest('.mb-4').style.display = 'block';
        }
        if (runHelpText) {
            runHelpText.classList.add('hidden');
        }
        if (fotoPreview) {
            fotoPreview.src = "/storage/placeholder.png";
        }
    }

    async editarRegistro(id) {
        const data = await super.editarRegistro(id);
        if (!data) return;

        const runInput = this.form.querySelector('#runDocente');
        const runHelpText = document.getElementById('run-help-text');
        const fotoPreview = document.getElementById('foto-preview');
        const selectSedeCarrera = this.form.querySelector('[name="idSedeCarrera"]');
        const docente = data.docente;
        this.form.querySelector('[name="runDocente"]').value = docente.runDocente;
        this.form.querySelector('[name="nombresDocente"]').value = docente.nombresDocente;
        this.form.querySelector('[name="apellidoPaterno"]').value = docente.apellidoPaterno;
        this.form.querySelector('[name="apellidoMaterno"]').value = docente.apellidoMaterno || '';
        this.form.querySelector('[name="correo"]').value = docente.correo;
        this.form.querySelector('[name="profesion"]').value = docente.profesion;
        this.form.querySelector('[name="fechaNacto"]').value = docente.fechaNacto;

        if (runInput) {
            runInput.setAttribute('readonly', true);
            runInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        }
        if (runHelpText) {
            runHelpText.classList.remove('hidden');
        }
        if (fotoPreview) {
            if (data && data.foto) {
                fotoPreview.src = `/storage/${docente.foto}`;
            } else {
                fotoPreview.src = "/storage/placeholder.png";
            }
        }
        if (selectSedeCarrera && data.sedesCarrerasDisponibles) {

            selectSedeCarrera.innerHTML = '<option value="">Seleccione una opción...</option>';

            data.sedesCarrerasDisponibles.forEach(sede => {
                const option = document.createElement('option');
                option.value = sede.idSedeCarrera;
                const nombreSede = (sede.sede && sede.sede.nombreSede) ? sede.sede.nombreSede : '';
                option.textContent = `${sede.nombreSedeCarrera} (${nombreSede || 'Sin Sede'})`;

                if (sede.idSedeCarrera == data.idSedeCarreraActual) {
                    option.selected = true;
                }
                selectSedeCarrera.appendChild(option);
            });
        }
    }

    async verDocumentos(id) {
        const modal = document.getElementById('documentosModal');
        const modalBody = document.getElementById('documentosModal-body');
        const modalTitle = document.getElementById('documentosModal-title');
        const modalPanel = modal.querySelector('div[class*="bg-white"]') || modal.querySelector('div[class*="max-w-"]');

        if (!modal || !modalBody) return;

        this.setModalMaxWidth(modalPanel, 'sm:max-w-3xl');

        modal.classList.remove('hidden');
        modal.classList.add('flex', 'items-center', 'justify-center');

        if (modalTitle) modalTitle.textContent = 'Documentos del Docente';

        modalBody.innerHTML = `<div class="flex justify-center items-center h-32"><i class="fas fa-spinner fa-spin fa-2x text-gray-500"></i></div>`;

        try {
            const response = await fetch(`/docentes/${id}/documentos`);
            if (!response.ok) throw new Error('No se pudo cargar la lista de documentos.');

            const html = await response.text();

            modalBody.innerHTML = html;
        } catch (error) {
            console.error('Error al ver documentos:', error);
            modalBody.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
        }
    }

    async handleFileChange(fileInput) {
        const file = fileInput.files[0];
        if (!file) return;

        const docKey = fileInput.dataset.docKey; 
        const docenteId = fileInput.dataset.docenteId;

        const docNameReadable = docKey.replace(/([A-Z])/g, ' $1').trim();

        const result = await Swal.fire({
            title: '¿Cambiar archivo?',
            text: `Se reemplazará el documento actual de "${docNameReadable}".`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) {
            fileInput.value = ''; 
            return;
        }

        Swal.fire({
            title: 'Subiendo archivo...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            await this.uploadDocument(docenteId, docKey, file);
            await Swal.fire({
                title: '¡Actualizado!',
                text: 'El documento se ha subido correctamente.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            this.verDocumentos(docenteId);
        } catch (error) {
            console.error('Error al subir documento:', error);
            Swal.fire({
                title: 'Error',
                text: error.message || 'No se pudo subir el archivo.',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        } finally {
            fileInput.value = '';
        }
    }

    async uploadDocument(docenteId, docKey, file) {
        const formData = new FormData();
        formData.append('_method', 'POST');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append(docKey, file);

        const url = `docentes/${docenteId}/upload-document`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData,
        });

        if (!response.ok) {
            let errorMessage = 'Error al subir el documento.';
            try {
                const errorData = await response.json();
                errorMessage = errorData.message || errorMessage;
                
                if (errorData.errors) {
                    errorMessage += ': ' + Object.values(errorData.errors).flat().join(', ');
                }
            } catch (e) {
                console.error('Error crítico (HTML recibido):', await response.text());
                errorMessage = 'Error crítico del servidor. Revisa la consola (F12) para más detalles.';
            }
            throw new Error(errorMessage);
        }

        return response.json();
    }
}

window.docenteManager = new DocenteManager();

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('docenteForm')) {
        new DocenteManager();
    }
});