export function validarTelefono(telefono) {
    if (!telefono || telefono.trim() === '') {
        return true;
    }
    const patronRegex = /^\+[0-9]{1,11}$/;
    return patronRegex.test(telefono);
}