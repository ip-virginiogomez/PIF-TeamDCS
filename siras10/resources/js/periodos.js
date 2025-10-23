import BaseModalManager from './base-modal-manager.js';
/**
 * Periodo Manager
 * Extiende BaseModalManager
 */
class PeriodoManager extends BaseModalManager {
    
    constructor() {
        super({
            modalId: 'periodoModal',
            formId: 'periodoForm',
            entityName: 'Período',
            entityGender: 'm',
            baseUrl: '/periodos',
            primaryKey: 'idPeriodo',
            tableContainerId: 'tabla-container',
            fields: [
                'Año',
                'fechaInicio',
                'fechaFin'
            ]
        });
    }

    validate() {
        this.clearValidationErrors(); // Llama al método del padre
        let esValido = true;
        const fechaInicio = this.form.querySelector('[name="fechaInicio"]')?.value;
        const fechaFin = this.form.querySelector('[name="fechaFin"]')?.value;
        
        if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
            esValido = false;
            // Llama al método 'showValidationErrors' del padre
            this.showValidationErrors({
                'fechaFin': ['La fecha de fin debe ser posterior a la de inicio.']
            });
        }

        return esValido;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('periodoForm')) {
        new PeriodoManager();
    }
});
