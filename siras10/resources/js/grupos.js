import BaseModalManager from './base-modal-manager.js';

class GrupoManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'grupoModal',
            formId: 'grupoForm',
            entityName: 'Grupo',
            entityGender: 'm',
            baseUrl: '/grupos',
            primaryKey: 'idGrupo',
            fields: ['nombreGrupo', 'idCupoDistribucion']
        });

        this.initDistribucionSelector();
    }

    initDistribucionSelector() {
        const tablaDistribuciones = document.getElementById('tabla-distribuciones-container');
        const seccionGrupos = document.getElementById('seccion-grupos');
        const tablaGruposContainer = document.getElementById('tabla-grupos-container');
        const tituloDistribucion = document.getElementById('titulo-distribucion-seleccionada');
        const btnNuevoGrupo = document.getElementById('btn-nuevo-grupo');
        const inputHiddenDistribucion = document.getElementById('idCupoDistribucion');

        if (!tablaDistribuciones) return;

        // Evento al hacer click en "Ver Grupos" en la Tabla 1
        tablaDistribuciones.addEventListener('click', async (e) => {
            const btnSelect = e.target.closest('[data-action="select-distribucion"]');
            if (!btnSelect) return;

            const distribucionId = btnSelect.dataset.id;
            const distribucionSummary = btnSelect.dataset.summary;

            // 1. Visual: Marcar fila seleccionada (opcional)
            document.querySelectorAll('.row-distribucion').forEach(r => r.classList.remove('bg-blue-100'));
            btnSelect.closest('tr').classList.add('bg-blue-100');

            // 2. Actualizar UI de la sección de grupos
            seccionGrupos.classList.remove('hidden');
            tituloDistribucion.textContent = `(${distribucionSummary})`;
            
            // 3. Configurar botón "Nuevo Grupo" con el ID correcto
            btnNuevoGrupo.dataset.distribucionId = distribucionId;
            
            // 4. Cargar tabla de grupos vía AJAX
            tablaGruposContainer.innerHTML = '<div class="flex justify-center p-4"><i class="fas fa-spinner fa-spin fa-2x text-blue-500"></i></div>';
            
            try {
                const response = await fetch(`/grupos/por-distribucion/${distribucionId}`);
                const html = await response.text();
                tablaGruposContainer.innerHTML = html;
                
                // Scroll suave hacia la tabla de grupos
                seccionGrupos.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (error) {
                console.error(error);
                tablaGruposContainer.innerHTML = '<p class="text-red-500 text-center">Error al cargar grupos.</p>';
            }
        });

        // Evento para el botón "Nuevo Grupo" (Abre el modal y pre-llena el ID)
        if (btnNuevoGrupo) {
            btnNuevoGrupo.addEventListener('click', () => {
                const distId = btnNuevoGrupo.dataset.distribucionId;
                if (!distId) return; // Seguridad

                this.limpiarFormulario();
                
                // IMPORTANTE: Seteamos el ID de la distribución en el input oculto
                if (inputHiddenDistribucion) {
                    inputHiddenDistribucion.value = distId;
                }

                this.mostrarModal();
            });
        }
    }

    // Sobrescribimos reloadTable para que recargue la tabla de grupos, NO la de distribuciones
    reloadTable() {
        const btnNuevoGrupo = document.getElementById('btn-nuevo-grupo');
        const distId = btnNuevoGrupo ? btnNuevoGrupo.dataset.distribucionId : null;
        const tablaGruposContainer = document.getElementById('tabla-grupos-container');

        if (distId && tablaGruposContainer) {
            // Recargamos solo la tabla pequeña
            fetch(`/grupos/por-distribucion/${distId}`)
                .then(res => res.text())
                .then(html => {
                    tablaGruposContainer.innerHTML = html;
                })
                .catch(err => console.error(err));
        } else {
            // Si no hay selección, quizás recargar la página o nada
            // super.reloadTable(); // Ojo: esto recargaría la tabla de distribuciones
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('grupoForm')) {
        window.grupoManager = new GrupoManager();
    }
});