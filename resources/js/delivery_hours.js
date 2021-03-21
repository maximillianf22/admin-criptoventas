const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btnWeekDay').on('click', function () {
        let commerce = $('#commerce_id').val()
        let weekDay = $(this).val()

        if (commerce.length == 0) {
            Swal.fire('', 'Debe escoger un comercio primero', 'warning')
            return
        }

        $('.btnWeekDay').removeClass('active')
        $(this).addClass('active')

        $.ajax({
            type: "get",
            url: "/administrator/shipping/byCommerceByWeekday",
            data: {
                commerce_id: commerce,
                weekDay: weekDay
            },
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
                Swal.close()
                if (response.code === 200) {
                    $('.horarios').empty()
                    $('.horarios').append(response.data)
                }
            },
            error: function (xhr, status, error) {
                Swal.fire(
                    'Error',
                    'Hemos tenido problemas, intente mas tarde',
                    'error'
                )
            }
        });
    })

    $('body').on('click', '.btnEditHorario', function () {
        let id = $(this).parents('tr').attr('id')
        $.ajax({
            type: "get",
            url: "/administrator/shipping/" + id,
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
                Swal.close()
                if (response.code == 200) {
                    $('#idHorario').val(response.data.id)
                    $('#hora_inicial').val(response.data.init_hour)
                    $('#hora_final').val(response.data.fin_hour)
                    $('#limite_cupo').val(response.data.limit == -1 ? '' : response.data.limit)
                    $('#state option[value=' + response.data.state + ']').attr('selected', true)
                    $('#modalEditHora').modal()
                }
            },
            error: function (xhr, status, error) {
                Swal.fire(
                    'Error',
                    'Hemos tenido problemas, intente mas tarde',
                    'error'
                )
            }
        });
    })

    $('body').on('click', '.btnEraseHorario', function () {
        let id = $(this).parents('tr').attr('id')
        Swal.fire({
            title: '¿Está seguro?',
            text: "Este horario será borrado definitivamente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: "/administrator/shipping/" + id,
                    data: {
                        weekDay: $('.btnWeekDay.active').val(),
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
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
                    dataType: "json",
                    success: function (response) {
                        Swal.close()
                        if (response.code == 200) {
                            $('.horarios').empty()
                            $('.horarios').append(response.data)
                            Swal.fire(
                                'Eliminado',
                                'Horario eliminado satisfactoriamente',
                                'success'
                            )
                        }
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

    $('#formEditHora').on('submit', function (e) {
        e.preventDefault()
        let id = $('#idHorario').val()
        let weekday = $('.btnWeekDay.active').val()
        let formData = new FormData(this)
        formData.append('weekDay', weekday)

        $.ajax({
            type: "post",
            url: "/administrator/shipping/" + id,
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
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
                Swal.close()
                if (response.code == 200) {
                    $('.horarios').empty()
                    $('.horarios').append(response.data)
                    $('#modalEditHora').modal('hide')
                }
            },
            error: function (xhr, status, error) {
                Swal.fire(
                    'Error',
                    'Hemos tenido problemas, intente mas tarde',
                    'error'
                )
            }
        });
    })

    $('#formCreateHora').on('submit', function (e) {
        e.preventDefault()
        let commerce = $('#commerce_id').val()
        let weekday = $('.btnWeekDay.active').val()
        let formData = new FormData(this)
        formData.append('commerce_id', commerce)
        formData.append('weekDay', weekday)

        $.ajax({
            type: "post",
            url: "/administrator/shipping",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
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
                Swal.close()
                if (response.code === 200) {
                    $('.horarios').empty()
                    $('.horarios').append(response.data)
                    $('#formCreateHora').trigger('reset')
                    $('#modalCreateHora').modal('hide')
                }
            },
            error: function (xhr, status, error) {
                Swal.fire(
                    'Error',
                    'Hemos tenido problemas, intente mas tarde',
                    'error'
                )
            }
        });
    })
});