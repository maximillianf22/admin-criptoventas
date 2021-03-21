const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnDeleteforCategories').on('click', function (e) {
        let category_id = $(this).parents('tr').attr('id');
        let commerce = $('#idcommercio').val();
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Esta Categoria será eliminado permanentemente",
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
                    url: "/administrator/commerces/categories/",
                    data: {
                        category_id: category_id,
                        commerce: commerce
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Eliminado',
                            'La cetegoria ha sido borrado satisfactoriamente',
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
