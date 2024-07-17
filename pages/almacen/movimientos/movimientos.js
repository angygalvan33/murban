function inicializaTablaMovimientosInventario() {
    $('#movimientosInventarioTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/movimientos/movimientosData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".movimientosInventarioTable-error").html("");
                $("#movimientosInventarioTable").append('<tbody class="movimientosInventarioTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#movimientosInventarioTable_processing").css("display", "none");
            },
            data: function(data) {
                data.FechaIni = $("#fIniInv").val(),
                data.FechaFin = $("#fFinInv").val(),
                data.TipoMovimiento = $("#tipoMovimientoInv").val(),
                data.IdPersonal = $("#idPersonalInvValue").val(),
                data.IdMaterial = $("#idMaterialInvValue").val(),
                data.IdProyecto = $("#idProyectoInvValue").val(),
                data.IdCategoria = $("#idCategoriaInvValue").val()
            }
        },
        'createdRow': function(row, data, dataIndex) {
            if (data.TipoMovimiento == 'ENTRADA')
                $(row).addClass('entradaInventario');
            else if (data.TipoMovimiento == 'SALIDA')
                $(row).addClass('salidaInventario');
            else if (data.TipoMovimiento == 'AJUSTE')
                $(row).addClass('ajusteInventario');
            else //transferencia
                $(row).addClass('transferenciaInventario');
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "5%" },
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "Categoria", orderable: true, width: "20%" },
            { 'data': "Fecha", orderable: true, width: "10%" },
            { 'data': "Obra", orderable: true, width: "10%" },
            { 'data': "Personal", orderable: true, width: "20%" },
            { 'data': "UsuarioRegistro", orderable: true, width: "15%" }
        ],
        "order": [[ 3, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function llenaPersonalInv() {
    $('#idPersonalInv').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/movimientos/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'personal',
                    searchTerm: params.term //search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            }
        }
    });
}

function llenaMaterialesInv() {
    $('#idMaterialInv').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/movimientos/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'material',
                    searchTerm: params.term //search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            }
        }
    });
}

function llenaProyectosInv() {
    $('#idProyectoInv').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/movimientos/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'obra',
                    searchTerm: params.term //search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            }
        }
    });
}

function llenaCategoriasInv() {
    $('#idCategoriaInv').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/movimientos/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'categoria',
                    searchTerm: params.term //search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            }
        }
    });
}