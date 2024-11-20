$('#cerrarSesion').click(function (e) {
    e.preventDefault();

    $.ajax({
        url: '/controller/logout.php',
        type: 'POST',
        success: function(response) {
            window.location.href = "/";
        },
        error: function() {
            alert('Ocurrió un error al cerrar la sesión');
        }
    });
});

// Separador de miles
function agregarSeparadorDeMiles(inputId) {
    $('#' + inputId).on('input', function() {
        let numeroSinFormato = $(this).val().replace(/,/g, '');
        let numeroFormateado = numeroSinFormato.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).val(numeroFormateado);
    });
}