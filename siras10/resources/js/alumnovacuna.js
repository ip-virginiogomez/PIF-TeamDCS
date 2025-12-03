import Swal from 'sweetalert2';

export default class AlumnoVacunaManager {
    constructor() {
        this.elements = {
            modal: document.getElementById('modalVacunas'),
            form: document.getElementById('form-vacunas'),
            containerLista: document.getElementById('lista-vacunas-container'),
            inputRun: document.getElementById('runAlumnoVacuna'),
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
        if (target.closest('[data-action="close-modal-vacunas"]') || target.id === 'modal-backdrop-vacunas') {
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
            const response = await fetch(`/alumnos/${this.currentRun}/vacunas`);
            if (!response.ok) throw new Error('Error cargando vacunas');
            const html = await response.text();
            this.elements.containerLista.innerHTML = html;
        } catch (error) {
            this.elements.containerLista.innerHTML = '<p class="text-red-500 text-xs text-center">Error al cargar datos.</p>';
        }
    }

    renderLoading() {
        this.elements.containerLista.innerHTML = '<div class="flex justify-center p-4"><i class="fas fa-spinner fa-spin text-green-600"></i></div>';
    }

    // --- ACCIONES CRUD ---

    async handleUpload(e) {
        e.preventDefault();

        const file = this.elements.fileInput.files[0];
        if (!this.validateFile(file)) return;

        this.setLoadingState(true);

        try {
            const formData = new FormData(this.elements.form);
            const response = await fetch(`/alumnos/${this.currentRun}/vacunas`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': this.getCsrfToken() },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.onUploadSuccess();
            } else {
                Swal.fire('Error', data.message || 'No se pudo subir el archivo', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Ocurrió un problema de conexión', 'error');
        } finally {
            this.setLoadingState(false);
        }
    }

    validateFile(file) {
        if (file && file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo demasiado grande',
                text: 'El documento no debe superar los 2MB.',
                confirmButtonColor: '#d33'
            });
            return false;
        }
        return true;
    }

    setLoadingState(isLoading) {
        const btn = this.elements.form.querySelector('button[type="submit"]');
        btn.disabled = isLoading;
        if (this.elements.spinner) {
            isLoading ? this.elements.spinner.classList.remove('hidden') : this.elements.spinner.classList.add('hidden');
        }
    }

    onUploadSuccess() {
        this.elements.form.reset();
        this.elements.inputRun.value = this.currentRun;
        this.cargarLista();

        Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
        }).fire({ icon: 'success', title: 'Vacuna guardada correctamente' });
    }

    async eliminarVacuna(id) {
        if (!id) return;

        const result = await Swal.fire({
            title: '¿Eliminar vacuna?',
            text: "El archivo se borrará permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/vacunas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    this.cargarLista();
                    Swal.fire('Eliminado', '', 'success');
                } else {
                    Swal.fire('Error', 'No se pudo eliminar', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Error de conexión', 'error');
            }
        }
    }

    async cambiarEstadoVacuna(id, currentStatus) {
        const estados = window.estadosVacuna || [];
        const inputOptions = estados.reduce((acc, estado) => {
            acc[estado.idEstadoVacuna] = estado.nombreEstado;
            return acc;
        }, {});

        const { value: idEstadoVacuna } = await Swal.fire({
            title: 'Cambiar Estado',
            input: 'select',
            inputOptions: inputOptions,
            inputValue: currentStatus,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => !value && 'Debes seleccionar un estado'
        });

        if (idEstadoVacuna) {
            try {
                const response = await fetch(`/vacunas/${id}/estado`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken()
                    },
                    body: JSON.stringify({ idEstadoVacuna })
                });

                const data = await response.json();

                if (data.success) {
                    this.cargarLista();
                    Swal.fire('Actualizado', data.message, 'success');
                } else {
                    Swal.fire('Error', 'No se pudo actualizar el estado', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Ocurrió un error al procesar la solicitud', 'error');
            }
        }
    }

    // --- PREVIEW ---

    abrirPreview(url) {
        if (this.elements.modalPreview && this.elements.iframePreview) {
            this.elements.iframePreview.src = url;
            this.elements.modalPreview.classList.remove('hidden');
        }
    }

    cerrarPreview() {
        if (this.elements.modalPreview && this.elements.iframePreview) {
            this.elements.modalPreview.classList.add('hidden');
            this.elements.iframePreview.src = '';
        }
    }

    // --- UTILS ---

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }
}