import BaseModalManager from './base-modal-manager.js';

class GrupoManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'grupoModal',
            formId: 'grupoForm',
            entityName: 'Grupo',
            entityGender: 'm',
            baseUrl: '/grupos',
            primaryKey: 'idGrupo',
            fields: [
                'nombreGrupo', 
                'idCupoDistribucion', 
                'idAsignatura', 
                'idDocenteCarrera',
                'fechaInicio', // ✅ Correcto
                'fechaFin'     // ✅ Correcto
            ]
        });

        this.currentEditId = null;

        // Inicializamos todos los módulos
        this.initDistribucionSelector();
        this.initTablaGruposEvents();
        this.initPreviewEvents(); // ✅ Preview de archivos
        this.initFiltrosAjax();   // ✅ Buscador y Filtros
    }

    // =========================================================
    // 1. LÓGICA DE PREVISUALIZACIÓN (IFRAME)
    // =========================================================
    initPreviewEvents() {
        const container = document.getElementById('tabla-grupos-container');
        if (!container) return;

        const modal = document.getElementById('modalPreviewDossier');
        const iframe = document.getElementById('iframe-preview');
        const title = document.getElementById('preview-title');
        const errorDiv = document.getElementById('preview-error');
        const fallbackLink = document.getElementById('btn-fallback-download');
        
        const btnClose = document.getElementById('btn-close-preview');
        const backdrop = document.getElementById('backdropPreview');

        const closePreview = () => {
            if (modal) modal.classList.add('hidden');
            if (iframe) iframe.src = ''; 
            document.body.classList.remove('overflow-hidden');
        };

        if (btnClose) btnClose.addEventListener('click', closePreview);
        if (backdrop) backdrop.addEventListener('click', closePreview);

        container.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-action="preview-file"]');
            if (!btn) return;

            const url = btn.dataset.url;
            const name = btn.dataset.title;
            const extension = btn.dataset.type.toLowerCase();

            if (modal && iframe && title) {
                title.textContent = name;
                
                if (['pdf', 'jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                    iframe.src = url;
                    iframe.classList.remove('hidden');
                    if (errorDiv) errorDiv.classList.add('hidden');
                } else {
                    iframe.classList.add('hidden');
                    if (errorDiv) errorDiv.classList.remove('hidden');
                    if (fallbackLink) fallbackLink.href = url;
                }

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        });
    }

    // =========================================================
    // 2. LÓGICA DE FILTROS AJAX (MOVIDA DENTRO DE LA CLASE)
    // =========================================================
    initFiltrosAjax() {
        const form = document.getElementById('form-filtros');
        const container = document.getElementById('tabla-distribuciones-container');
        const inputSearch = document.getElementById('input-search');
        const selectPeriodo = document.getElementById('select-periodo');
        const btnLimpiar = document.getElementById('btn-limpiar'); // <--- Nuevo selector

        if (!form || !container) return;

        let debounceTimer;

        // Función principal para cargar datos
        const fetchResultados = async (url = null) => {
            const targetUrl = url || new URL(form.action);
            
            // Si no es una URL directa (paginación), construimos los parámetros del form
            if (!url) {
                const params = new URLSearchParams(new FormData(form)).toString();
                targetUrl.search = params;
            }

            // Efecto visual
            container.classList.add('opacity-50', 'pointer-events-none');

            try {
                const response = await fetch(targetUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!response.ok) throw new Error('Error en la red');

                const html = await response.text();
                container.innerHTML = html;
                
                // Actualizar URL del navegador
                window.history.pushState({}, '', targetUrl);

                // Lógica visual del botón limpiar (Opcional: ocultar si está limpio)
                this.toggleBtnLimpiar(inputSearch, selectPeriodo, btnLimpiar);

            } catch (error) {
                console.error('Error filtrando:', error);
            } finally {
                container.classList.remove('opacity-50', 'pointer-events-none');
            }
        };

        // EVENTO 1: Escribir
        if (inputSearch) {
            inputSearch.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchResultados(), 300);
            });
        }

        // EVENTO 2: Select
        if (selectPeriodo) {
            selectPeriodo.addEventListener('change', () => fetchResultados());
        }

        // EVENTO 3: Submit (Enter)
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            fetchResultados();
        });

        // EVENTO 4: Botón Limpiar (NUEVO)
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', () => {
                // 1. Resetear valores
                if (inputSearch) inputSearch.value = '';
                if (selectPeriodo) selectPeriodo.value = '';

                // 2. Disparar búsqueda "vacía" para traer todo de nuevo
                fetchResultados();
            });
        }

        // EVENTO 5: Paginación
        container.addEventListener('click', (e) => {
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                fetchResultados(link.href);
            }
        });

        // Inicializar estado del botón al cargar
        this.toggleBtnLimpiar(inputSearch, selectPeriodo, btnLimpiar);
    }

    // Auxiliar para mostrar/ocultar el botón limpiar
    toggleBtnLimpiar(input, select, btn) {
        if (!btn) return;
        const hasFilter = (input && input.value.trim() !== '') || (select && select.value !== '');
        
        // Si quieres que se oculte cuando no hay filtros, descomenta esto:
        // if (hasFilter) btn.classList.remove('hidden');
        // else btn.classList.add('hidden');
        
        // O si prefieres solo deshabilitarlo visualmente:
        btn.disabled = !hasFilter;
        btn.classList.toggle('opacity-50', !hasFilter);
        btn.classList.toggle('cursor-not-allowed', !hasFilter);
    }

    // =========================================================
    // 3. MANEJO DEL FORMULARIO (FILES & DATES)
    // =========================================================
    async handleFormSubmit(e) {
        e.preventDefault();

        const isEdit = !!this.currentEditId;
        const submitUrl = isEdit 
            ? `${this.config.baseUrl}/${this.currentEditId}` 
            : this.config.baseUrl;

        const formData = new FormData(this.form);

        if (isEdit) {
            formData.set('_method', 'PUT');
        } else {
            formData.delete('_method');
        }

        try {
            this.limpiarErroresVisuales();

            const response = await fetch(submitUrl, {
                method: 'POST', 
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (!response.ok) {
                if (response.status === 422) {
                    if (typeof this.mostrarErrores === 'function') {
                        this.mostrarErrores(data.errors);
                    } else {
                        alert('Error de validación.');
                    }
                } else {
                    throw new Error(data.message || 'Error en el servidor');
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire('Éxito', 'Operación realizada correctamente', 'success');
            } else {
                alert('Guardado correctamente');
            }

            this.cerrarModal();
            this.refreshTable();

        } catch (error) {
            console.error(error);
            alert('Ocurrió un error inesperado al guardar.');
        }
    }

    // ... (El resto de tus métodos: refreshTable, initDistribucionSelector, etc. se mantienen igual) ...
    
    refreshTable() { this.reloadTable(); }

    initDistribucionSelector() {
        const tablaDistribuciones = document.getElementById('tabla-distribuciones-container');
        const seccionGrupos = document.getElementById('seccion-grupos');
        const tituloDistribucion = document.getElementById('titulo-distribucion-seleccionada');
        const btnNuevoGrupo = document.getElementById('btn-nuevo-grupo');
        const inputHiddenDistribucion = document.getElementById('idCupoDistribucion');

        if (!tablaDistribuciones) return;

        tablaDistribuciones.addEventListener('click', (e) => {
            const btnSelect = e.target.closest('[data-action="select-distribucion"]');
            if (!btnSelect) return;

            const distribucionId = btnSelect.dataset.id;
            const distribucionSummary = btnSelect.dataset.summary;

            document.querySelectorAll('.row-distribucion').forEach(r => r.classList.remove('bg-green-100'));
            btnSelect.closest('tr').classList.add('bg-green-100');

            if (seccionGrupos) seccionGrupos.classList.remove('hidden');
            if (tituloDistribucion) tituloDistribucion.textContent = `(${distribucionSummary})`;
            
            if (btnNuevoGrupo) btnNuevoGrupo.dataset.distribucionId = distribucionId;
            
            this.cargarTablaGrupos(distribucionId);
        });

        if (btnNuevoGrupo) {
            btnNuevoGrupo.addEventListener('click', () => {
                const distId = btnNuevoGrupo.dataset.distribucionId;
                if (!distId) {
                    alert('Seleccione una distribución primero.');
                    return;
                }
                this.resetFormulario();
                if (inputHiddenDistribucion) inputHiddenDistribucion.value = distId;
                this.mostrarModal();
            });
        }
    }

    initTablaGruposEvents() {
        const container = document.getElementById('tabla-grupos-container');
        if (!container) return;

        container.addEventListener('click', (e) => {
            const btnEdit = e.target.closest('button[data-action="edit"]');
            if (btnEdit) {
                this.cargarDatosGrupo(btnEdit.dataset.id);
                return;
            }
            const btnDelete = e.target.closest('button[data-action="delete"]');
            if (btnDelete) {
                this.eliminarGrupo(btnDelete.dataset.id);
                return;
            }
        });
    }

    async cargarTablaGrupos(distId) {
        const container = document.getElementById('tabla-grupos-container');
        const seccion = document.getElementById('seccion-grupos');
        if (!container) return;

        container.innerHTML = '<div class="flex justify-center p-4"><i class="fas fa-spinner fa-spin fa-2x text-green-600"></i></div>';
        
        try {
            const response = await fetch(`/grupos/por-distribucion/${distId}`);
            if (!response.ok) throw new Error('Error HTTP');
            const html = await response.text();
            container.innerHTML = html;
            if (seccion) seccion.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
            container.innerHTML = '<p class="text-red-500 text-center">Error al cargar los grupos.</p>';
        }
    }

    reloadTable() {
        const btnNuevoGrupo = document.getElementById('btn-nuevo-grupo');
        const distId = btnNuevoGrupo ? btnNuevoGrupo.dataset.distribucionId : null;
        if (distId) this.cargarTablaGrupos(distId);
    }

    async cargarDatosGrupo(id) {
        try {
            this.currentEditId = id;
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`);
            if (!response.ok) throw new Error('Error obteniendo datos');
            const data = await response.json();

            this.config.fields.forEach(field => {
                const input = document.getElementById(field);
                if (input && data[field] !== undefined && data[field] !== null) {
                    if (input.type === 'date') {
                        input.value = data[field].toString().substring(0, 10);
                    } else {
                        input.value = data[field];
                    }
                }
            });

            const title = document.getElementById(`${this.config.modalId}-title`);
            if (title) title.textContent = `Editar ${this.config.entityName}`;
            this.mostrarModal();
        } catch (error) {
            console.error(error);
            alert('Error al cargar la información.');
        }
    }

    async eliminarGrupo(id) {
        const confirmacion = typeof Swal !== 'undefined' 
            ? await Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar'
            })
            : { isConfirmed: confirm('¿Eliminar este grupo?') };

        if (confirmacion.isConfirmed) {
            try {
                const response = await fetch(`${this.config.baseUrl}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();
                if (res.success) {
                    if (typeof Swal !== 'undefined') Swal.fire('Eliminado', res.message, 'success');
                    this.refreshTable();
                } else {
                    alert('Error al eliminar');
                }
            } catch (error) {
                alert('Ocurrió un error al intentar eliminar.');
            }
        }
    }

    resetFormulario() {
        this.currentEditId = null;
        if (this.form) {
            this.form.reset();
            const methodInputs = this.form.querySelectorAll('input[name="_method"]');
            methodInputs.forEach(input => input.remove());
        }
        const title = document.getElementById(`${this.config.modalId}-title`);
        if (title) title.textContent = `Nuevo ${this.config.entityName}`;
        this.limpiarErroresVisuales();
    }

    limpiarErroresVisuales() {
        if (this.form) {
            this.form.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
            this.form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
        }
    }

    mostrarModal() {
        const modal = document.getElementById(this.config.modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    cerrarModal() {
        const modal = document.getElementById(this.config.modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.body.classList.remove('overflow-hidden');
        this.resetFormulario();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('grupoForm')) {
        window.grupoManager = new GrupoManager();
    }
});