const { default: Swal } = require("sweetalert2");

$(document).ready(function() {
    $(".btnDeleteCategories").on("click", function(e) {
        let id = $(this).parents("tr").attr("id");
        Swal.fire({
            title: "¿Esta seguro?",
            text: "Esta Categoria será eliminado permanentemente",
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
                    url: " categories/delete",
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
                        console.log(response);
                        if (response.code == 200) {
                            Swal.fire(
                                "Eliminado",
                                "El comercio ha sido borrado satisfactoriamente",
                                "success"
                            ).then(() => {
                                window.location.reload();
                            });
                        } else if (response.code == 530) {
                            Swal.fire(
                                "Eliminado",
                                response.message,
                                "success"
                            ).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON.code == 520) {
                            Swal.fire(
                                "Error",
                                xhr.responseJSON.message,
                                "error"
                            );
                        } else {
                            Swal.fire(
                                "Error",
                                "Hemos tenido problemas, intente mas tarde",
                                "error"
                            );
                        }
                    },
                });
            }
        });
    });
    $(".updateBtn").click((e) => {
        e.preventDefault();
        let id = $(e.target).parents("tr").attr("id");
        console.log($("#idCat").attr("data-imgRoute"));
        $("#order").val("");
        $("#idCat").val("");
        $(".nameC").val("");
        $(".desC").val("");
        $(".imgView").attr("src", "");

        fetch("categories/" + id, {
                method: "GET",
            })
            .then((response) =>
                response.ok ? response.json() : alert("error")
            )
            .then((data) => {
                let cat = data.data;
                console.log(cat);
                $("#order").val(cat.order);
                $("#idCat").val(cat.id);
                $(".nameC").val(cat.name);
                $(".desC").val(cat.description);
                $(".imgView").attr(
                    "src",
                    $("#idCat").attr("data-imgRoute") + "/" + cat.img_category
                );
                $("#editCategory").modal("show");
            })
            .catch((error) => alert("Error de red" + error));
    });

    $(".radioCategoryTypes").on("click", function() {
        let typeCat = $(this).val();
        if (typeCat == "cat1") {
            $(".categoryRadios").prop("disabled", true);
        }

        if (typeCat == "cat2") {
            $(".categoryRadios").prop("disabled", false);
        }
    });

    $(".selectCommerce").on("change", function() {
        let id = $(this).val();
        let radio = $(".radioCategoryTypes:checked").val();

        $.ajax({
            type: "get",
            url: "/administrator/categories/showByCommerce",
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
                        <div class="col-6">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="cat_${c.id}" ${
                            radio == "cat1" ? "disabled" : ""
                        } name="parent_id" value="${
                            c.id
                        }" class="custom-control-input categoryRadios">
                                <label class="custom-control-label" for="cat_${
                                    c.id
                                }">${c.name}</label>
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

    $(".btnFilterEraseCategory").on("click", function(e) {
        e.preventDefault();
        window.location.href = "/administrator/categories";
    });
});