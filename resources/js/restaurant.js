const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnDetailCategory').on('click', function () {
        let id = $(this).parents('tr').attr('id')
        if ($(this).attr('id') == 'adicional') {
            $('#newIngredientValue').prop('disabled', false)
        } else {
            $('#newIngredientValue').prop('disabled', true)
        }
        $('.bodyDetailCategories').empty()
        $.ajax({
            type: "get",
            url: "/administrator/ingredients/byCategory/" + id,
            dataType: "json",
            success: function (response) {
                let data = response.data
                console.log(data)
                $('#category_id').val(id)
                data.map(i => {
                    $('.bodyDetailCategories').append(
                        `
                        <tr id="${i.id}">
                            <td>${i.name}</td>
                            <td>${i.value == null ? 'Sin precio' : i.value}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditIngredient"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-danger btn-sm btnEraseIngredient"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        `
                    )
                })
                $('#modalDetailCategory').modal()
            }
        });
    })

    $('.btnEditIngredientCategory').on('click', function () {
        let id = $(this).parents('tr').attr('id')
        $.ajax({
            type: "get",
            url: "/administrator/ingredientsCategories/" + id,
            dataType: "json",
            success: function (response) {
                let data = response.data
                console.log(data)
                $('#formUpdateIngredientCatefory').attr('action', '/administrator/ingredientsCategories/' + id)
                $('#updateNameCategory').val(data.name)
                $('#updateMaxCategory').val(data.max_ingredients)
                $('#modalEditIngredientCategory').modal()
            }
        });
    })

    $('.btnEraseIngredientCategory').on('click', function () {
        let id = $(this).parents('tr').attr('id')
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Esta categoría será eliminada permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: "/administrator/ingredientsCategories/" + id,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Eliminado',
                            'La categoria ha sido eliminada satisfactoriamente',
                            'success'
                        ).then(() => {
                            window.location.reload()
                        })
                    }
                });
            }
        })
    })

    $('.btnAddIngredient').on('click', function () {
        let category_id = $('#category_id').val()
        let name = $('#newIngredientName').val()
        let value = $('#newIngredientValue').val()

        if (name.length == 0) {
            Swal.fire(
                '',
                'El nombre no puede estar vacío',
                'warning'
            )
            return
        }

        $.ajax({
            type: "post",
            url: "/administrator/ingredients",
            data: {
                category_id: category_id,
                name: name,
                value: value
            },
            dataType: "json",
            success: function (response) {
                let data = response.data
                if (response.code == 200) {
                    $('.bodyDetailCategories').append(
                        `
                        <tr id="${data.id}">
                            <td>${data.name}</td>
                            <td>${data.value == null ? 'Sin precio' : data.value}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditIngredient"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-danger btn-sm btnEraseIngredient"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        `
                    )
                    $('#newIngredientName').val('')
                    $('#newIngredientValue').val('')
                }
            }
        });
    })

    $('body').on('click', '.btnEditIngredient', function () {
        let id = $(this).parents('tr').attr('id')
        $.ajax({
            type: "get",
            url: "/administrator/ingredients/" + id,
            dataType: "json",
            success: function (response) {
                let data = response.data
                console.log(data)
                $('#formUpdateIngredient').attr('action', '/administrator/ingredients/' + id)
                $('#updateIngredientName').val(data.name)
                $('#updateIngredientPrice').val(data.value)
                $('#modalEditIngredient').modal()
            }
        });
    })

    $('#formUpdateIngredient').on('submit', function (e) {
        let url = $(this).attr('action')
        let id = $('#updateIngredientId').val()
        let name = $('#updateIngredientName').val()
        let value = $('#updateIngredientPrice').val()

        e.preventDefault()
        $.ajax({
            type: "put",
            url: url,
            data: {
                name: name,
                value: value
            },
            dataType: "json",
            success: function (response) {
                if (response.code == 200) {
                    $('#updateIngredientName').val('')
                    $('#updateIngredientPrice').val('')
                    $('#modalEditIngredient').modal('hide')

                    $('#modalDetailCategory').modal('hide')

                    $('.bodyDetailCategories').empty()
                    let id = $('#category_id').val()
                    $.ajax({
                        type: "get",
                        url: "/administrator/ingredients/byCategory/" + id,
                        dataType: "json",
                        success: function (response) {
                            let data = response.data
                            console.log(data)
                            data.map(i => {
                                $('.bodyDetailCategories').append(
                                    `
                                    <tr id="${i.id}">
                                        <td id="name_${i.id}">${i.name}</td>
                                        <td id="price_${i.id}">${i.value == null ? 'Sin precio' : i.value}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btnEditIngredient"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-danger btn-sm btnEraseIngredient"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                    `
                                )
                            })
                            $('#modalDetailCategory').modal()
                        }
                    });
                }
            }
        });
    })

    $('body').on('click', '.btnEraseIngredient', function () {
        let id = $(this).parents('tr').attr('id')
        Swal.fire({
            title: '¿Esta seguro?',
            text: "Esta ingrediente será eliminado permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: "/administrator/ingredients/" + id,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if (response.code == 200) {
                            Swal.fire(
                                'Eliminado',
                                'El ingrediente ha sido eliminado satisfactoriamente',
                                'success'
                            ).then(() => {
                                $('#modalDetailCategory').modal('hide')
                                $('.bodyDetailCategories').empty()
                                let id = $('#category_id').val()
                                $.ajax({
                                    type: "get",
                                    url: "/administrator/ingredients/byCategory/" + id,
                                    dataType: "json",
                                    success: function (response) {
                                        let data = response.data
                                        console.log(data)
                                        data.map(i => {
                                            $('.bodyDetailCategories').append(
                                                `
                                                <tr id="${i.id}">
                                                    <td id="name_${i.id}">${i.name}</td>
                                                    <td id="price_${i.id}">${i.value == null ? 'Sin precio' : i.value}</td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm btnEditIngredient"><i class="fas fa-pencil-alt"></i></button>
                                                        <button class="btn btn-danger btn-sm btnEraseIngredient"><i class="fas fa-trash-alt"></i></button>
                                                    </td>
                                                </tr>
                                                `
                                            )
                                        })
                                        $('#modalDetailCategory').modal()
                                    }
                                });
                            })
                        }
                    }
                });
            }
        })
    })


    $('.btnEraseRestaurant').on('click', function (e) {
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
                    url: "/administrator/product/restaurant/" + id,
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
                    },
                    error: function (xhr, status, error) {
                        Swal.fire(
                            'Error',
                            'Hemos tenido problemas, intente mas tarde',
                            'error'
                        )
                    }
                });
            }
        })
    })
});