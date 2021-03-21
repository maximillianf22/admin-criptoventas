const { default: Swal } = require("sweetalert2");

$(document).ready(function () {

    $('.btnDeleteUser').on('click', function (e) {
        let id = $(this).parents('tr').attr('id');
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Este usuario será eliminado permanentemente",
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
                    url: "/administrator/users/" + id,
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
                        )
                        window.location.reload()
                    }
                });
            }
        })
    })

    $('.btnAddDirection').on('click', function (e) {
        e.preventDefault()
        $('#modalCreateDirection').modal()
    })

    $('.btnEditAddress').on('click', function (e) {
        e.preventDefault()
        let id = $(this).attr('data-address-id');
        $.ajax({
            type: "get",
            url: "/administrator/addresses/show",
            data: {
                id: id
            },
            dataType: "json",
            success: function (response) {
                console.log(response)
                let data = response.data
                $('#formEditAddress').attr('action', '/administrator/addresses/' + id)
                $('#editUserID').val(data.user_id)
                $('#editAddressLat').val(data.lat)
                $('#editAddressLng').val(data.lng)
                $('#editAddress').val(data.address)
                $('#nameEditAddress').val(data.name)
                $('#descEditAddress').val(data.observation)

                $('#modalEditDirection').modal()
            }
        });
    })

    $('.btnDeleteAddress').on('click', function (e) {
        e.preventDefault()
        let id = $(this).attr('data-address-id');
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Esta dirección se borrará permanentemente del usuario",
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
                    url: "/administrator/addresses/delete",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Eliminado',
                            'Direccion elimanda satisfactoriamente',
                            'success'
                        ).then(() => {
                            window.location.reload()
                        })
                    }
                });
            }
        })
    })

    $('.btnFilterEraseUser').on('click', function (e) {
        e.preventDefault()
        window.location.href = '/administrator/users'
    })

    $('.btnGenNewPassword').on('click', function (e) {
        e.preventDefault()
        $.ajax({
            type: "get",
            url: "/generatePassword",
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
                $('.inputEditPassword').val(response.data)
                Swal.close()
            }
        });
    })
});
