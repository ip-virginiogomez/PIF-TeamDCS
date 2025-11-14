import BaseModalManager from './base-modal-manager.js';

/**
 * CupoDistribucion Manager
 * Maneja el modal en la PÁGINA de distribución.
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

        // Obtenemos los cupos restantes del div
        const dataEl = document.getElementById('distribucion-data');
        this.cuposRestantes = parseInt(dataEl?.dataset.cuposRestantes || 0);
    }
    
    /**
     * Sobreescribe onSuccess (para Crear/Editar)
     */
    onSuccess(data) {
        super.onSuccess(data); // Llama al onSuccess del padre
        
        // Llama a nuestra nueva función de ayuda
        this._actualizarContadorCupos(data);
    }

    /**
     * !! ESTA ES LA PARTE NUEVA QUE FALTABA !!
     * Sobreescribe eliminarRegistro para que también actualice los cupos.
     */
    async eliminarRegistro(id) {
        try {
            // Llama al método del padre (BaseModalManager) y espera su respuesta
            // La respuesta del padre ya incluye el JSON del servidor (con 'cuposRestantes')
            const response = await super.eliminarRegistro(id); 

            // Si la respuesta del servidor (la del controlador)
            // trae 'cuposRestantes', la usamos.
            if (response && response.cuposRestantes !== undefined) {
                this._actualizarContadorCupos(response);
            }
            
            // Retorna la respuesta para que la cadena de promesas continúe
            return response;

        } catch (error) {
            // El padre (super.eliminarRegistro) ya maneja el mostrar el error
            // Simplemente relanzamos el error si es necesario
            throw error; 
        }
    }

    /**
     * !! NUEVA FUNCIÓN DE AYUDA !!
     * Creamos una función privada para no repetir código
     * y que onSuccess y eliminarRegistro puedan usarla.
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