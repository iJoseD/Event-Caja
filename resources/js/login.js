$('#button-iniciarSesion').click(function (e) {
    e.preventDefault();

    $(this).prop('disabled', true);
    $(this).empty();
    $(this).append('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span role="status"> Validando...</span>');

    var CorreoElectronico = $('#CorreoElectronico').val();
    var Contrasena = $('#Contrasena').val();

    var formData = new FormData();
    formData.append('Accion', 'Ingresar');
    formData.append('CorreoElectronico', CorreoElectronico);
    formData.append('Contrasena', Contrasena);

    var pElement = $('<p>').addClass('error mt-3 text-danger').text('Estas credenciales no coinciden con nuestros registros.');

    $.ajax({
        url: '/controller/login.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if ( response === 'Ingreso exitoso' ) {
                window.location.href = '/';
            } else {
                console.log(response);
                $('.form div:nth-child(3)').after(pElement);
                $('#button-iniciarSesion').prop('disabled', false);
                $('#button-iniciarSesion').empty();
                $('#button-iniciarSesion').append('Acceso <i class="bi bi-arrow-right"></i>');
            }
        },
        error: function() {
            alert('Ocurrió un error al guardar los datos');
        }
    });
});