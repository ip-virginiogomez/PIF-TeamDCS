import BaseModalManager from './base-modal-manager.js';

/**
 * Unidad Clínica Manager
 * Extiende BaseModalManager
 */
class UnidadClinicaManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'unidadClinicaModal',
            formId: 'unidadClinicaForm',
            entityName: 'Unidad Clínica',
            entityGender: 'f',
            baseUrl: '/unidad-clinicas',
            primaryKey: 'idUnidadClinica',
            tableContainerId: 'tabla-container',
            fields: [
                'nombreUnidad',
                'idCentroSalud'
            ]
        });

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

        // --- FUNCIÓN PARA EJECUTAR BÚSQUEDA Y ORDENAMIENTO ---
        const executeSearch = (page = 1, newSortBy = null) => {
            const params = new URLSearchParams(window.location.search);

            // Actualizar búsqueda
            if (searchInput.value.trim()) {
                params.set('search', searchInput.value.trim());
            } else {
                params.delete('search');
            }

            // Actualizar página
            if (page > 1) {
                params.set('page', page);
            } else {
                params.delete('page');
            }

            // Actualizar ordenamiento
            if (newSortBy) {
                const currentSortBy = params.get('sort_by') || 'idUnidadClinica';
                const currentSortDir = params.get('sort_direction') || 'desc';

                if (newSortBy === currentSortBy) {
                    params.set('sort_direction', currentSortDir === 'asc' ? 'desc' : 'asc');
                } else {
                    params.set('sort_by', newSortBy);
                    params.set('sort_direction', 'asc');
                }
            }

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
                })
                .catch(error => console.error('Error en búsqueda:', error));
        };

        // Exponer función de ordenamiento globalmente
        window.updateSort = (column) => {
            executeSearch(1, column);
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
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('unidadClinicaForm')) {
        new UnidadClinicaManager();
    }
});
