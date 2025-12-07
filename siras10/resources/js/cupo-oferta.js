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
                'cantCupos', 'fechaEntrada', 'fechaSalida'
            ]
        });

        this.initSearch();
        this.initHorarios();
    }

    initHorarios() {
        const btnAdd = document.getElementById('btn-add-horario');
        const container = document.getElementById('horarios-container');

        if (btnAdd && container) {
            btnAdd.addEventListener('click', () => {
                this.addHorarioRow(container);
            });
        }
    }

    async editarRegistro(id) {
        const data = await super.editarRegistro(id);
        if (data) {
            this.populateHorarios(data);
        }
    }

    populateHorarios(data) {
        const container = document.getElementById('horarios-container');
        if (!container) return;

        container.innerHTML = '';

        if (data.horarios && data.horarios.length > 0) {
            const groups = data.horarios.reduce((acc, h) => {
                const key = `${h.horaEntrada}-${h.horaSalida}`;
                if (!acc[key]) {
                    acc[key] = {
                        entrada: h.horaEntrada,
                        salida: h.horaSalida,
                        dias: []
                    };
                }
                acc[key].dias.push(h.diaSemana);
                return acc;
            }, {});

            Object.values(groups).forEach(group => this.addHorarioRow(container, group));
        } else {
            this.addHorarioRow(container);
        }
    }

    validate() {
        const container = document.getElementById('horarios-container');
        if (!container) return true;

        // Remove old hidden inputs
        this.form.querySelectorAll('.hidden-horario-input').forEach(el => el.remove());

        const rows = container.querySelectorAll('.horario-row');
        let hasHorarios = false;

        rows.forEach((row, index) => {
            const dias = Array.from(row.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value);
            const entrada = row.querySelector('.hora-entrada').value;
            const salida = row.querySelector('.hora-salida').value;

            if (dias.length > 0 && entrada && salida) {
                hasHorarios = true;
                dias.forEach((dia, diaIndex) => {
                    this.addHiddenInput(`horarios[${index}][dias][${diaIndex}]`, dia);
                });
                this.addHiddenInput(`horarios[${index}][entrada]`, entrada);
                this.addHiddenInput(`horarios[${index}][salida]`, salida);
            }
        });

        return true;
    }

    addHiddenInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        input.className = 'hidden-horario-input';
        this.form.appendChild(input);
    }

    addHorarioRow(container, data = null) {
        const row = document.createElement('div');
        row.className = 'horario-row bg-gray-50 p-3 rounded border border-gray-200 relative';

        const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        let checkboxesHtml = '<div class="flex flex-wrap gap-2 mb-2">';
        dias.forEach(dia => {
            const checked = data && data.dias.includes(dia) ? 'checked' : '';
            checkboxesHtml += `
                <label class="inline-flex items-center">
                    <input type="checkbox" value="${dia}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" ${checked}>
                    <span class="ml-1 text-sm text-gray-700">${dia.substring(0, 3)}</span>
                </label>
            `;
        });
        checkboxesHtml += '</div>';

        const entradaVal = data ? data.entrada : '';
        const salidaVal = data ? data.salida : '';

        row.innerHTML = `
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Días</span>
                    ${checkboxesHtml}
                </div>
                <button type="button" class="text-red-500 hover:text-red-700 btn-remove-horario ml-2" title="Eliminar horario">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-700">Entrada</label>
                    <input type="time" class="hora-entrada mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" value="${entradaVal}" required>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-700">Salida</label>
                    <input type="time" class="hora-salida mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" value="${salidaVal}" required>
                </div>
            </div>
        `;

        container.appendChild(row);

        row.querySelector('.btn-remove-horario').addEventListener('click', () => {
            row.remove();
        });
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
