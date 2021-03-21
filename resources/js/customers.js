const { default: Swal } = require("sweetalert2");

$(document).ready(function (e) {

    $('.btnDeleteCustomer').on('click', function (e) {
        let id = $(e.target).parents('tr').attr('id');
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Este cliente será eliminado permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                console.log(id);
                $.ajax({
                    type: "delete",
                    url: "/administrator/custommers/" + id,
                    data: {
                        id: id
                    },
                    dataType: "json",
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
                    success: function (response) {
                        console.log(response);
                        Swal.close()
                        Swal.fire(
                            'Eliminado',
                            'El usuario ha sido borrado satisfactoriamente',
                            'success'
                        ).then(() => {
                            window.location.reload()
                        })
                    }
                });
            }
        })
    })
})

$('#distributorSearcher').select2()

$(".profile_vp").change(() => {

    if ($('.profile_vp').val() == "5") {
        alert("hola");
        $('.distributor_percent').prop('disabled', true)
        $('.distributor_code').prop('disabled', true)
    } else {
        if ($('.profile_vp').val() == "4") {
            $('.distributor_percent').prop('disabled', false)
            $('.distributor_code').prop('disabled', false)
        }

    }
})
