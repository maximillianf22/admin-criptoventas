const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnDeleteDistributor').on('click', function (e) {
        let id = $(e.target).parents('tr').attr('id');
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Este distribuidor será eliminado permanentemente",
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
                    url: "/administrator/distributors/" + id,
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
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

    $('.btnFilterEraseDistributors').on('click', function (e) {
        e.preventDefault()
        window.location.href = '/administrator/distributors'
    })
});