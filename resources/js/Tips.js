const { default: Swal } = require("sweetalert2");

$(document).ready(function() {
    $(".btnDeleteTips").on("click", function(e) {
        let id = $(this).parents("tr").attr("id");
        Swal.fire({
            title: "¿Esta seguro?",
            text: "Este valor de propina será eliminado permanentemente",
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
                    url: "/administrator/tips/" + id,
                    dataType: "json",
                    beforeSend: function() {
                        Swal.fire({
                            title: "Cargando...",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            onOpen: () => {
                                Swal.showLoading();
                            },
                        });
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.close();
                        Swal.fire(
                            "Eliminado",
                            "El valor de la propina ha sido eliminada satisfactoriamente",
                            "success"
                        ).then(() => {
                            window.location.reload();
                        });
                    },
                });
            }
        });
    });

    $(".btnFilterEraseTips").on("click", function(e) {
        window.location.href = "/administrator/tips";
    });

    $(".btnEditTips").on("click", function(e) {
        e.preventDefault();
        let id = $(this).parents("tr").attr("id");
        $.ajax({
            type: "get",
            url: "/administrator/tips/" + id,
            dataType: "json",
            beforeSend: function() {
                Swal.fire({
                    title: "Cargando...",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    onOpen: () => {
                        Swal.showLoading();
                    },
                });
            },
            success: function(response) {
                console.log(response);
                let data = response.data;
                $("#formUpdateTips").attr(
                    "action",
                    "/administrator/tips/" + data.id
                );
                $(".commerce_id").val(data.commerce_id);
                $("#commerce_id option[value=" + data.commerce_id + "]").attr(
                    "selected",
                    true
                );
                $("#tips").val(data.value);
                $("#state option[value=" + data.state + "]").attr(
                    "selected",
                    true
                );
                $("#modalEdittips").modal();
                Swal.close();
            },
        });
    });
});