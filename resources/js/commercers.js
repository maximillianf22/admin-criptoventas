const { default: Swal } = require("sweetalert2");

$(document).ready(function() {
    $(".btnDeleteCommerces").on("click", function(e) {
        let id = $(this).parents("tr").attr("id");
        Swal.fire({
            title: "¿Esta seguro?",
            text: "Este comercio será eliminado permanentemente",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: " commerces/delete",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        Swal.fire(
                            "Eliminado",
                            "El comercio ha sido borrado satisfactoriamente",
                            "success"
                        ).then(() => {
                            window.location.reload();
                        });
                    },
                });
            }
        });
    });

    $(".btnFilterEraseCommerce").on("click", function(e) {
        e.preventDefault();
        window.location.href = "/administrator/commerces";
    });

    $(".selectCommerceType").on("change", function(e) {
        e.preventDefault();
        let id = $(this).val();
        $.ajax({
            type: "get",
            url: "/administrator/commerces/category/showByCommerceType",
            data: {
                id: id,
            },
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
                console.log(response.data);
                $(".categoriesList").empty();
                $(".categoriesContainer").show();
                let data = response.data;
                data.map((c) => {
                    $(".categoriesList").append(
                        `
                        <div class="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="categories[]" value="${c.id}" class="custom-control-input" id="cat_${c.id}">
                                <label class="custom-control-label" for="cat_${c.id}">${c.name}</label>
                            </div>
                        </div>

                        `
                    );
                });
                Swal.close();
            },
            error: function() {
                Swal.fire(
                    "Error",
                    "Hemos tenido problemas. Intente mas tarde",
                    "error"
                );
            },
        });
    });

    $(".btnFilterEraseListCommerces").on("click", function(e) {
        e.preventDefault();
        window.location.href = "/administrator/products/commerces";
    });

    $(".btnUpdatecommerce").on("click", function(e) {
        e.preventDefault();
        if (
            $("#newAddressLat").val().length == 0 ||
            $("#newAddressLong").val().length == 0
        ) {
            Swal.fire(
                "",
                "Por favor seleccione la dirección correctamente",
                "warning"
            );
        } else {
            $("#formUpdateCommerce").submit();
        }
    });
});