const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnChangeState').on('click', function () {
        let id = $(this).attr('id')
        let state = $(this).attr('data-state')
        $.ajax({
            type: "put",
            url: "/administrator/orders/updateState",
            data: {
                id: id,
                state: state
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Cargando...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                })
            },
            dataType: "json",
            success: function (response) {
                if (response.code == 200) {
                    Swal.close()
                    Swal.fire(
                        '',
                        response.message,
                        'success'
                    ).then(() => {
                        window.location.reload()
                    })
                }
            },
            error: function (xhr, status, error) {
                Swal.fire(
                    'Error',
                    'Hemos tenido problemas, intente mas tarde',
                    'error'
                )
            }
        });
    })
});
$('.canceleOrder').click(function () {
    Swal.fire({
        title: '¿Esta seguro?',
        text: "Esta de Cancelar este pedido",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $('#canceleForm').submit();
        }
    })
});
