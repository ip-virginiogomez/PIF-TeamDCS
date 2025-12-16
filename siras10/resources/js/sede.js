// Importamos la función de validación que necesitamos.
import { validarTelefono } from './validators.js';
import BaseModalManager from './base-modal-manager.js';

/**
 * Sede Manager
 * Extiende BaseModalManager
 */
class SedeManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'sedeModal',
            formId: 'sedeForm',
            entityName: 'Sede',
            entityGender: 'f',
            baseUrl: '/sede',
            primaryKey: 'idSede',
            tableContainerId: 'tabla-container',
            fields: [
                'nombreSede',
                'direccion',
                'idCentroFormador',
                'fechaCreacion',
                'numContacto'
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

        const numContactoInput = this.form.querySelector('[name="numContacto"]');
        if (numContactoInput && !validarTelefono(numContactoInput.value)) {
            esValido = false;
            this.showValidationErrors({
                'numContacto': ['El formato debe ser + seguido de hasta 11 dígitos.']
            });
        }
        return esValido;
    }
}



document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('sedeForm')) {
        new SedeManager();
    }
});