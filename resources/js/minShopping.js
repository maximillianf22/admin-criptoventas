const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.commerceMinShopping').on('change', function () {
        let id = $(this).val()

        $.ajax({
            type: "get",
            url: "/administrator/minimCompra/byCommerce",
            data: {
                commerce_id: id
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
                let data = response.data
                console.log(data)
                $('.inputValue').val('')
                data.map(m => {
                    $('#' + m.profile_vp).find('.inputValue').val(m.value)
                })
                Swal.close()
            },
            error: function () {
                Swal.fire(
                    'Error',
                    'Hemos tenido problemas. Intente mas tarde',
                    'error'
                )
            }
        });
    })

    $('#formUpdateMin').on('submit', function (e) {
        e.preventDefault()
        $.ajax({
            type: "post",
            url: "/administrator/minShoppingValue",
            data: new FormData(this),
            dataType: "json",
            processData: false,
            contentType: false,
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
                console.log(data)
                $('.inputValue').val('')
                data.map(m => {
                    $('#' + m.profile_vp).find('.inputValue').val(m.value)
                })
                Swal.close()
                Swal.fire(
                    '',
                    'Minimos actualizados completamente',
                    'success'
                )
            },
            error: function () {
                Swal.fire(
                    'Error',
                    'Hemos tenido problemas. Intente mas tarde',
                    'error'
                )
            }
        });
    })
});