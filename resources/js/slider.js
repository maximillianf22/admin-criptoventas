const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnDeleteSlider').on('click', function (e) {
        let id = $(this).parents('tr').attr('id');
        console.log(id);
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Este slider será eliminado permanentemente",
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
                    url: "/administrator/commerces/sliders/delete",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Eliminado',
                            'El slider ha sido borrado satisfactoriamente',
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
