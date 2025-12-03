import BaseModalManager from './base-modal-manager.js';

/**
 * Centro de Salud Manager
 * Extiende BaseModalManager
 */
class CentroSaludManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'centroSaludModal',
            formId: 'centroSaludForm',
            entityName: 'Centro de Salud',
            entityGender: 'm',
            baseUrl: '/centro-salud',
            primaryKey: 'centroId',
            fields: [
                'nombreCentro',
                'direccion',
                'director',
                'correoDirector',
                'idCiudad',
                'idTipoCentroSalud'
            ]
        });

        this.initPersonalModal();
    }

    initPersonalModal() {
        const tableContainer = document.getElementById('tabla-container');
        const modal = document.getElementById('personalModal');
        const closeBtnX = document.getElementById('closePersonalModalX');
        const closeBtnBottom = document.getElementById('closePersonalModalBtn');
        const backdrop = document.getElementById('personalModalBackdrop');
        const listContainer = document.getElementById('personalListContainer');

        if (!tableContainer || !modal) return;

        // Open Modal
        tableContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-action="view-personal"]');
            if (btn) {
                const data = JSON.parse(btn.dataset.personal);
                this.showPersonalInfo(data, listContainer);
                modal.classList.remove('hidden');
            }
        });

        // Close Modal
        const closeModal = () => modal.classList.add('hidden');
        if (closeBtnX) closeBtnX.addEventListener('click', closeModal);
        if (closeBtnBottom) closeBtnBottom.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
    }

    showPersonalInfo(users, container) {
        if (!users || users.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500">No hay personal asignado.</p>';
            return;
        }

        const html = users.map(user => {
            const fullName = `${user.nombreUsuario || ''} ${user.apellidoPaterno || ''} ${user.apellidoMaterno || ''}`.trim();
            const photoUrl = user.foto
                ? `/storage/${user.foto}`
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=bae6fd&color=0369a1&size=64`;

            return `
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 mb-4 flex items-start space-x-4 hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0 mr-2">
                        <img class="h-16 w-16 rounded-full object-cover border border-gray-200" src="${photoUrl}" alt="${fullName}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-bold text-gray-900 truncate">
                            ${fullName}
                        </p>
                        <p class="text-sm text-sky-600 font-medium mb-2">
                            ${user.runUsuario || 'N/A'}
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <span class="truncate">${user.correo || 'Sin correo'}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                <span class="truncate">${user.telefono || 'Sin teléfono'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;
    }

    // ¡Todo el código repetido de 'showValidationErrors', 'clearValidationErrors' y 'validate' se elimina!
    // Usará los métodos del padre.
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('centroSaludForm')) {
        new CentroSaludManager();
    }
});