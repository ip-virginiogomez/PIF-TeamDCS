import Swal from 'sweetalert2';

export default class DocenteVacunaManager {
    constructor() {
        this.elements = {
            modal: document.getElementById('modalVacunasDocente'),
            form: document.getElementById('form-vacunas-docente'),
            containerLista: document.getElementById('lista-vacunas-container'),
            inputRun: document.getElementById('runDocenteVacuna'),
            titulo: document.getElementById('titulo-modal-vacunas'),
            modalPreview: document.getElementById('modalPreviewVacuna'),
            iframePreview: document.getElementById('iframe-preview-vacuna'),
            fileInput: document.getElementById('archivo_vacuna'),
            spinner: document.getElementById('spinner-vacuna')
        };

        this.currentRun = null;
        this.initEvents();
    }

    initEvents() {
        // Delegación de eventos global
        document.body.addEventListener('click', (e) => this.handleGlobalClick(e));

        // Submit del formulario
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => this.handleUpload(e));
        }
    }

    handleGlobalClick(e) {
        const target = e.target;

        // A. Abrir Modal Gestión
        const btnOpen = target.closest('button[data-action="manage-vacunas"]');
        if (btnOpen) {
            return this.abrirModal(btnOpen.dataset.run, btnOpen.dataset.nombre);
        }

        // B. Cerrar Modal Gestión
        if (target.closest('[data-action="close-modal-vacunas"]') || target.id === 'backdrop-vacunas-docente') {
            return this.cerrarModal();
        }

        // C. Eliminar Vacuna
        const btnDelete = target.closest('button[data-action="delete-vacuna"]');
        if (btnDelete) {
            return this.eliminarVacuna(btnDelete.dataset.id);
        }

        // D. Preview Documento
        const btnPreview = target.closest('button[data-action="preview-vacuna"]');
        if (btnPreview) {
            return this.abrirPreview(btnPreview.dataset.url);
        }

        // E. Cerrar Preview
        if (target.closest('[data-action="close-preview-vacuna"]') || target.id === 'backdrop-preview-vacuna') {
            return this.cerrarPreview();
        }

        // F. Cambiar Estado
        const btnStatus = target.closest('button[data-action="change-status-vacuna"]');
        if (btnStatus) {
            return this.cambiarEstadoVacuna(btnStatus.dataset.id, btnStatus.dataset.currentStatus);
        }
    }

    // --- GESTIÓN DEL MODAL PRINCIPAL ---

    abrirModal(run, nombre) {
        if (!this.elements.modal) return;

        this.currentRun = run;
        this.elements.titulo.textContent = `Vacunas: ${nombre}`;
        this.elements.inputRun.value = run;

        this.elements.modal.classList.remove('hidden');
        this.cargarLista();
    }

    cerrarModal() {
        if (!this.elements.modal) return;
        this.elements.modal.classList.add('hidden');
        this.elements.form.reset();
        this.elements.containerLista.innerHTML = '';
    }

    async cargarLista() {
        this.renderLoading();
        try {
            const response = await fetch(`/docentes/${this.currentRun}/vacunas`);
            if (!response.ok) throw new Error('Error cargando vacunas');
            const html = await response.text();
            this.elements.containerLista.innerHTML = html;
        } catch (error) {
            this.elements.containerLista.innerHTML = '<p class="text-red-500 text-xs text-center">Error al cargar datos.</p>';
        }
    }

    renderLoading() {
        this.elements.containerLista.innerHTML = `
            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                <svg class="w-8 h-8 mb-2 animate-spin text-sky-200" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-xs">Cargando datos...</span>
            </div>
        `;
    }

    // --- SUBIDA DE ARCHIVOS ---

    async handleUpload(e) {
        e.preventDefault();

        const formData = new FormData(this.elements.form);
        const btn = this.elements.form.querySelector('button[type="submit"]');
        const btnText = document.getElementById('btn-text-vacuna');

        // UI Loading
        btn.disabled = true;
        btnText.textContent = 'Subiendo...';
        this.elements.spinner.classList.remove('hidden');

        try {
            const response = await fetch(`/docentes/${this.currentRun}/vacunas`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                if (result.errors) {
                    const messages = Object.values(result.errors).flat().join('\n');
                    throw new Error(messages);
                }
                throw new Error(result.message || 'Error al subir el archivo');
            }

            if (result.success) {
                this.elements.form.reset();
                this.cargarLista();
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: 'Vacuna agregada' });
            } else {
                throw new Error(result.message || 'Error al subir');
            }
        } catch (error) {
            Swal.fire('Error', error.message, 'error');
        } finally {
            btn.disabled = false;
            btnText.textContent = 'Subir';
            this.elements.spinner.classList.add('hidden');
        }
    }

    // --- ELIMINAR VACUNA ---

    async eliminarVacuna(id) {
        const result = await Swal.fire({
            title: '¿Eliminar vacuna?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/docentes/vacunas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.cargarLista();
                    Swal.fire('Eliminado', 'La vacuna ha sido eliminada.', 'success');
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo eliminar la vacuna.', 'error');
            }
        }
    }

    // --- CAMBIAR ESTADO ---

    async cambiarEstadoVacuna(id, currentStatus) {
        // Generar opciones desde window.estadosVacuna (inyectado en Blade)
        const options = {};
        if (window.estadosVacuna) {
            window.estadosVacuna.forEach(est => {
                options[est.idEstadoVacuna] = est.nombreEstado;
            });
        }

        const { value: newStatus } = await Swal.fire({
            title: 'Actualizar Estado',
            input: 'select',
            inputOptions: options,
            inputValue: currentStatus,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) return 'Debes seleccionar un estado';
            }
        });

        if (newStatus && newStatus != currentStatus) {
            try {
                const response = await fetch(`/docentes/vacunas/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ idEstadoVacuna: newStatus })
                });

                const data = await response.json();

                if (data.success) {
                    this.cargarLista();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({ icon: 'success', title: 'Estado actualizado' });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo actualizar el estado.', 'error');
            }
        }
    }

    // --- PREVIEW ---

    abrirPreview(url) {
        if (!this.elements.modalPreview || !this.elements.iframePreview) return;
        this.elements.iframePreview.src = url;
        this.elements.modalPreview.classList.remove('hidden');
    }

    cerrarPreview() {
        if (!this.elements.modalPreview || !this.elements.iframePreview) return;
        this.elements.modalPreview.classList.add('hidden');
        this.elements.iframePreview.src = '';
    }
}