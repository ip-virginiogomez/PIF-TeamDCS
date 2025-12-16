import BaseModalManager from './base-modal-manager.js';

class CarreraManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'carreraModal',
            formId: 'carreraForm',
            entityName: 'Carrera',
            entityGender: 'f',
            baseUrl: '/carreras',
            primaryKey: 'idCarrera',
            fields: [
                'nombreCarrera'
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
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('carreraForm')) {
        new CarreraManager();
    }
});