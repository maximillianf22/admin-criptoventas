const { default: Swal } = require("sweetalert2");

$(document).ready(function() {
    $(".btnDeleteCoupons").on("click", function(e) {
        let id = $(this).parents("tr").attr("id");
        Swal.fire({
            title: "¿Esta seguro?",
            text: "cupon será eliminado permanentemente",
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
                    url: "/administrator/coupons/show" + id,
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
                            "El cupon ha sido eliminado satisfactoriamente",
                            "success"
                        ).then(() => {
                            window.location.reload();
                        });
                    },
                });
            }
        });
    });
    $(".btnFilterEraseCoupons").on("click", function(e) {
        window.location.href = "/administrator/coupons";
    });

    $(".btnEditCoupons").on("click", function(e) {
        e.preventDefault();
        let id = $(this).parents("tr").attr("id");
        $.ajax({
            type: "get",
            url: "/administrator/coupons/" + id,
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
                $("#formUpdateCoupon").attr(
                    "action",
                    "/administrator/coupons/" + data.id
                );
                $(".commerce_id").val(data.commerce_id);
                $("#commerce_id option[value=" + data.commerce_id + "]").attr(
                    "selected",
                    true
                );
                $("#nameCupon").val(data.name);
                $("#minCCupon").val(data.min_shopping);
                $("#cantUso").val(data.max_quantity);
                $("#valC").val(data.value);
                $("#state option[value=" + data.state + "]").attr(
                    "selected",
                    true
                );
                $("#modalEditCoupos").modal();
                Swal.close();
            },
        });
    });
});