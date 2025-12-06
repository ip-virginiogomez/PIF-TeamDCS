import BaseModalManager from './base-modal-manager.js';
/**
 * TipoPractica Manager
 * Extiende BaseModalManager
 */
class TipoPracticaManager extends BaseModalManager {

    constructor() {
        super({
            modalId: 'tipoPracticaModal',
            formId: 'tipoPracticaForm',
            entityName: 'Tipo de Práctica',
            entityGender: 'm',
            baseUrl: '/tipos-practica',
            primaryKey: 'idTipoPractica',
            tableContainerId: 'tabla-container',
            fields: [
                'nombrePractica'
            ]
        });

        this.initFiltros();
    }

    initFiltros() {
        const inputSearch = document.getElementById('search-input');
        const btnClear = document.getElementById('btn-clear-search');
        const container = document.getElementById(this.config.tableContainerId);

        let debounceTimer;
        let currentSort = '';
        let currentDirection = 'asc';

        const fetchResultados = async (url = null) => {
            let targetUrl;
            if (url) {
                targetUrl = new URL(url);
                // Mantener sort si es paginación
                if (currentSort && !targetUrl.searchParams.has('sort')) {
                    targetUrl.searchParams.set('sort', currentSort);
                    targetUrl.searchParams.set('direction', currentDirection);
                }
            } else {
                targetUrl = new URL(window.location.origin + this.config.baseUrl);
                const params = new URLSearchParams();
                if (inputSearch && inputSearch.value) {
                    params.set('search', inputSearch.value);
                }
                if (currentSort) {
                    params.set('sort', currentSort);
                    params.set('direction', currentDirection);
                }
                targetUrl.search = params.toString();
            }

            if (container) container.classList.add('opacity-50', 'pointer-events-none');

            try {
                const response = await fetch(targetUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Error en la red');
                const html = await response.text();
                if (container) container.innerHTML = html;

                window.history.pushState({}, '', targetUrl);

                // Toggle clear button
                if (btnClear && inputSearch) {
                    if (inputSearch.value) btnClear.classList.remove('hidden');
                    else btnClear.classList.add('hidden');
                }

            } catch (error) {
                console.error('Error filtrando:', error);
            } finally {
                if (container) container.classList.remove('opacity-50', 'pointer-events-none');
            }
        };

        // Exponer globalmente para el onclick del HTML
        window.toggleSort = (column) => {
            if (currentSort === column) {
                currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = column;
                currentDirection = 'asc';
            }
            fetchResultados();
        };

        if (inputSearch) {
            inputSearch.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchResultados(), 300);
            });
        }

        if (btnClear) {
            btnClear.addEventListener('click', () => {
                if (inputSearch) inputSearch.value = '';
                currentSort = '';
                currentDirection = 'asc';
                fetchResultados();
            });
        }

        // Paginación AJAX
        if (container) {
            container.addEventListener('click', (e) => {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    fetchResultados(link.href);
                }
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tipoPracticaForm')) {
        new TipoPracticaManager();
    }
});
