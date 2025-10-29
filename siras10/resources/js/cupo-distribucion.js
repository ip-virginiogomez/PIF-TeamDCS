import BaseModalManager from './base-modal-manager.js';

/**
 * CupoDistribucion Manager
 * Maneja el modal en la PÁGINA de distribución.
 * No necesita 'validate()' propio, usa el del BaseModalManager.
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
                'cantCupos' // Coincide con tu migración y Blade
            ]
        });

        // Obtenemos los cupos restantes del div (esto está bien)
        const dataEl = document.getElementById('distribucion-data');
        this.cuposRestantes = parseInt(dataEl?.dataset.cuposRestantes || 0);
    }

    // ¡¡¡ SE ELIMINA EL MÉTODO validate() DE AQUÍ !!!
    // Usará el validate() por defecto de BaseModalManager (que retorna true)
    
    /**
     * Sobreescribe onSuccess para actualizar los cupos restantes
     * (Este método SÍ es necesario)
     */
    onSuccess(data) {
        super.onSuccess(data); // Llama al onSuccess del padre
        
        // Actualiza el display de cupos restantes si el backend lo envía
        if (data.cuposRestantes !== undefined) {
            document.getElementById('cupos-restantes-display').textContent = data.cuposRestantes;
            const dataEl = document.getElementById('distribucion-data');
            if (dataEl) dataEl.dataset.cuposRestantes = data.cuposRestantes;
            this.cuposRestantes = data.cuposRestantes;
        }
    }
}

// =======================================================================
// ACTIVACIÓN 
// =======================================================================
document.addEventListener('DOMContentLoaded', () => {
    // Se activa solo si el formulario de distribución existe en esta página
    if (document.getElementById('distribucionForm')) {
        new CupoDistribucionManager();
    }
});