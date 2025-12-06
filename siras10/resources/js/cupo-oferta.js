import BaseModalManager from './base-modal-manager.js';
/**
 * CupoOferta Manager
 * Extiende BaseModalManager
 */
class CupoOfertaManager extends BaseModalManager {

    constructor() {
        super({
            modalId: 'cupoOfertaModal',
            formId: 'cupoOfertaForm',
            entityName: 'Oferta de Cupo',
            entityGender: 'f',
            baseUrl: '/cupo-ofertas',
            primaryKey: 'idCupoOferta',
            tableContainerId: 'tabla-container',
            fields: [
                'idPeriodo', 'idUnidadClinica', 'idTipoPractica', 'idCarrera',
                'cantCupos', 'fechaEntrada', 'fechaSalida', 'horaEntrada', 'horaSalida'
            ]
        });

        this.initSearch();
    }

    initSearch() {
        const searchInput = document.getElementById('search-input');
        const clearBtn = document.getElementById('btn-clear-search');
        const resetBtn = document.getElementById('btn-reset-filters');
        const tablaContainer = document.getElementById('tabla-container');

        const filterPeriodo = document.getElementById('filter-periodo');
        const filterTipoPractica = document.getElementById('filter-tipo-practica');
        const filterCarrera = document.getElementById('filter-carrera');

        if (!searchInput || !tablaContainer) return;

        // Mostrar X si ya hay texto
        if (clearBtn && searchInput.value.trim().length > 0) {
            clearBtn.classList.remove('hidden');
            clearBtn.classList.add('flex');
        }

        // --- FUNCIÓN PARA EJECUTAR BÚSQUEDA ---
        const executeSearch = (page = 1, newSortBy = null) => {
            const params = new URLSearchParams(window.location.search);

            if (searchInput.value.trim()) {
                params.set('search', searchInput.value.trim());
            } else {
                params.delete('search');
            }

            if (filterPeriodo && filterPeriodo.value) {
                params.set('idPeriodo', filterPeriodo.value);
            } else {
                params.delete('idPeriodo');
            }
            if (filterTipoPractica && filterTipoPractica.value) {
                params.set('idTipoPractica', filterTipoPractica.value);
            } else {
                params.delete('idTipoPractica');
            }
            if (filterCarrera && filterCarrera.value) {
                params.set('idCarrera', filterCarrera.value);
            } else {
                params.delete('idCarrera');
            }

            if (page > 1) {
                params.set('page', page);
            } else {
                params.delete('page');
            }

            // Actualizar ordenamiento
            if (newSortBy) {
                const currentSortBy = params.get('sort_by') || 'idCupoOferta';
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

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                if (clearBtn) {
                    clearBtn.classList.add('hidden');
                    clearBtn.classList.remove('flex');
                }
                if (filterPeriodo) filterPeriodo.value = '';
                if (filterTipoPractica) filterTipoPractica.value = '';
                if (filterCarrera) filterCarrera.value = '';
                executeSearch();
            });
        }

        // Listeners para filtros
        if (filterPeriodo) filterPeriodo.addEventListener('change', () => executeSearch());
        if (filterTipoPractica) filterTipoPractica.addEventListener('change', () => executeSearch());
        if (filterCarrera) filterCarrera.addEventListener('change', () => executeSearch());

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
    if (document.getElementById('cupoOfertaForm')) {
        new CupoOfertaManager();
    }
});
