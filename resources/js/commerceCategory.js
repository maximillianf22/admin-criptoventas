const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnDeleteCommerceCategories').on('click', function (e) {
        let id = $(this).parents('tr').attr('id');
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Esta Categoria será eliminado permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: "/administrator/commerces/category/" + id,
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
                            'La categoria ha sido borrado satisfactoriamente',
                            'success'
                        ).then(() => {
                            window.location.reload()
                        })
                    },
                    error: function () {
                        Swal.fire(
                            'Error',
                            'Hemos tenido problemas. Intente mas tarde',
                            'error'
                        )
                    }
                });
            }
        })
    })

    $('.btnFilterEraseCategory').on('click', function (e) {
        e.preventDefault()
        window.location.href = '/administrator/commerces/category'
    })
});
