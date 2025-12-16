import BaseModalManager from './base-modal-manager.js';
import { validarCorreo } from './validators.js';
import { validarRun } from './validators.js';
import DocenteVacunaManager from './docentevacuna.js';

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

        this.docsElements = {
            modal: document.getElementById('modalDocumentosDocente'),
            container: document.getElementById('contenido-docs-docente'),
            titulo: document.getElementById('titulo-modal-docs'),
            backdrop: 'backdrop-docs-docente'
        };

        this.initFotoPreview();
        this.initDocumentosViewer();
        this.initSearch();
        this.initRunInputRestriction();

        // Inicializar gestor de vacunas
        this.vacunaManager = new DocenteVacunaManager();
    }

    initRunInputRestriction() {
        const runInput = this.form.querySelector('[name="runDocente"]');
        if (runInput) {
            runInput.addEventListener('input', (e) => {
                // Solo permitir números, K, k y guión
                const valor = e.target.value;
                const valorLimpio = valor.replace(/[^0-9kK\-]/g, '');
                if (valor !== valorLimpio) {
                    e.target.value = valorLimpio;
                }
            });

            runInput.addEventListener('keypress', (e) => {
                // Prevenir caracteres no permitidos antes de que se ingresen
                const char = String.fromCharCode(e.which || e.keyCode);
                if (!/[0-9kK\-]/.test(char)) {
                    e.preventDefault();
                }
            });
        }
    }

    initDocumentosViewer() {
        // Mover el modal al body para evitar problemas de apilamiento (z-index)
        const modal = document.getElementById('modalDocumentosDocente');
        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        // Usar el contenedor de la tabla para delegación de eventos
        const tablaContainer = document.getElementById('tabla-container');

        if (tablaContainer) {
            tablaContainer.addEventListener('click', (e) => {
                const btnDocs = e.target.closest('button[data-action="view-docs"]');
                if (btnDocs) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.abrirModalDocumentos(btnDocs.dataset.run, btnDocs.dataset.nombre);
                }
            });
        }

        // Listener para cerrar el modal
        document.body.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="close-modal-docs"]') || e.target.id === this.docsElements.backdrop) {
                this.cerrarModalDocumentos();
            }
        });
    }

    async abrirModalDocumentos(run, nombre) {
        const modal = document.getElementById('modalDocumentosDocente');
        if (!modal) return;

        // Actualizar referencia
        this.docsElements.modal = modal;
        this.docsElements.container = document.getElementById('contenido-docs-docente');
        this.docsElements.titulo = document.getElementById('titulo-modal-docs');

        this.docsElements.titulo.textContent = `Documentos: ${nombre}`;
        this.docsElements.modal.classList.remove('hidden');
        this.docsElements.modal.style.display = 'block';

        // Mostrar loading
        this.docsElements.container.innerHTML = `
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-10 h-10 animate-spin text-gray-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
        `;

        try {
            const response = await fetch(`${this.config.baseUrl}/${run}/documentos`);
            if (!response.ok) throw new Error('Error al cargar documentos');

            const html = await response.text();
            this.docsElements.container.innerHTML = html;

        } catch (error) {
            this.docsElements.container.innerHTML = `
                <div class="w-full h-full flex items-center justify-center text-red-500">
                    <p>Error al cargar los documentos.</p>
                </div>
            `;
        }
    }

    cerrarModalDocumentos() {
        const modal = document.getElementById('modalDocumentosDocente');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none'; // Asegurar ocultamiento
            if (this.docsElements.container) {
                this.docsElements.container.innerHTML = '';
            }
        }
    }

    initSearch() {
        const searchInput = document.getElementById('search-input');
        const clearBtn = document.getElementById('btn-clear-search');
        const tablaContainer = document.getElementById('tabla-container');

        const filterCentro = document.getElementById('filter-centro');
        const filterSedeCarrera = document.getElementById('filter-sede-carrera');

        if (!searchInput || !tablaContainer) return;

        // Mostrar X si ya hay texto
        if (clearBtn && searchInput.value.trim().length > 0) {
            clearBtn.classList.remove('hidden');
            clearBtn.classList.add('flex');
        }

        // --- FUNCIÓN PARA EJECUTAR BÚSQUEDA ---
        const executeSearch = (page = 1) => {
            const params = new URLSearchParams();

            if (searchInput.value.trim()) {
                params.append('search', searchInput.value.trim());
            }

            if (filterCentro && filterCentro.value) {
                params.append('centro_id', filterCentro.value);
            }

            if (filterSedeCarrera && filterSedeCarrera.value) {
                params.append('sede_carrera_id', filterSedeCarrera.value);
            }

            if (page > 1) {
                params.append('page', page);
            }

            // Mantener ordenamiento si existe en URL actual
            const currentUrlParams = new URLSearchParams(window.location.search);
            if (currentUrlParams.has('sort_by')) params.append('sort_by', currentUrlParams.get('sort_by'));
            if (currentUrlParams.has('sort_direction')) params.append('sort_direction', currentUrlParams.get('sort_direction'));

            const newUrl = `${this.config.baseUrl}?${params.toString()}`;

            // Actualizar URL del navegador sin recargar
            window.history.pushState({}, '', newUrl);

            fetchTabla(newUrl);
        };

        const fetchTabla = async (url) => {
            tablaContainer.style.opacity = '0.5';
            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Error al cargar tabla');
                const html = await response.text();
                tablaContainer.innerHTML = html;
            } catch (error) {
                // Opcional: Mostrar mensaje de error en tabla
            } finally {
                tablaContainer.style.opacity = '1';
            }
        };

        // --- EVENT LISTENERS ---

        // 1. Input de búsqueda (Debounce)
        let timeoutId;
        searchInput.addEventListener('input', () => {
            if (searchInput.value.trim().length > 0) {
                clearBtn.classList.remove('hidden');
                clearBtn.classList.add('flex');
            } else {
                clearBtn.classList.add('hidden');
                clearBtn.classList.remove('flex');
            }

            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                executeSearch(1);
            }, 300);
        });

        // 2. Botón Limpiar
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                clearBtn.classList.add('hidden');
                clearBtn.classList.remove('flex');

                // Resetear filtros también si se desea, o solo búsqueda
                // filterCentro.value = '';
                // filterSedeCarrera.value = '';

                executeSearch(1);
            });
        }

        // 3. Filtros Select
        if (filterCentro) {
            filterCentro.addEventListener('change', () => {
                // Lógica de cascada para Sede/Carrera
                const centroId = filterCentro.value;
                const sedeSelect = filterSedeCarrera;

                // Limpiar select de sedes
                sedeSelect.innerHTML = '<option value="">Todas las Carreras</option>';

                if (centroId) {
                    // Cargar sedes del centro seleccionado
                    fetch(`/docentes/sedes-carreras-by-centro?centro_id=${centroId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.idSedeCarrera;
                                option.textContent = `${item.nombreSedeCarrera} - ${item.sede.nombreSede}`;
                                sedeSelect.appendChild(option);
                            });
                        });
                } else {
                    // Si no hay centro, cargar todas (o dejar vacío según lógica de negocio)
                    // Aquí podrías volver a cargar todas si tienes un endpoint para ello
                    // O simplemente dejar que el filtro de backend maneje "todos"
                }

                executeSearch(1);
            });
        }

        if (filterSedeCarrera) {
            filterSedeCarrera.addEventListener('change', () => {
                executeSearch(1);
            });
        }

        // 4. Paginación (Delegación de eventos)
        tablaContainer.addEventListener('click', (e) => {
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                const url = link.getAttribute('href');
                // Extraer página de la URL
                const urlObj = new URL(url);
                const page = urlObj.searchParams.get('page');

                executeSearch(page);
            }

            // Ordenamiento
            const sortLink = e.target.closest('a[data-sort]');
            if (sortLink) {
                e.preventDefault();
                // La lógica de ordenamiento ya debería estar en los links generados por el backend
                // Pero si queremos usar AJAX, debemos interceptar y usar fetchTabla
                const url = sortLink.getAttribute('href');
                window.history.pushState({}, '', url);
                fetchTabla(url);
            }
        });
    }

    initFotoPreview() {
        const fotoInput = document.getElementById('foto');
        if (fotoInput) {
            fotoInput.addEventListener('change', function (e) {
                // Implementar preview si es necesario
            });
        }
    }

    // Sobrescribir método para editar registro
    async editarRegistro(id) {
        const data = await super.editarRegistro(id);

        if (data && data.docente) {
            // Llenar campos del formulario manualmente ya que data.docente está anidado
            this.config.fields.forEach(field => {
                const element = this.form.querySelector(`[name="${field}"]`);
                if (element && data.docente[field] !== undefined) {
                    if (element.type === 'date' && data.docente[field]) {
                        element.value = data.docente[field].substring(0, 10);
                    } else {
                        element.value = data.docente[field];
                    }
                }
            });

            // Llenar select de SedeCarrera
            const selectSede = document.getElementById('idSedeCarrera');
            if (selectSede && data.sedesCarrerasDisponibles) {
                selectSede.value = data.idSedeCarreraActual || '';
            }

            // Mostrar enlaces a documentos existentes
            this.mostrarEnlaceDocumento('curriculum', data.docente.curriculum);
            this.mostrarEnlaceDocumento('certSuperInt', data.docente.certSuperInt);
            this.mostrarEnlaceDocumento('certRCP', data.docente.certRCP);
            this.mostrarEnlaceDocumento('certIAAS', data.docente.certIAAS);
            this.mostrarEnlaceDocumento('acuerdo', data.docente.acuerdo);

            // Manejo especial para RUN (deshabilitar en edición)
            const runInput = document.getElementById('runDocente');
            const runHelp = document.getElementById('run-help-text');
            if (runInput) {
                runInput.readOnly = true;
                runInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                if (runHelp) runHelp.classList.remove('hidden');
            }
        }
    }

    mostrarEnlaceDocumento(campo, path) {
        const container = document.getElementById(`${campo}-actual`);
        const link = document.getElementById(`${campo}-link`);

        if (container && link) {
            if (path) {
                container.classList.remove('hidden');
                link.href = `/storage/${path}`;
            } else {
                container.classList.add('hidden');
                link.href = '#';
            }
        }
    }

    limpiarFormulario() {
        super.limpiarFormulario();

        // Ocultar enlaces de documentos
        ['curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo'].forEach(campo => {
            const container = document.getElementById(`${campo}-actual`);
            if (container) container.classList.add('hidden');
        });

        // Resetear RUN input
        const runInput = document.getElementById('runDocente');
        const runHelp = document.getElementById('run-help-text');
        if (runInput) {
            runInput.readOnly = false;
            runInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            if (runHelp) runHelp.classList.add('hidden');
        }
    }
}


// Solo inicializar si estamos en la página de docentes
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('docenteForm')) {
        window.docenteManager = new DocenteManager();
    }
});
