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

        // Agregar validación en tiempo real para el campo Año
        this.initAñoValidation();
    }

    /**
     * Inicializa la validación del campo Año
     */
    initAñoValidation() {
        const añoInput = this.form.querySelector('[name="Año"]');
        
        if (añoInput) {
            // Limitar a 4 dígitos mientras se escribe
            añoInput.addEventListener('input', (e) => {
                let valor = e.target.value;
                
                // Solo permitir números
                valor = valor.replace(/[^0-9]/g, '');
                
                // Limitar a 4 dígitos
                if (valor.length > 4) {
                    valor = valor.slice(0, 4);
                }
                
                e.target.value = valor;
            });

            // Establecer atributos HTML5 para validación adicional
            añoInput.setAttribute('min', '2000');
            añoInput.setAttribute('max', '2099');
            añoInput.setAttribute('maxlength', '4');
        }
    }

    validate() {
        this.clearValidationErrors();
        let esValido = true;
        const errores = {};

        // Obtener valores
        const año = this.form.querySelector('[name="Año"]')?.value;
        const fechaInicio = this.form.querySelector('[name="fechaInicio"]')?.value;
        const fechaFin = this.form.querySelector('[name="fechaFin"]')?.value;

        // 1. Validar que el año tenga exactamente 4 dígitos
        if (!año || año.length !== 4) {
            esValido = false;
            errores['Año'] = ['El año debe tener exactamente 4 dígitos.'];
        } else {
            const añoNum = parseInt(año);
            
            // Validar rango razonable
            if (añoNum < 2000 || añoNum > 2099) {
                esValido = false;
                errores['Año'] = ['El año debe estar entre 2000 y 2099.'];
            }

            // 2. Validar que fechaInicio corresponda al año ingresado
            if (fechaInicio) {
                const añoFechaInicio = parseInt(fechaInicio.split('-')[0]);
                
                if (añoFechaInicio !== añoNum) {
                    esValido = false;
                    errores['fechaInicio'] = [`La fecha de inicio debe corresponder al año ${año}.`];
                }
            }

            // 3. Validar que fechaFin corresponda al año ingresado
            if (fechaFin) {
                const añoFechaFin = parseInt(fechaFin.split('-')[0]);
                
                if (añoFechaFin !== añoNum) {
                    esValido = false;
                    errores['fechaFin'] = [`La fecha de fin debe corresponder al año ${año}.`];
                }
            }
        }

        // 4. Validar que fechaFin sea posterior a fechaInicio
        if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
            esValido = false;
            errores['fechaFin'] = errores['fechaFin'] || [];
            errores['fechaFin'].push('La fecha de fin debe ser posterior a la fecha de inicio.');
        }

        // Mostrar todos los errores si existen
        if (!esValido) {
            this.showValidationErrors(errores);
        }

        return esValido;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('periodoForm')) {
        new PeriodoManager();
    }
});
