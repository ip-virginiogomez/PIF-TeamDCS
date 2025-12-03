import BaseModalManager from './base-modal-manager.js';
import { validarCorreo, validarRun } from './validators.js';

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
            entityGender: 'm',
            baseUrl: '/alumnos',
            primaryKey: 'runAlumno',
            fields: [
                'runAlumno',
                'nombres',
                'apellidoPaterno',
                'apellidoMaterno',
                'fechaNacto',
                'correo',
            ]
        });

        this.docsElements = {
            modal: document.getElementById('modalDocumentosAlumno'),
            container: document.getElementById('contenido-docs-alumno'),
            titulo: document.getElementById('titulo-modal-docs'),
            backdrop: 'backdrop-docs-alumno'
        };

        this.initFotoPreview();
        this.initDocumentosViewer();
        this.initSearch();
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
                console.error(error);
                // Opcional: Mostrar mensaje de error en tabla
            } finally {
                tablaContainer.style.opacity = '1';
            }
        };

        // --- LISTENERS ---

        // 1. TEXTO (Debounce)
        let timeoutId;
        searchInput.addEventListener('input', (e) => {
            const val = e.target.value;

            // Toggle X button
            if (val.trim().length > 0) {
                if (clearBtn) {
                    clearBtn.classList.remove('hidden');
                    clearBtn.classList.add('flex');
                }
            } else {
                if (clearBtn) {
                    clearBtn.classList.add('hidden');
                    clearBtn.classList.remove('flex');
                }
            }

            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                executeSearch();
            }, 500);
        });

        // 2. FILTRO CENTRO FORMADOR (LÓGICA CASCADA)
        if (filterCentro && filterSedeCarrera) {
            filterCentro.addEventListener('change', async () => {
                const centroId = filterCentro.value;

                // Resetear Sede/Carrera
                filterSedeCarrera.innerHTML = '<option value="">Cargando...</option>';
                filterSedeCarrera.disabled = true;

                try {
                    // Si hay centro seleccionado, filtrar. Si no, traer todas (o vaciar, según prefieras)
                    // Aquí reutilizamos la lógica: si no hay centro, quizás quieras mostrar todas o ninguna.
                    // Asumiremos que si borra centro, reseteamos a "Todas".

                    let url = `${this.config.baseUrl}/sedes-carreras-by-centro`;
                    if (centroId) {
                        url += `?centro_id=${centroId}`;
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    filterSedeCarrera.innerHTML = '<option value="">Todas las Carreras</option>';
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.idSedeCarrera;
                        const nombreSede = item.sede ? item.sede.nombreSede : '';
                        option.textContent = `${item.nombreSedeCarrera} - ${nombreSede}`;
                        filterSedeCarrera.appendChild(option);
                    });

                } catch (error) {
                    console.error('Error cargando sedes/carreras', error);
                    filterSedeCarrera.innerHTML = '<option value="">Error al cargar</option>';
                } finally {
                    filterSedeCarrera.disabled = false;
                    // Ejecutar búsqueda principal al cambiar filtro
                    executeSearch();
                }
            });
        } else if (filterCentro) {
            // Si solo existe filtro centro (raro pero posible)
            filterCentro.addEventListener('change', () => executeSearch());
        }

        // 3. FILTRO SEDE/CARRERA (Simple)
        if (filterSedeCarrera) {
            filterSedeCarrera.addEventListener('change', () => {
                executeSearch();
            });
        }

        // 4. LIMPIAR
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                clearBtn.classList.add('hidden');
                clearBtn.classList.remove('flex');
                executeSearch();
            });
        }

        // 5. PAGINACIÓN
        tablaContainer.addEventListener('click', (e) => {
            const link = e.target.closest('a.page-link'); // Clase de Laravel Pagination
            if (link) {
                e.preventDefault();
                const url = link.href;
                // Actualizar URL navegador
                window.history.pushState({}, '', url);
                fetchTabla(url);
            }
        });

        // Prevenir submit
        const searchForm = document.getElementById('search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => e.preventDefault());
        }
    }

    initDocumentosViewer() {
        if (!this.docsElements.modal) return;

        document.body.addEventListener('click', (e) => {
            const target = e.target;

            // A. ABRIR (Botón Carpeta Azul)
            const btnOpen = target.closest('button[data-action="view-documents"]');
            if (btnOpen) {
                const run = btnOpen.dataset.id;
                const nombre = btnOpen.dataset.nombre || 'Alumno';
                this.abrirModalDocs(run, nombre);
            }

            // B. CERRAR (Botón X, Botón Cerrar o Fondo Oscuro)
            if (target.closest('[data-action="close-modal-docs"]') || target.id === this.docsElements.backdrop) {
                this.cerrarModalDocs();
            }
        });
    }

    async abrirModalDocs(run, nombre) {
        const { modal, container, titulo } = this.docsElements;

        // 1. Mostrar Modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // 2. Setear Título
        if (titulo) titulo.textContent = `Documentos: ${nombre}`;

        // 3. Mostrar Spinner
        this.renderLoadingDocs(container);

        // 4. Fetch al Servidor
        try {
            const response = await fetch(`/alumnos/${run}/documentos`);
            if (!response.ok) throw new Error('Error al obtener documentos');
            const html = await response.text();
            container.innerHTML = html;
        } catch (error) {
            console.error(error);
            this.renderErrorDocs(container);
        }
    }

    cerrarModalDocs() {
        const { modal, container } = this.docsElements;
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (container) container.innerHTML = '';
    }

    renderLoadingDocs(container) {
        container.innerHTML = `
            <div class="w-full h-full flex flex-col items-center justify-center">
                <svg class="w-10 h-10 text-blue-500 animate-spin mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-500 text-sm font-medium">Cargando documentos...</span>
            </div>`;
    }

    renderErrorDocs(container) {
        container.innerHTML = `
            <div class="w-full h-full flex flex-col items-center justify-center text-red-500">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>No se pudo cargar la información.</p>
            </div>`;
    }

    // --- FORMULARIO Y VALIDACIONES ---

    showValidationErrors(errors) {
        this.clearValidationErrors();
        for (const field in errors) {
            const input = this.form.querySelector(`[name="${field}"]`);
            const errorDiv = document.getElementById(`error-${field}`);
            const errorMessage = errors[field][0];

            if (input) input.classList.add('border-red-500');
            if (errorDiv) {
                errorDiv.textContent = errorMessage;
                errorDiv.classList.remove('hidden');
            }
        }
    }

    clearValidationErrors() {
        this.form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
        this.form.querySelectorAll('[id^="error-"]').forEach(errorDiv => {
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
        });
    }

    initFotoPreview() {
        const fotoInput = this.form.querySelector('#foto');
        const fotoPreview = document.getElementById('foto-preview');

        if (!fotoInput || !fotoPreview) return;

        fotoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => { fotoPreview.src = event.target.result; };
            reader.readAsDataURL(file);
        });
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;

        const runInput = this.form.querySelector('[name="runAlumno"]');
        const correoInput = this.form.querySelector('[name="correo"]');

        if (runInput && !validarRun(runInput.value)) {
            esValido = false;
            this.showFieldError(runInput, 'error-runAlumno', 'El RUN no cumple con el formato requerido.');
        }
        if (correoInput && !validarCorreo(correoInput.value)) {
            esValido = false;
            this.showFieldError(correoInput, 'error-correo', 'Correo electrónico inválido.');
        }
        return esValido;
    }

    showFieldError(input, errorId, message) {
        input.classList.add('border-red-500');
        const errorDiv = document.getElementById(errorId);
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }
    }

    limpiarFormulario() {
        super.limpiarFormulario();

        // Reset Inputs Especiales
        const runInput = this.form.querySelector('#runAlumno');
        const runHelpText = document.getElementById('run-help-text');
        const fotoPreview = document.getElementById('foto-preview');
        const acuerdoInfo = document.getElementById('acuerdo-actual');
        const acuerdoLink = document.getElementById('acuerdo-link');

        if (runInput) runInput.removeAttribute('readonly');
        if (runHelpText) runHelpText.classList.add('hidden');
        if (fotoPreview) fotoPreview.src = "/storage/placeholder.png";
        if (acuerdoInfo) acuerdoInfo.classList.add('hidden');
        if (acuerdoLink) {
            acuerdoLink.href = '#';
            acuerdoLink.textContent = '';
        }
    }

    async editarRegistro(id) {
        const data = await super.editarRegistro(id);
        if (!data) return;

        const alumno = data.alumno;
        this.fillForm(alumno);
        this.handleSedeCarreraSelect(data);
    }

    fillForm(alumno) {
        // Campos básicos
        const fields = ['runAlumno', 'nombres', 'apellidoPaterno', 'apellidoMaterno', 'correo'];
        fields.forEach(field => {
            const input = this.form.querySelector(`[name="${field}"]`);
            if (input) input.value = alumno[field] || '';
        });

        // Fecha
        const fechaInput = this.form.querySelector('[name="fechaNacto"]');
        if (fechaInput) fechaInput.value = alumno.fechaNacto ? alumno.fechaNacto.substring(0, 10) : '';

        // RUN Readonly
        const runInput = this.form.querySelector('#runAlumno');
        const runHelpText = document.getElementById('run-help-text');
        if (runInput) runInput.setAttribute('readonly', true);
        if (runHelpText) runHelpText.classList.remove('hidden');

        // Foto
        const fotoPreview = document.getElementById('foto-preview');
        if (fotoPreview) fotoPreview.src = alumno.foto ? `/storage/${alumno.foto}` : "/storage/placeholder.png";

        // Acuerdo
        const acuerdoInfo = document.getElementById('acuerdo-actual');
        const acuerdoLink = document.getElementById('acuerdo-link');
        if (acuerdoInfo && acuerdoLink) {
            if (alumno.acuerdo) {
                acuerdoLink.href = `/storage/${alumno.acuerdo}`;
                acuerdoLink.textContent = 'Ver documento actual';
                acuerdoInfo.classList.remove('hidden');
            } else {
                acuerdoInfo.classList.add('hidden');
            }
        }
    }

    handleSedeCarreraSelect(data) {
        const select = this.form.querySelector('[name="idSedeCarrera"]');
        if (!select || !data.sedesCarrerasDisponibles) return;

        select.innerHTML = '<option value="">Seleccione una opción...</option>';

        data.sedesCarrerasDisponibles.forEach(sede => {
            const option = document.createElement('option');
            option.value = sede.idSedeCarrera;

            const nombreSede = sede.sede?.nombreSede || 'Sede';
            const nombreCarrera = sede.nombreSedeCarrera || sede.carrera?.nombreCarrera || 'Carrera';

            option.textContent = `${nombreCarrera} - ${nombreSede}`;

            if (data.sedeCarreraActual && sede.idSedeCarrera == data.sedeCarreraActual.idSedeCarrera) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('alumnoForm')) {
        new AlumnoManager();
    }
});