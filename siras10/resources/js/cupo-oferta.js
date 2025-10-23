import BaseModalManager from './base-modal-manager.js';
/**
 * CupoOferta Manager
 * Extiende BaseModalManager
 */
class CupoOfertaManager extends BaseModalManager {
    
    constructor() {
        super({
            modalId: 'cupoOfertaModal',
            formId: 'cupoOfertaForm',
            entityName: 'Oferta de Cupo',
            entityGender: 'f',
            baseUrl: '/cupo-ofertas',
            primaryKey: 'idCupoOferta',
            tableContainerId: 'tabla-container',
            fields: [
                'idPeriodo', 'idUnidadClinica', 'idTipoPractica', 'idCarrera',
                'cantCupos', 'fechaEntrada', 'fechaSalida', 'horaEntrada', 'horaSalida'
            ]
        });
    }
    
    // ¡Todo el código repetido de 'showValidationErrors' y 'clearValidationErrors' se elimina!
    // Usará los métodos del padre.
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('cupoOfertaForm')) {
        new CupoOfertaManager();
    }
});