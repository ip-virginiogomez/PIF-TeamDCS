import BaseModalManager from './base-modal-manager.js';
/**
 * Periodo Manager
 * Extiende BaseModalManager
 */
class PeriodoManager extends BaseModalManager {

    constructor() {
        super({
            modalId: 'periodoModal',
            formId: 'periodoForm',
            entityName: 'Período',
            entityGender: 'm',
            baseUrl: '/periodos',
            primaryKey: 'idPeriodo',
            tableContainerId: 'tabla-container',
            fields: [
                'Año',
                'fechaInicio',
                'fechaFin'
            ]
        });

        // Agregar validación en tiempo real para el campo Año
        this.initAñoValidation();
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
                const currentSortBy = params.get('sort_by') || 'idPeriodo';
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

    /**
     * Inicializa la validación del campo Año
     */
    initAñoValidation() {
        const añoInput = this.form.querySelector('[name="Año"]');

        if (añoInput) {
            // Limitar a 4 dígitos mientras se escribe
            añoInput.addEventListener('input', (e) => {
                let valor = e.target.value;

                // Solo permitir números
                valor = valor.replace(/[^0-9]/g, '');

                // Limitar a 4 dígitos
                if (valor.length > 4) {
                    valor = valor.slice(0, 4);
                }

                e.target.value = valor;
            });

            // Establecer atributos HTML5 para validación adicional
            añoInput.setAttribute('min', '2000');
            añoInput.setAttribute('max', '2099');
            añoInput.setAttribute('maxlength', '4');
        }
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;
        const errores = {};

        // Obtener valores
        const año = this.form.querySelector('[name="Año"]')?.value;
        const fechaInicio = this.form.querySelector('[name="fechaInicio"]')?.value;
        const fechaFin = this.form.querySelector('[name="fechaFin"]')?.value;

        // 1. Validar que el año tenga exactamente 4 dígitos
        if (!año || año.length !== 4) {
            esValido = false;
            errores['Año'] = ['El año debe tener exactamente 4 dígitos.'];
        } else {
            const añoNum = parseInt(año);

            // Validar rango razonable
            if (añoNum < 2000 || añoNum > 2099) {
                esValido = false;
                errores['Año'] = ['El año debe estar entre 2000 y 2099.'];
            }

            // 2. Validar que fechaInicio corresponda al año ingresado
            if (fechaInicio) {
                const añoFechaInicio = parseInt(fechaInicio.split('-')[0]);

                if (añoFechaInicio !== añoNum) {
                    esValido = false;
                    errores['fechaInicio'] = [`La fecha de inicio debe corresponder al año ${año}.`];
                }
            }

            // 3. Validar que fechaFin corresponda al año ingresado
            if (fechaFin) {
                const añoFechaFin = parseInt(fechaFin.split('-')[0]);

                if (añoFechaFin !== añoNum) {
                    esValido = false;
                    errores['fechaFin'] = [`La fecha de fin debe corresponder al año ${año}.`];
                }
            }
        }

        // 4. Validar que fechaFin sea posterior a fechaInicio
        if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
            esValido = false;
            errores['fechaFin'] = errores['fechaFin'] || [];
            errores['fechaFin'].push('La fecha de fin debe ser posterior a la fecha de inicio.');
        }

        // Mostrar todos los errores si existen
        if (!esValido) {
            this.showValidationErrors(errores);
        }

        return esValido;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('periodoForm')) {
        new PeriodoManager();
    }
});
