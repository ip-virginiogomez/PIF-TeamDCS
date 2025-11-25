document.addEventListener('DOMContentLoaded', () => {

    initDocenteModal();
    initDocumentosModal();
});

function initDocenteModal() {
    const modal = document.getElementById('modalDocente');
    if (!modal) return;

    const btnOpen = document.getElementById('btn-open-docente');
    const backdrop = document.getElementById('modal-backdrop');
    const btnCloseX = document.getElementById('btn-close-x');
    const btnCloseBottom = document.getElementById('btn-close-bottom');

    const openModal = (e) => {
        if(e) e.preventDefault();
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = (e) => {
        if(e) e.preventDefault();
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    if (btnOpen) btnOpen.addEventListener('click', openModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
    if (btnCloseX) btnCloseX.addEventListener('click', closeModal);
    if (btnCloseBottom) btnCloseBottom.addEventListener('click', closeModal);
}

function initDocumentosModal() {
    const modalDocs = document.getElementById('modalDocs');
    const modalFicha = document.getElementById('modalDocente');
    
    if (!modalDocs) return;

    const btnOpen = document.getElementById('btn-open-docs');
    const btnInternal = document.getElementById('btn-view-docs-internal');
    
    const btnClose = document.getElementById('btn-close-docs');
    const btnBack = document.getElementById('btn-back-docs'); // <--- NUEVO ELEMENTO
    const backdrop = document.getElementById('modal-docs-backdrop');
    
    const listaPanel = document.getElementById('lista-docs-panel');
    const iframe = document.getElementById('doc-iframe');
    const emptyState = document.getElementById('empty-state');

    // ... (La función openDocs se mantiene igual) ...
    const openDocs = (e) => {
        if(e) e.preventDefault();
        if(modalFicha && !modalFicha.classList.contains('hidden')) {
            modalFicha.classList.add('hidden');
        }
        modalDocs.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    if (btnOpen) btnOpen.addEventListener('click', openDocs);
    if (btnInternal) btnInternal.addEventListener('click', openDocs);

    // ... (La función closeModal se mantiene igual) ...
    const closeModal = () => {
        modalDocs.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        if(iframe) {
            iframe.src = '';
            iframe.classList.add('hidden');
            if(emptyState) emptyState.classList.remove('hidden');
        }
    };

    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
    
    // --- NUEVO: EVENTO PARA EL BOTÓN VOLVER ---
    if (btnBack) btnBack.addEventListener('click', closeModal); 
    // ------------------------------------------

    // ... (La lógica de listaPanel se mantiene igual) ...
    if (listaPanel) {
        listaPanel.addEventListener('click', (e) => {
            const btnPreview = e.target.closest('button[data-action="preview-doc"]');
            if (btnPreview) {
                const url = btnPreview.dataset.url;
                if(iframe && emptyState) {
                    iframe.src = url;
                    iframe.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                }
            }
        });
    }
}