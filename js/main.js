// Capa cliente: pide confirmación antes de enviar los formularios marcados con data-confirmar.
document.querySelectorAll('form[data-confirmar]').forEach(function (form) {
    form.addEventListener('submit', function (evento) {
        if (!confirm(form.dataset.confirmar)) {
            evento.preventDefault(); // el usuario ha cancelado: no se envía
        }
    });
});
