import Swal from 'sweetalert2';

export default class AlumnoVacunaManager {
    constructor() {
        // Elementos del DOM
        this.modal = document.getElementById('modalVacunas');
        this.form = document.getElementById('form-vacunas');
        this.containerLista = document.getElementById('lista-vacunas-container');
        
        // Inputs ocultos y títulos
        this.inputRun = document.getElementById('runAlumnoVacuna');
        this.titulo = document.getElementById('titulo-modal-vacunas');
        
        // Estado
        this.currentRun = null;

        // Inicializar
        this.initEvents();
    }

    initEvents() {
        // 1. Escuchar clics globales para abrir el modal (Delegación de eventos)
        document.body.addEventListener('click', (e) => {
            // A. Botón "Jeringa" en la tabla de alumnos
            const btnOpen = e.target.closest('button[data-action="manage-vacunas"]');
            if (btnOpen) {
                const run = btnOpen.dataset.run;
                const nombre = btnOpen.dataset.nombre;
                this.abrirModal(run, nombre);
            }

            // B. Botón Cerrar (X o botón gris)
            if (e.target.closest('[data-action="close-modal-vacunas"]') || e.target.id === 'modal-backdrop-vacunas') {
                this.cerrarModal();
            }

            // C. Botón Eliminar Vacuna (Dinámico dentro de la lista)
            const btnDelete = e.target.closest('button[data-action="delete-vacuna"]');
            if (btnDelete) {
                this.eliminarVacuna(btnDelete.dataset.id);
            }
        });

        // 2. Submit del Formulario (Subir Vacuna)
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleUpload(e));
        }
    }

    abrirModal(run, nombre) {
        if (!this.modal) return;

        this.currentRun = run;
        this.titulo.textContent = `Vacunas: ${nombre}`;
        this.inputRun.value = run;

        this.modal.classList.remove('hidden');
        this.cargarLista();
    }

    cerrarModal() {
        if (!this.modal) return;
        this.modal.classList.add('hidden');
        this.form.reset();
        this.containerLista.innerHTML = ''; // Limpiar para que no se vea lo anterior al reabrir
    }

    async cargarLista() {
        this.containerLista.innerHTML = '<div class="flex justify-center p-4"><i class="fas fa-spinner fa-spin text-green-600"></i></div>';

        try {
            const response = await fetch(`/alumnos/${this.currentRun}/vacunas`);
            if (!response.ok) throw new Error('Error cargando vacunas');
            const html = await response.text();
            this.containerLista.innerHTML = html;
        } catch (error) {
            this.containerLista.innerHTML = '<p class="text-red-500 text-xs text-center">Error al cargar datos.</p>';
        }
    }

    async handleUpload(e) {
        e.preventDefault();

        const fileInput = document.getElementById('archivo_vacuna');
        const file = fileInput.files[0];

        if (file && file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo demasiado grande',
                text: 'El documento no debe superar los 2MB.',
                confirmButtonColor: '#d33'
            });
            return; 
        }

        const btn = this.form.querySelector('button[type="submit"]');
        const spinner = document.getElementById('spinner-vacuna');
        const formData = new FormData(this.form);

        // UI Loading
        btn.disabled = true;
        if(spinner) spinner.classList.remove('hidden');

        try {
            const response = await fetch(`/alumnos/${this.currentRun}/vacunas`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.form.reset();
                this.inputRun.value = this.currentRun; // Restaurar el RUN porque el reset lo borra
                this.cargarLista(); // Recargar la lista sin cerrar el modal

                // Toast de éxito
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: 'Vacuna guardada correctamente' });
            } else {
                Swal.fire('Error', data.message || 'No se pudo subir el archivo', 'error');
            }

        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Ocurrió un problema de conexión', 'error');
        } finally {
            btn.disabled = false;
            if(spinner) spinner.classList.add('hidden');
        }
    }

    async eliminarVacuna(id) {

        if (!id) {
            console.error("ID de vacuna no encontrado");
            return;
        }

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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.cargarLista(); // Refrescar lista
                    Swal.fire('Eliminado', '', 'success');
                } else {
                    Swal.fire('Error', 'No se pudo eliminar', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Error de conexión', 'error');
            }
        }
    }
}