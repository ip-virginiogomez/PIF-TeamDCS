import BaseModalManager from './base-modal-manager.js';

class CentroFormadorManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'centroFormadorModal',
            formId: 'centroFormadorForm',
            entityName: 'Centro Formador',
            entityGender: 'm',
            baseUrl: '/centros-formadores',
            primaryKey: 'idCentroFormador',
            tableContainerId: 'tabla-container',
            fields: [
                'idTipoCentroFormador',
                'nombreCentroFormador',
                'fechaCreacion',
            ]
        });

        this.initCoordinatorModal();
        this.initConveniosModal();
        this.initSearch();
    }

    initSearch() {
        const searchInput = document.getElementById('search-input');
        const clearBtn = document.getElementById('btn-clear-search');
        const tablaContainer = document.getElementById('tabla-container');

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

            if (page > 1) {
                params.append('page', page);
            }

            // Mantener ordenamiento si existe en URL actual
            const currentUrlParams = new URLSearchParams(window.location.search);
            if (currentUrlParams.has('sort_by')) params.append('sort_by', currentUrlParams.get('sort_by'));
            if (currentUrlParams.has('sort_direction')) params.append('sort_direction', currentUrlParams.get('sort_direction'));

            const url = `${window.location.pathname}?${params.toString()}`;

            // Actualizar URL sin recargar
            window.history.pushState({}, '', url);

            // Fetch AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    tablaContainer.innerHTML = html;
                    // Re-inicializar listeners de paginación y ordenamiento si es necesario
                    // (Depende de cómo BaseModalManager maneje esto, pero generalmente los eventos delegados funcionan)
                })
                .catch(error => console.error('Error en búsqueda:', error));
        };

        // --- EVENT LISTENERS ---
        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);

            // Mostrar/Ocultar botón X
            if (searchInput.value.trim().length > 0) {
                clearBtn.classList.remove('hidden');
                clearBtn.classList.add('flex');
            } else {
                clearBtn.classList.add('hidden');
                clearBtn.classList.remove('flex');
            }

            debounceTimer = setTimeout(() => {
                executeSearch();
            }, 300); // Debounce de 300ms
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                clearBtn.classList.add('hidden');
                clearBtn.classList.remove('flex');
                executeSearch();
                searchInput.focus();
            });
        }

        // Prevenir submit del form
        const form = document.getElementById('search-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                executeSearch();
            });
        }
    }

    initConveniosModal() {
        const tableContainer = document.getElementById(this.config.tableContainerId);
        const modal = document.getElementById('conveniosModal');
        const closeBtnX = document.getElementById('closeConveniosModalX');
        const backdrop = document.getElementById('conveniosModalBackdrop');
        const listContainer = document.getElementById('conveniosListContainer');
        const centroNombre = document.getElementById('conveniosCentroNombre');
        const iframe = document.getElementById('convenioIframe');

        if (!tableContainer || !modal) return;

        // Open Modal
        tableContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-action="view-convenios"]');
            if (btn) {
                const convenios = JSON.parse(btn.dataset.convenios);
                const nombre = btn.dataset.centroNombre;

                if (centroNombre) {
                    centroNombre.textContent = nombre;
                }

                this.showConveniosList(convenios, listContainer, iframe);
                modal.classList.remove('hidden');
            }
        });

        // Close Modal
        const closeModal = () => {
            modal.classList.add('hidden');
            if (iframe) iframe.src = '';
        };

        if (closeBtnX) closeBtnX.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
    }

    showConveniosList(convenios, container, iframe) {
        if (!convenios || convenios.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 text-sm">No hay convenios registrados.</p>';
            return;
        }

        const html = convenios.map((convenio, index) => {
            const fechaInicio = convenio.fechaInicio
                ? new Date(convenio.fechaInicio).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' })
                : 'N/A';
            const fechaFin = convenio.fechaFin
                ? new Date(convenio.fechaFin).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' })
                : 'N/A';

            const estadoBadge = convenio.vigente
                ? '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Vigente</span>'
                : '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Vencido</span>';

            return `
                <button 
                    type="button"
                    data-convenio-url="/storage/${convenio.documento}"
                    class="convenio-item w-full text-left bg-white hover:bg-indigo-50 border-2 border-gray-200 hover:border-indigo-400 rounded-lg p-3 transition-all duration-200 cursor-pointer group"
                    onclick="document.getElementById('convenioIframe').src = this.dataset.convenioUrl; 
                             document.querySelectorAll('.convenio-item').forEach(el => el.classList.remove('border-indigo-600', 'bg-indigo-50'));
                             this.classList.add('border-indigo-600', 'bg-indigo-50');">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-semibold text-gray-900">Convenio #${convenio.id}</span>
                        </div>
                    </div>
                    ${estadoBadge}
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center text-xs text-gray-600">
                            <svg class="w-4 h-4 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Inicio: ${fechaInicio}</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <svg class="w-4 h-4 mr-1.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Fin: ${fechaFin}</span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-200 flex items-center text-xs text-indigo-600 group-hover:text-indigo-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Click para visualizar
                    </div>
                </button>
            `;
        }).join('');

        container.innerHTML = html;

        // Cargar el primer documento automáticamente
        if (convenios.length > 0 && iframe) {
            iframe.src = `/storage/${convenios[0].documento}`;
            // Marcar el primer item como seleccionado
            setTimeout(() => {
                const firstItem = container.querySelector('.convenio-item');
                if (firstItem) {
                    firstItem.classList.add('border-indigo-600', 'bg-indigo-50');
                }
            }, 100);
        }
    }

    initCoordinatorModal() {
        const tableContainer = document.getElementById(this.config.tableContainerId);
        const modal = document.getElementById('coordinatorModal');
        const closeBtnX = document.getElementById('closeCoordinatorModalX');
        const closeBtnBottom = document.getElementById('closeCoordinatorModalBtn');
        const backdrop = document.getElementById('coordinatorModalBackdrop');
        const listContainer = document.getElementById('coordinatorListContainer');

        if (!tableContainer || !modal) return;

        // Open Modal
        tableContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-action="view-coordinator"]');
            if (btn) {
                const data = JSON.parse(btn.dataset.coordinator);
                this.showCoordinatorsList(data, listContainer);
                modal.classList.remove('hidden');
            }
        });

        // Close Modal
        const closeModal = () => modal.classList.add('hidden');
        if (closeBtnX) closeBtnX.addEventListener('click', closeModal);
        if (closeBtnBottom) closeBtnBottom.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
    }

    showCoordinatorsList(coordinators, container) {
        if (!coordinators || coordinators.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500">No hay coordinadores asignados.</p>';
            return;
        }

        // Ensure it's an array (handle single object case if legacy data exists)
        const list = Array.isArray(coordinators) ? coordinators : [coordinators];

        const html = list.map(data => {
            const fullName = `${data.nombres || data.nombreUsuario || ''} ${data.apellidoPaterno || ''} ${data.apellidoMaterno || ''}`.trim();
            const photoUrl = data.foto
                ? `/storage/${data.foto}`
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=bae6fd&color=0369a1&size=64`;

            return `
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 mb-4 flex items-start space-x-4 hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0 mr-2">
                        <img class="h-16 w-16 rounded-full object-cover border border-gray-200" src="${photoUrl}" alt="${fullName}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-bold text-gray-900 truncate">
                            ${fullName}
                        </p>
                        <p class="text-sm text-sky-600 font-medium mb-2">
                            ${data.runUsuario || 'N/A'}
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z" /></svg>
                                <span class="truncate">${data.correo || 'Sin correo'}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                <span class="truncate">${data.telefono || 'Sin teléfono'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('centroFormadorForm')) {
        new CentroFormadorManager();
    }
});


