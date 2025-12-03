import BaseModalManager from './base-modal-manager.js';

class CentroFormadorManager extends BaseModalManager {
    constructor() {
        super({
            modalId: 'centroFormadorModal',
            formId: 'centroFormadorForm',
            entityName: 'Centro Formador',
            entityGender: 'm',
            baseUrl: '/centros-formadores',
            primaryKey: 'idCentroFormador',
            tableContainerId: 'tabla-container',
            fields: [
                'idTipoCentroFormador',
                'nombreCentroFormador',
                'fechaCreacion',
            ]
        });

        this.initCoordinatorModal();
    }

    initCoordinatorModal() {
        const tableContainer = document.getElementById(this.config.tableContainerId);
        const modal = document.getElementById('coordinatorModal');
        const closeBtnX = document.getElementById('closeCoordinatorModalX');
        const closeBtnBottom = document.getElementById('closeCoordinatorModalBtn');
        const backdrop = document.getElementById('coordinatorModalBackdrop');
        
        // Elements to populate
        const photoContainer = document.getElementById('coordinatorPhotoContainer');
        const nameElement = document.getElementById('coordinatorName');
        const detailsContainer = document.getElementById('coordinatorDetails');

        if (!tableContainer || !modal) return;

        // Open Modal
        tableContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-action="view-coordinator"]');
            if (btn) {
                const data = JSON.parse(btn.dataset.coordinator);
                this.showCoordinatorInfo(data, { photoContainer, nameElement, detailsContainer });
                modal.classList.remove('hidden');
            }
        });

        // Close Modal
        const closeModal = () => modal.classList.add('hidden');
        if (closeBtnX) closeBtnX.addEventListener('click', closeModal);
        if (closeBtnBottom) closeBtnBottom.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
    }

    showCoordinatorInfo(data, elements) {
        if (!data) return;
        
        const { photoContainer, nameElement, detailsContainer } = elements;
        const fullName = `${data.nombres || data.nombreUsuario || ''} ${data.apellidoPaterno || ''} ${data.apellidoMaterno || ''}`.trim();

        // 1. Photo
        const photoUrl = data.foto 
            ? `/storage/${data.foto}` 
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=bae6fd&color=0369a1&size=128`;
            
        photoContainer.innerHTML = `<img src="${photoUrl}" class="h-full w-full object-cover">`;

        // 2. Name
        nameElement.textContent = fullName;

        // 3. Details
        detailsContainer.innerHTML = `
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-500 font-medium">RUN</span>
                <span class="text-gray-900 font-semibold">${data.runUsuario || 'N/A'}</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                <span class="text-gray-500 font-medium">Correo</span>
                <a class="text-sky-600 font-semibold truncate ml-4 block text-right">${data.correo || 'N/A'}</a>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-500 font-medium">Teléfono</span>
                <span class="text-gray-900 font-semibold text-right">${data.telefono || 'N/A'}</span>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('centroFormadorForm')) {
        new CentroFormadorManager();
    }
});


