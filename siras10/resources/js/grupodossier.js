document.addEventListener('DOMContentLoaded', () => {
    // Inicializamos módulos con manejo de errores
    try { initDocenteModal(); } catch (e) { console.error('Error initDocenteModal:', e); }
    try { initDocumentosModal(); } catch (e) { console.error('Error initDocumentosModal:', e); }
    try { initAddAlumnoModal(); } catch (e) { console.error('Error initAddAlumnoModal:', e); }
    try { initRemoveAlumno(); } catch (e) { console.error('Error initRemoveAlumno:', e); }
    try { initAlumnoDocsModal(); } catch (e) { console.error('Error initAlumnoDocsModal:', e); }
    try { initAsignaturaModal(); } catch (e) { console.error('Error initAsignaturaModal:', e); }
    try { initFichaAlumnoModal(); } catch (e) { console.error('Error initFichaAlumnoModal:', e); }
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

    if (!modalDocs) return;

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
        if (e) e.preventDefault();
        // Cerrar ficha si está abierta
        if (modalFicha && !modalFicha.classList.contains('hidden')) {
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
        if (iframe) {
            iframe.src = '';
            iframe.classList.add('hidden');
            if (emptyState) emptyState.classList.remove('hidden');
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
                if (iframe && emptyState) {
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

    if (!grupoIdInput) return;
    const grupoId = grupoIdInput.value;
    let debounceTimer;

    const toggle = (show) => {
        if (show) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            if (inputSearch) inputSearch.focus();
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if (inputSearch) inputSearch.value = '';
            if (resultsContainer) { resultsContainer.innerHTML = ''; resultsContainer.classList.add('hidden'); }
            if (noResults) noResults.classList.add('hidden');
        }
    };

    if (btnOpen) btnOpen.addEventListener('click', () => toggle(true));
    if (btnClose) btnClose.addEventListener('click', () => toggle(false));
    if (backdrop) backdrop.addEventListener('click', () => toggle(false));

    if (inputSearch) {
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
 * 7. FICHA ALUMNO (DINÁMICA)
 */
function initFichaAlumnoModal() {
    const modal = document.getElementById('modalFichaAlumno');
    if (!modal) return;

    const btnCloseX = document.getElementById('btn-close-ficha-alumno');
    const btnCloseBottom = document.getElementById('btn-close-ficha-alumno-bottom');
    const backdrop = document.getElementById('modal-ficha-alumno-backdrop');

    // Elementos a rellenar
    const imgFoto = document.getElementById('ficha-alumno-foto');
    const txtNombre = document.getElementById('ficha-alumno-nombre');
    const txtRun = document.getElementById('ficha-alumno-run');
    const txtCorreo = document.getElementById('ficha-alumno-correo');
    const txtNacimiento = document.getElementById('ficha-alumno-nacimiento');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action="view-alumno-ficha"]');
        if (!btn) return;

        const alumno = JSON.parse(btn.dataset.alumno);

        // Rellenar datos
        txtNombre.textContent = `${alumno.nombres} ${alumno.apellidoPaterno} ${alumno.apellidoMaterno || ''}`;
        txtRun.textContent = alumno.runAlumno;
        txtCorreo.textContent = alumno.correo || 'No registrado';

        // Formatear fecha (simple)
        if (alumno.fechaNacto) {
            const date = new Date(alumno.fechaNacto);
            // Ajuste zona horaria o usar string directo si viene YYYY-MM-DD
            const day = String(date.getDate() + 1).padStart(2, '0'); // +1 por tema de zona horaria al parsear YYYY-MM-DD
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            // Mejor usar split si viene como string YYYY-MM-DD para evitar lios de timezone
            const parts = alumno.fechaNacto.split('-');
            if (parts.length === 3) {
                txtNacimiento.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            } else {
                txtNacimiento.textContent = alumno.fechaNacto;
            }
        } else {
            txtNacimiento.textContent = 'No registrado';
        }

        // Foto
        if (alumno.foto) {
            imgFoto.src = `/storage/${alumno.foto}`;
        } else {
            imgFoto.src = `https://ui-avatars.com/api/?name=${alumno.nombres}+${alumno.apellidoPaterno}&background=bae6fd&color=0369a1&size=128`;
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    });

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    if (btnCloseX) btnCloseX.addEventListener('click', closeModal);
    if (btnCloseBottom) btnCloseBottom.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
}
function initAsignaturaModal() {
    const modal = document.getElementById('modalAsignatura');
    if (!modal) return;

    const btns = document.querySelectorAll('.btn-open-asignatura-docs');
    const btnClose = document.getElementById('btn-close-asignatura');
    const btnBack = document.getElementById('btn-back-asignatura');
    const backdrop = document.getElementById('modal-asignatura-backdrop');

    const iframe = document.getElementById('iframe-asignatura');
    const title = document.getElementById('asignatura-modal-nombre');
    const listaContainer = document.getElementById('lista-docs-asignatura');
    const emptyState = document.getElementById('empty-state-asignatura');

    btns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const button = e.currentTarget;
            const docs = JSON.parse(button.dataset.docs || '[]');
            const nombreAsignatura = button.dataset.nombre;

            title.textContent = nombreAsignatura;

            // Render list
            listaContainer.innerHTML = '';
            if (docs.length === 0) {
                listaContainer.innerHTML = '<p class="text-gray-500 text-sm text-center">No hay documentos disponibles.</p>';
            } else {
                docs.forEach(doc => {
                    const item = document.createElement('button');
                    item.className = 'w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:bg-sky-50 hover:border-sky-200 transition-colors group focus:outline-none focus:ring-2 focus:ring-sky-500';
                    item.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-sky-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-sky-700">${doc.nombre}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    `;
                    item.onclick = () => {
                        // Reset active state
                        listaContainer.querySelectorAll('button').forEach(b => {
                            b.classList.remove('bg-sky-100', 'border-sky-300');
                            b.classList.add('border-gray-200');
                        });
                        item.classList.remove('border-gray-200');
                        item.classList.add('bg-sky-100', 'border-sky-300');

                        // Show iframe
                        emptyState.classList.add('hidden');
                        iframe.classList.remove('hidden');
                        iframe.src = doc.url;
                    };
                    listaContainer.appendChild(item);
                });
            }

            // Reset view
            emptyState.classList.remove('hidden');
            iframe.classList.add('hidden');
            iframe.src = '';

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });

    const close = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        iframe.src = '';
    };

    if (btnClose) btnClose.addEventListener('click', close);
    if (btnBack) btnBack.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);
}