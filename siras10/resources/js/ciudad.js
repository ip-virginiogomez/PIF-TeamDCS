import BaseModalManager from './base-modal-manager.js';

/**
 * Ciudad Manager
 * Extiende BaseModalManager
 */
class CiudadManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'ciudadModal',
            formId: 'ciudadForm',
            entityName: 'Ciudad',
            entityGender: 'f',
            baseUrl: '/ciudad',
            primaryKey: 'idCiudad',
            tableContainerId: 'tabla-container',
            fields: [
                'nombreCiudad'
            ]
        });

        this.initFiltros();
    }

    initFiltros() {
        const inputSearch = document.getElementById('search-input');
        const btnClear = document.getElementById('btn-clear-search');
        const container = document.getElementById(this.config.tableContainerId);

        let debounceTimer;
        let currentSort = 'nombreCiudad';
        let currentDirection = 'asc';

        // Leer params iniciales de la URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('sort_by')) currentSort = urlParams.get('sort_by');
        if (urlParams.has('sort_direction')) currentDirection = urlParams.get('sort_direction');
        if (urlParams.has('search') && inputSearch) {
            inputSearch.value = urlParams.get('search');
        }

        const fetchResultados = async (url = null) => {
            try {
                const searchValue = inputSearch ? inputSearch.value : '';
                const params = new URLSearchParams({
                    search: searchValue,
                    sort_by: currentSort,
                    sort_direction: currentDirection
                });

                const targetUrl = url || `${this.config.baseUrl}?${params.toString()}`;

                const response = await fetch(targetUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                });

                if (!response.ok) throw new Error('Error en la búsqueda');

                const html = await response.text();
                if (container) {
                    container.innerHTML = html;
                }

                // Actualizar URL sin recargar
                const newUrl = new URL(window.location);
                newUrl.searchParams.set('search', searchValue);
                newUrl.searchParams.set('sort_by', currentSort);
                newUrl.searchParams.set('sort_direction', currentDirection);
                window.history.pushState({}, '', newUrl);

            } catch (error) {
                console.error('Error al buscar:', error);
            }
        };

        // Búsqueda en tiempo real
        if (inputSearch) {
            inputSearch.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                const value = e.target.value;

                if (btnClear) {
                    btnClear.classList.toggle('hidden', value === '');
                }

                debounceTimer = setTimeout(() => {
                    fetchResultados();
                }, 300);
            });
        }

        // Botón limpiar búsqueda
        if (btnClear) {
            btnClear.addEventListener('click', () => {
                if (inputSearch) {
                    inputSearch.value = '';
                    btnClear.classList.add('hidden');
                    fetchResultados();
                }
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

    async editarRegistro(id) {
        this.editando = true;
        this.clearValidationErrors();

        try {
            const response = await fetch(`${this.config.baseUrl}/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) throw new Error('Error al cargar la ciudad');

            const data = await response.json();
            const ciudad = data.ciudad;

            this.config.fields.forEach(field => {
                const element = this.form.querySelector(`[name="${field}"]`);
                if (element && ciudad[field] !== undefined) {
                    element.value = ciudad[field] || '';
                }
            });

            this.registroIdActual = id;
            this.mostrarModal();
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar los datos de la ciudad');
        }
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;

        // Validar nombre de la ciudad
        const nombreCiudadInput = this.form.querySelector('[name="nombreCiudad"]');
        if (nombreCiudadInput && nombreCiudadInput.value.trim() === '') {
            esValido = false;
            this.showValidationErrors({
                'nombreCiudad': ['El nombre de la ciudad es obligatorio.']
            });
        }

        return esValido;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('ciudadForm')) {
        new CiudadManager();
    }
});

export default CiudadManager;
