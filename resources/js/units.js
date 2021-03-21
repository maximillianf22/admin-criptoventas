const { default: Swal } = require("sweetalert2");

$(document).ready(function() {
    $(".btnDeleteUnit").on("click", function(e) {
        let id = $(this).parents("tr").attr("id");
        Swal.fire({
            title: "¿Esta seguro?",
            text: "Esta unidad será eliminada permanentemente",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: "/administrator/units/" + id,
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        Swal.fire(
                            "Eliminado",
                            "La unidad ha sido eliminada satisfactoriamente",
                            "success"
                        ).then(() => {
                            window.location.reload();
                        });
                    },
                });
            }
        });
    });
    $(".btnFilterEraseUnits").on("click", function(e) {
        window.location.href = "/administrator/units";
    });

    $(".btnEditUnit").on("click", function(e) {
        e.preventDefault();
        let id = $(this).parents("tr").attr("id");
        $.ajax({
            type: "get",
            url: "/administrator/units/" + id,
            dataType: "json",
            success: function(response) {
                console.log(response);
                let data = response.data;
                $("#formUpdateUnit").attr(
                    "action",
                    "/administrator/units/" + data.id
                );
                $("#commerce_id option[value=" + data.commerce_id + "]").attr(
                    "selected",
                    true
                );
                $("#unit").val(data.name);
                $("#state option[value=" + data.state + "]").attr(
                    "selected",
                    true
                );
                $("#modalEditUnit").modal();
            },
        });
    });
});