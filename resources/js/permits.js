const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnEditRol').on('click', function () {
        let id = $(this).parents('tr').attr('id')
        $.ajax({
            type: "get",
            url: "/administrator/rol/" + id,
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
                let data = response.data
                $('#formUpdateRol').attr('action', '/administrator/rol/' + id)
                $('#name').val(data.name)
                $('#state option[value=' + data.state + ']').attr('selected', true)
                $('#modalEditRol').modal()
                Swal.close()
            }
        });
    })

    $('.btnEraseRol').on('click', function () {
        let id = $(this).parents('tr').attr('id');
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Esta Rol será eliminado permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: "delete",
                    url: "/administrator/rol/" + id,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Eliminado',
                            'El Rol ha sido eliminada satisfactoriamente',
                            'success'
                        ).then(() => {
                            window.location.reload()
                        })
                    }
                });
            }
        })
    })

    $('.btnPermits').on('click', function () {
        let id = $(this).parents('tr').attr('id')
        $.ajax({
            type: "get",
            url: "/administrator/permits/" + id,
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
                $('input[type=checkbox]').prop('disabled', false)
                $('input[type=checkbox]').prop('checked', false)

                let data = response.data
                $('#idRol').val(id)
                data.map(p => {
                    $('#' + p.get_module.reference).prop('checked', true)
                })
                Swal.close()
            }
        });
    })
});