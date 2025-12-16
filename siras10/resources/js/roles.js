import BaseModalManager from './base-modal-manager.js';

/**
 * Rol Manager
 * Extiende BaseModalManager
 */
class RolManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'rolModal',
            formId: 'rolForm',
            entityName: 'Rol',
            entityGender: 'm',
            baseUrl: '/roles',
            primaryKey: 'id',
            tableContainerId: 'tabla-container',
            fields: ['name']
        });

        this.initFiltros();
    }

    initFiltros() {
        const inputSearch = document.getElementById('search-input');
        const btnClear = document.getElementById('btn-clear-search');
        const container = document.getElementById(this.config.tableContainerId);

        let debounceTimer;
        let currentSort = 'name'; // Default sort
        let currentDirection = 'asc'; // Default direction

        // Leer params iniciales de la URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('sortBy')) currentSort = urlParams.get('sortBy');
        if (urlParams.has('sortDirection')) currentDirection = urlParams.get('sortDirection');
        if (urlParams.has('search') && inputSearch) {
            inputSearch.value = urlParams.get('search');
            if (btnClear) btnClear.classList.remove('hidden');
        }

        const fetchResultados = async (url = null) => {
            let targetUrl;
            if (url) {
                targetUrl = new URL(url);
                // Mantener sort si es paginación
                if (currentSort && !targetUrl.searchParams.has('sortBy')) {
                    targetUrl.searchParams.set('sortBy', currentSort);
                    targetUrl.searchParams.set('sortDirection', currentDirection);
                }
                // Mantener search
                if (inputSearch && inputSearch.value && !targetUrl.searchParams.has('search')) {
                    targetUrl.searchParams.set('search', inputSearch.value);
                }
            } else {
                targetUrl = new URL(window.location.origin + this.config.baseUrl);
                const params = new URLSearchParams();
                if (inputSearch && inputSearch.value) {
                    params.set('search', inputSearch.value);
                }
                if (currentSort) {
                    params.set('sortBy', currentSort);
                    params.set('sortDirection', currentDirection);
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
                // Reset sort to default? Or keep? Usually keep.
                // currentSort = 'name';
                // currentDirection = 'asc';
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

    validate() {
        this.clearValidationErrors();
        let esValido = true;

        const nameInput = this.form.querySelector('[name="name"]');
        if (nameInput && nameInput.value.trim() === '') {
            esValido = false;
            this.showValidationErrors({
                'name': ['El nombre del rol es obligatorio.']
            });
        }

        return esValido;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('rolForm')) {
        window.rolManager = new RolManager();
    }
});
