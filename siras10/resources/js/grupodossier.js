document.addEventListener('DOMContentLoaded', () => {
    // Inicializamos módulos con manejo de errores
    try { initDocenteModal(); } catch (e) { console.error('Error initDocenteModal:', e); }
    try { initDocumentosModal(); } catch (e) { console.error('Error initDocumentosModal:', e); }
    try { initAddAlumnoModal(); } catch (e) { console.error('Error initAddAlumnoModal:', e); }
    try { initRemoveAlumno(); } catch (e) { console.error('Error initRemoveAlumno:', e); }
    try { initAlumnoDocsModal(); } catch (e) { console.error('Error initAlumnoDocsModal:', e); }
    try { initAsignaturaModal(); } catch (e) { console.error('Error initAsignaturaModal:', e); }
});

/**
 * 1. FICHA DOCENTE
 */
function initDocenteModal() {
    const modal = document.getElementById('modalDocente');
    if (!modal) return;

    const btnOpen = document.getElementById('btn-open-docente');
    const backdrop = document.getElementById('modal-backdrop');
    const btnCloseX = document.getElementById('btn-close-x');
    const btnCloseBottom = document.getElementById('btn-close-bottom');

    const toggle = (show) => {
        if (show) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    };

    if (btnOpen) btnOpen.addEventListener('click', (e) => { e.preventDefault(); toggle(true); });
    if (backdrop) backdrop.addEventListener('click', () => toggle(false));
    if (btnCloseX) btnCloseX.addEventListener('click', () => toggle(false));
    if (btnCloseBottom) btnCloseBottom.addEventListener('click', () => toggle(false));
}

/**
 * 2. DOCUMENTOS DOCENTE (Igualado al de Alumnos)
 */
function initDocumentosModal() {
    const modalDocs = document.getElementById('modalDocs');
    const modalFicha = document.getElementById('modalDocente');
    
    if (!modalDocs) {
        console.warn('⚠️ Modal de documentos docente (modalDocs) no encontrado en el HTML.');
        return;
    }

    const btnOpen = document.getElementById('btn-open-docs');
    const btnInternal = document.getElementById('btn-view-docs-internal');
    
    const btnClose = document.getElementById('btn-close-docs');
    const btnBack = document.getElementById('btn-back-docs');
    const backdrop = document.getElementById('modal-docs-backdrop');
    
    const listaPanel = document.getElementById('lista-docs-panel');
    const iframe = document.getElementById('doc-iframe');
    const emptyState = document.getElementById('empty-state');

    // Función Abrir
    const openDocs = (e) => {
        if(e) e.preventDefault();
        // Cerrar ficha si está abierta
        if(modalFicha && !modalFicha.classList.contains('hidden')) {
            modalFicha.classList.add('hidden');
        }
        modalDocs.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    if (btnOpen) btnOpen.addEventListener('click', openDocs);
    if (btnInternal) btnInternal.addEventListener('click', openDocs);

    // Función Cerrar
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
    if (btnBack) btnBack.addEventListener('click', closeModal);

    // LÓGICA DE FILAS CLICKEABLES (Igual que alumnos)
    if (listaPanel) {
        // Buscamos las filas generadas por Blade (que suelen ser div.flex...)
        // Vamos a asumir que tu partial _documentos_lista genera divs con botones dentro.
        
        // 1. Preparamos las filas: Ocultamos el botón "ojo" original y preparamos el click
        const filas = listaPanel.querySelectorAll('div.flex.items-center.justify-between');
        
        filas.forEach(fila => {
            // Estilos para que parezca botón
            fila.classList.add('cursor-pointer', 'hover:bg-sky-50', 'transition', 'p-2', 'rounded-lg');
            
            // Buscar botón de previsualización (ojo) y ocultarlo (ya no se necesita, el clic es en la fila)
            const btnPreview = fila.querySelector('button[data-action="preview-doc"]');
            if (btnPreview) {
                fila.dataset.previewUrl = btnPreview.dataset.url; // Guardamos la URL en la fila
                btnPreview.style.display = 'none'; // Ocultamos el ojo
            }

            // Buscar botón de descarga y evitar que el clic se propague a la fila
            const linkDownload = fila.querySelector('a[download]');
            if (linkDownload) {
                linkDownload.addEventListener('click', (e) => e.stopPropagation());
            }
        });

        // 2. Evento Click en la lista (Delegación)
        listaPanel.addEventListener('click', (e) => {
            // Buscamos la fila más cercana al clic
            const fila = e.target.closest('div[data-preview-url]');
            
            if (fila) {
                const url = fila.dataset.previewUrl;
                
                // Resaltar activo
                filas.forEach(f => f.classList.remove('bg-sky-100', 'border-sky-300', 'ring-1', 'ring-sky-300'));
                fila.classList.add('bg-sky-100', 'border-sky-300', 'ring-1', 'ring-sky-300');

                // Cargar Iframe
                if(iframe && emptyState) {
                    iframe.src = url;
                    iframe.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                }
            }
        });
    }
}

/**
 * 3. AGREGAR ALUMNO
 */
function initAddAlumnoModal() {
    const modal = document.getElementById('modalAddAlumno');
    if (!modal) return; // Si el botón está deshabilitado (grupo lleno), el modal existe pero el btn no abre nada

    const btnOpen = document.getElementById('btn-open-add-alumno');
    const btnClose = document.getElementById('btn-close-add');
    const backdrop = document.getElementById('modal-add-backdrop');
    const inputSearch = document.getElementById('search-alumno');
    const resultsContainer = document.getElementById('search-results');
    const noResults = document.getElementById('no-results');
    const grupoIdInput = document.getElementById('grupo-id-actual');

    if(!grupoIdInput) return;
    const grupoId = grupoIdInput.value;
    let debounceTimer;

    const toggle = (show) => {
        if(show) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            if(inputSearch) inputSearch.focus();
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if(inputSearch) inputSearch.value = '';
            if(resultsContainer) { resultsContainer.innerHTML = ''; resultsContainer.classList.add('hidden'); }
            if(noResults) noResults.classList.add('hidden');
        }
    };

    if(btnOpen) btnOpen.addEventListener('click', () => toggle(true));
    if(btnClose) btnClose.addEventListener('click', () => toggle(false));
    if(backdrop) backdrop.addEventListener('click', () => toggle(false));

    if(inputSearch) {
        inputSearch.addEventListener('input', (e) => {
            const term = e.target.value.trim();
            clearTimeout(debounceTimer);
            
            if (term.length < 2) {
                resultsContainer.classList.add('hidden');
                noResults.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/dossier/${grupoId}/buscar-alumnos?query=${term}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        if (data.length > 0) {
                            resultsContainer.classList.remove('hidden');
                            noResults.classList.add('hidden');
                            data.forEach(alumno => {
                                const item = document.createElement('div');
                                item.className = 'p-3 hover:bg-sky-50 cursor-pointer border-b border-gray-100 flex justify-between items-center transition';
                                item.innerHTML = `
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">${alumno.nombres} ${alumno.apellidoPaterno}</p>
                                        <p class="text-xs text-gray-500">${alumno.runAlumno}</p>
                                    </div>
                                    <button class="text-green-600 hover:text-green-800"><i class="fas fa-plus-circle text-xl"></i></button>
                                `;
                                item.addEventListener('click', () => agregarAlumno(alumno.runAlumno, grupoId));
                                resultsContainer.appendChild(item);
                            });
                        } else {
                            resultsContainer.classList.add('hidden');
                            noResults.classList.remove('hidden');
                        }
                    })
                    .catch(console.error);
            }, 300);
        });
    }

    const agregarAlumno = async (runAlumno, grupoId) => {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(`/dossier/${grupoId}/agregar-alumno`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ runAlumno: runAlumno })
            });
            const res = await response.json();
            if (res.success) window.location.reload();
            else alert('Error: ' + res.message);
        } catch (error) {
            console.error(error);
            alert('Error de conexión');
        }
    };
}

/**
 * 4. ELIMINAR ALUMNO
 */
function initRemoveAlumno() {
    const grupoIdInput = document.getElementById('grupo-id-actual');
    if (!grupoIdInput) return;
    const grupoId = grupoIdInput.value;

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('button[data-action="delete-alumno"]');
        if (btn) {
            btn.disabled = true;
            const runAlumno = btn.dataset.run;
            
            let confirmado = false;
            if (typeof Swal !== 'undefined') {
                const result = await Swal.fire({
                    title: '¿Quitar estudiante?',
                    text: "Se eliminará de este grupo.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, quitar'
                });
                confirmado = result.isConfirmed;
            } else {
                confirmado = confirm('¿Quitar estudiante?');
            }

            if (!confirmado) { btn.disabled = false; return; }

            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch(`/dossier/${grupoId}/eliminar-alumno/${runAlumno}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }
                });
                const data = await response.json();
                if (data.success) {
                    if (typeof Swal !== 'undefined') await Swal.fire('Eliminado', '', 'success');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                }
            } catch (error) {
                console.error(error);
                btn.disabled = false;
            }
        }
    });
}

/**
 * 5. DOCUMENTOS ALUMNO (Dinámico)
 */
function initAlumnoDocsModal() {
    const modal = document.getElementById('modalDocsAlumno');
    if (!modal) return;

    const btnClose = document.getElementById('btn-close-docs-alumno');
    const btnBack = document.getElementById('btn-back-docs-alumno');
    const backdrop = document.getElementById('modal-docs-alumno-backdrop');
    
    const listaContainer = document.getElementById('lista-docs-alumno');
    const nombreLabel = document.getElementById('alumno-docs-nombre');
    const iframe = document.getElementById('doc-iframe-alumno');
    const emptyState = document.getElementById('empty-state-alumno');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action="view-alumno-docs"]');
        if (!btn) return;

        const nombre = btn.dataset.nombre;
        const docs = JSON.parse(btn.dataset.docs); 

        nombreLabel.textContent = nombre;
        renderLista(docs);

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        iframe.src = '';
        iframe.classList.add('hidden');
        emptyState.classList.remove('hidden');
    });

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        iframe.src = '';
    };

    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnBack) btnBack.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);

    function renderLista(docs) {
        listaContainer.innerHTML = '';
        let hayDocs = false;

        docs.forEach(doc => {
            if (doc.file) {
                hayDocs = true;
                const url = `/storage/${doc.file}`; 
                
                const item = document.createElement('div');
                item.className = 'group flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-sky-50 transition cursor-pointer mb-2';
                
                item.innerHTML = `
                    <div class="flex items-center overflow-hidden">
                        <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        <span class="text-sm font-medium text-gray-700 truncate group-hover:text-sky-700" title="${doc.nombre}">${doc.nombre}</span>
                    </div>
                    <a href="${url}" download onclick="event.stopPropagation()" class="p-2 text-gray-400 hover:text-green-600 transition" title="Descargar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    </a>
                `;

                item.addEventListener('click', () => {
                    iframe.src = url;
                    iframe.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                    
                    listaContainer.querySelectorAll('div.group').forEach(d => {
                        d.classList.remove('bg-sky-50', 'border-sky-300', 'ring-1', 'ring-sky-300');
                        d.classList.add('border-gray-200');
                    });
                    item.classList.remove('border-gray-200');
                    item.classList.add('bg-sky-50', 'border-sky-300', 'ring-1', 'ring-sky-300');
                });

                listaContainer.appendChild(item);
            }
        });

        if (!hayDocs) {
            listaContainer.innerHTML = `<div class="text-center py-10 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg"><p class="text-sm font-medium">Sin documentos.</p></div>`;
        }
    }
}

/**
 * 6. ASIGNATURA
 */
function initAsignaturaModal() {
    const modal = document.getElementById('modalAsignatura');
    if (!modal) return;

    const btnOpen = document.getElementById('btn-open-asignatura');
    const btnClose = document.getElementById('btn-close-asignatura');
    const backdrop = document.getElementById('modal-asignatura-backdrop');
    const iframe = document.getElementById('iframe-asignatura');
    const title = document.getElementById('asignatura-modal-nombre');

    if (btnOpen) {
        btnOpen.addEventListener('click', (e) => {
            e.preventDefault();
            iframe.src = btnOpen.dataset.url;
            title.textContent = btnOpen.dataset.nombre;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    }

    const close = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        iframe.src = '';
    };

    if (btnClose) btnClose.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);
}