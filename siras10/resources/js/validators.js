export function validarTelefono(telefono) {
    if (!telefono || telefono.trim() === '') {
        return true;
    }
    const patronRegex = /^\+[0-9]{1,11}$/;
    return patronRegex.test(telefono);
}

export function validarCorreo(correo) {
    if (!correo || correo.trim() === '') {
        return true;
    }
    const patronRegex = /^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/;
    return patronRegex.test(correo);
}

export function validarRun(run) {
    if (!run || run.trim() === '') {
        return false;
    }
    
    // Eliminar puntos del RUN antes de validar
    run = run.replace(/\./g, '');
    
    // Verificar que tenga el formato número-dígito verificador (sin restricción de longitud mínima)
    const patronRegex = /^[0-9]+-[0-9kK]$/;
    if (!patronRegex.test(run)) {
        return false;
    }

    const partes = run.split('-');
    let numero = parseInt(partes[0], 10);
    let dv = partes[1].toLowerCase();

    let suma = 0;
    let multiplicador = 2;

    while (numero > 0) {
        const digito = numero % 10;
        suma += digito * multiplicador;
        multiplicador = (multiplicador < 7) ? multiplicador + 1 : 2;
        numero = Math.floor(numero / 10);
    }

    const resto = suma % 11;
    const dvCalculado = (resto === 1) ? 'k' : (resto === 0) ? '0' : (11 - resto).toString();

    return dv === dvCalculado;
}