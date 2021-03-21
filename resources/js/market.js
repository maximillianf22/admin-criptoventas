const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnAddVariation').on('click', function (e) {
        e.preventDefault()
        $('#modalCreateVariation').modal()
    })

    $('.btnEditVariation').on('click', function (e) {
        e.preventDefault()
        let id = $(this).parents('tr').attr('id')
        $.ajax({
            type: "get",
            url: "/administrator/product/market/variation/" + id,
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
                let priceList = data.get_product.get_values
                console.log(data)
                $('#formEditVariation').prop('action', '/administrator/product/market/variation/update/' + id)
                $('#editVariationName').val(data.get_product.name)
                $('#editVariationContent').val(data.quantity_content)
                $('#editVariationUnit option[value=' + data.unit_id + ']').prop('selected', true)
                $('#editVariationDesc').val(data.get_product.description)

                if (priceList.length > 0) {
                    priceList.map(p => {
                        $('#editVariationPrice_' + p.profile_vp).val(p.value)
                        $('#editVariationMin_' + p.profile_vp).val(p.min)
                        $('#editVariationDiscount_' + p.profile_vp).val(p.discount)
                    })
                }
                $('#modalEditVariation').modal()
                Swal.close()
            }
        });
    })

    $('.btnEraseMarket').on('click', function (e) {
        e.preventDefault()
        let id = $(this).parents('tr').attr('id');
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Este producto será eliminado permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: "/administrator/product/market/" + id,
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
                            'El producto ha sido borrado satisfactoriamente',
                            'success'
                        ).then(() => {
                            window.location.reload()
                        })
                    }
                });
            }
        })
    })
});