import BaseModalManager from './base-modal-manager.js';

/**
 * CupoDistribucion Manager
 * Maneja el modal en la PÁGINA de distribución.
 * Extiende BaseModalManager e implementa los métodos de validación
 * estándar (aunque la validación principal la haga el backend).
 */
class CupoDistribucionManager extends BaseModalManager {

    constructor() {
        super({
            modalId: 'distribucionModal',
            formId: 'distribucionForm',
            entityName: 'Distribución',
            entityGender: 'f',
            baseUrl: '/cupo-distribuciones',
            primaryKey: 'idCupoDistribucion',
            tableContainerId: 'tabla-container',
            fields: [
                'idCupoOferta',
                'idSedeCarrera',
                'cantCupos'
            ]
        });

        // Obtenemos los cupos restantes del div (esto es específico de este módulo)
        const dataEl = document.getElementById('distribucion-data');
        this.cuposRestantes = parseInt(dataEl?.dataset.cuposRestantes || 0);
    }

    // =======================================================================
    // MÉTODOS ESTÁNDAR (COPIADOS DEL ESTÁNDAR DE SEDE)
    // =======================================================================

    /**
     * Muestra los errores de validación en el formulario.
     * (Estándar de Sede)
     */
    showValidationErrors(errors) {
        this.clearValidationErrors();
        for (const field in errors) {
            const input = this.form.querySelector(`[name="${field}"]`);
            const errorDiv = document.getElementById(`error-${field}`);
            const errorMessage = errors[field][0];

            if (input) {
                input.classList.add('border-red-500');
            }

            if (errorDiv) {
                errorDiv.textContent = errorMessage;
                errorDiv.classList.remove('hidden');
            }
        }
    }

    /**
     * Limpia los errores de validación del formulario.
     * (Estándar de Sede)
     */
    clearValidationErrors() {
        // Asegurarse de que el formulario existe
        if (!this.form) return;

        this.form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });

        this.form.querySelectorAll('[id^="error-"]').forEach(errorDiv => {
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
        });
    }

    /**
     * Validación personalizada del lado del cliente.
     * (Estándar de Sede)
     */
    validate() {
        this.clearValidationErrors();
        let esValido = true;

        // Este módulo no requiere validación JS personalizada.
        // La validación de 'cantCupos > 0' la hace el HTML con 'min="1"'.
        // La validación de 'cantCupos <= restantes' la hace el Backend.

        return esValido;
    }

    // =======================================================================
    // MÉTODOS ESPECIALIZADOS (PROPIOS DE ESTE MÓDULO)
    // =======================================================================

    /**
     * Sobreescribe onSuccess (para Crear/Editar)
     */
    onSuccess(data) {
        super.onSuccess(data); // Llama al onSuccess del padre

        // Llama a nuestra función de ayuda
        this._actualizarContadorCupos(data);
    }

    /**
     * Sobreescribe eliminarRegistro para que también actualice los cupos.
     */
    async eliminarRegistro(id) {
        try {
            // Llama al método del padre (BaseModalManager) y espera la respuesta JSON
            const response = await super.eliminarRegistro(id);

            if (response && response.cuposRestantes !== undefined) {
                this._actualizarContadorCupos(response);
            }

            return response;

        } catch (error) {
            // El padre (super.eliminarRegistro) ya maneja el mostrar el error
            throw error;
        }
    }

    /**
     * Función de ayuda para actualizar el contador de cupos en la UI.
     */
    _actualizarContadorCupos(data) {
        if (data.cuposRestantes !== undefined) {
            document.getElementById('cupos-restantes-display').textContent = data.cuposRestantes;
            const dataEl = document.getElementById('distribucion-data');
            if (dataEl) dataEl.dataset.cuposRestantes = data.cuposRestantes;
            this.cuposRestantes = data.cuposRestantes;
        }
    }
}

// =======================================================================
// ACTIVACIÓN (Esta parte queda igual)
// =======================================================================
document.addEventListener('DOMContentLoaded', () => {
    // Se activa solo si el formulario de distribución existe en esta página
    if (document.getElementById('distribucionForm')) {
        new CupoDistribucionManager();
    }
});