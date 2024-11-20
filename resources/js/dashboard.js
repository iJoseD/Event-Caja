$(document).ready(function () {
    function ActualizarPedidos() {
        var EventoID    = $('body').data('eventoid');
        var PdvID       = $('body').data('pdvid');

        var formData = new FormData();
        formData.append('Accion', 'ActualizarPedidos');
        formData.append('EventoID', EventoID);
        formData.append('PdvID', PdvID);

        $.ajax({
            url: '/controller/dashboard.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#Pedidos').empty();
                $('#Pedidos').html(response);
            },
            error: function() {
                alert('Ocurrió un error al guardar los datos');
            }
        });
    }

    function ObtenerUltimoID() {
        var EventoID    = $('body').data('eventoid');
        var PdvID       = $('body').data('pdvid');
        var UltimoID    = $('#UltimoID').val();

        var formData = new FormData();
        formData.append('Accion', 'ObtenerUltimoID');
        formData.append('EventoID', EventoID);
        formData.append('PdvID', PdvID);

        $.ajax({
            url: '/controller/dashboard.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if ( response == UltimoID ) {} else {
                    ActualizarPedidos();
                }
            },
            error: function() {
                alert('Ocurrió un error al guardar los datos');
            }
        });
    }

    setInterval(ObtenerUltimoID, 5000);
});

// FinalizarPedido
$('.FinalizarPedido').click(function() {
    $(this).prop('disabled', true);
    $(this).empty();
    $(this).append('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span role="status"> Procesando...</span>');
    
    var Codigo = $(this).data('codigo');

    var formData = new FormData();
    formData.append('Accion', 'FinalizarPedido');
    formData.append('Codigo', Codigo);

    $.ajax({
        url: '/controller/dashboard.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if ( response === 'Datos guardados exitosamente' ) {
                location.reload();
            } else {
                console.log(response);
            }
        },
        error: function() {
            alert('Ocurrió un error al guardar los datos');
        }
    });
});