document.addEventListener('DOMContentLoaded', () => {

    initDocenteModal();
    initAddAlumnoModal();
    initRemoveAlumno();
    initAlumnoDocsModal();
    initAsignaturaModal();
});

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
            const url = btnOpen.dataset.url;
            const nombre = btnOpen.dataset.nombre;

            iframe.src = url;
            title.textContent = nombre;
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    }

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        iframe.src = ''; // Limpiar para detener carga
    };

    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
}

/**
 * 1. LÓGICA DEL MODAL FICHA DOCENTE (Foto y Datos)
 */
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

/**
 * 2. LÓGICA DEL MODAL DE DOCUMENTOS DOCENTE (Lista + Preview)
 */
function initDocumentosModal() {
    const modalDocs = document.getElementById('modalDocs');
    const modalFicha = document.getElementById('modalDocente');
    
    if (!modalDocs) return;

    // Botones de Apertura
    const btnOpen = document.getElementById('btn-open-docs'); 
    const btnInternal = document.getElementById('btn-view-docs-internal'); 
    
    // Botones de Cierre/Navegación
    const btnClose = document.getElementById('btn-close-docs');
    const btnBack = document.getElementById('btn-back-docs'); 
    const backdrop = document.getElementById('modal-docs-backdrop');
    
    // Elementos de contenido
    const listaPanel = document.getElementById('lista-docs-panel');
    const iframe = document.getElementById('doc-iframe');
    const emptyState = document.getElementById('empty-state');

    // --- Función para abrir ---
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

    // --- Función para cerrar ---
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

    // --- Lógica de Previsualización (Igual que Alumnos) ---
    if (listaPanel) {
        // Buscamos los contenedores de tu partial _documentos_lista
        // Asumimos que cada fila tiene un botón con data-action="preview-doc"
        // Vamos a hacer que TODA LA FILA sea clickeable para preview
        
        // 1. Ajuste visual inicial: quitamos estilos viejos si es necesario
        const filas = listaPanel.querySelectorAll('div.flex.items-center.justify-between');
        filas.forEach(fila => {
            fila.classList.add('cursor-pointer', 'hover:bg-sky-50', 'transition');
            
            // Encontrar botón de preview y obtener URL
            const btnPreview = fila.querySelector('button[data-action="preview-doc"]');
            if(btnPreview) {
                const url = btnPreview.dataset.url;
                fila.dataset.previewUrl = url; // Guardamos la URL en la fila padre
                btnPreview.style.display = 'none'; // Ocultamos el botón de ojo original
            }
        });

        // 2. Listener de clic en la fila
        listaPanel.addEventListener('click', (e) => {
            const fila = e.target.closest('div[data-preview-url]');
            
            // Si hicimos clic en el botón de descarga, no abrimos preview
            if (e.target.closest('a[download]')) return;

            if (fila) {
                const url = fila.dataset.previewUrl;
                
                // Resaltar visualmente
                listaPanel.querySelectorAll('div[data-preview-url]').forEach(f => {
                    f.classList.remove('bg-sky-50', 'border-sky-200');
                    f.classList.add('border-gray-200');
                });
                fila.classList.remove('border-gray-200');
                fila.classList.add('bg-sky-50', 'border-sky-200'); // Borde azul activo

                // Mostrar Iframe
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
 * 3. LÓGICA PARA AGREGAR ALUMNO
 */
function initAddAlumnoModal() {
    const modal = document.getElementById('modalAddAlumno');
    if (!modal) return;

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

    const toggleModal = (show) => {
        if(show) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            if(inputSearch) inputSearch.focus();
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if(inputSearch) inputSearch.value = '';
            if(resultsContainer) {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('hidden');
            }
            if(noResults) noResults.classList.add('hidden');
        }
    };

    if(btnOpen) btnOpen.addEventListener('click', () => toggleModal(true));
    if(btnClose) btnClose.addEventListener('click', () => toggleModal(false));
    if(backdrop) backdrop.addEventListener('click', () => toggleModal(false));

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
                                    <button class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-plus-circle text-xl"></i>
                                    </button>
                                `;
                                item.addEventListener('click', () => agregarAlumno(alumno.runAlumno, grupoId));
                                resultsContainer.appendChild(item);
                            });
                        } else {
                            resultsContainer.classList.add('hidden');
                            noResults.classList.remove('hidden');
                        }
                    })
                    .catch(err => console.error('Error:', err));
            }, 300);
        });
    }

    const agregarAlumno = async (runAlumno, grupoId) => {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(`/dossier/${grupoId}/agregar-alumno`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ runAlumno: runAlumno })
            });
            const res = await response.json();
            if (res.success) {
                window.location.reload();
            } else {
                alert('Error: ' + (res.message || 'No se pudo agregar'));
            }
        } catch (error) {
            console.error(error);
            alert('Error de conexión');
        }
    };
}

/**
 * 4. LÓGICA PARA ELIMINAR ALUMNO
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
            let confirmado = confirm('¿Quitar estudiante del grupo?');
            
            if (!confirmado) {
                btn.disabled = false;
                return;
            }

            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch(`/dossier/${grupoId}/eliminar-alumno/${runAlumno}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al eliminar');
                    btn.disabled = false;
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexión');
                btn.disabled = false;
            }
        }
    });
}

/**
 * 5. LÓGICA MODAL DOCUMENTOS ALUMNO (Dinámico)
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
        renderListaDocumentos(docs);

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

    function renderListaDocumentos(docs) {
        listaContainer.innerHTML = '';
        let hayDocs = false;

        docs.forEach(doc => {
            if (doc.file) {
                hayDocs = true;
                const url = `/storage/${doc.file}`; 
                
                const item = document.createElement('div');
                // Clases de estilo: Borde gris, fondo azul al pasar el mouse, cursor de mano
                item.className = 'group flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-sky-50 transition cursor-pointer mb-2';
                
                // AQUÍ ESTÁ EL DISEÑO NUEVO:
                item.innerHTML = `
                    <div class="flex items-center overflow-hidden">
                        <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        
                        <span class="text-sm font-medium text-gray-700 truncate group-hover:text-sky-700" title="${doc.nombre}">
                            ${doc.nombre}
                        </span>
                    </div>

                    <a href="${url}" download onclick="event.stopPropagation()" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-full transition duration-200" title="Descargar archivo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                `;

                // Evento: Al hacer clic en la fila, PREVISUALIZAR en el iframe
                item.addEventListener('click', () => {
                    iframe.src = url;
                    iframe.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                    
                    // Resaltar la fila seleccionada (Borde azul)
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

        // Estado vacío (si no hay documentos)
        if (!hayDocs) {
            listaContainer.innerHTML = `
                <div class="text-center py-10 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    <svg class="w-12 h-12 mb-3 opacity-30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm font-medium">Este alumno no tiene documentos.</p>
                </div>
            `;
        }
    }
}