function llenaPersonalMov() {
    $('#idPersonalMov').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/materialesEnPrestamo/autocompletes.php',
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

function llenaMaterialesMov() {
    $('#idMaterialMov').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/materialesEnPrestamo/autocompletes.php',
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

function loadDataTableMovimientosPrestamo() {
    $('#movimientosPrestamoTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/materialesEnPrestamo/movimientosResguardo/movimientosResguardoData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".movimientosPrestamoTable-error").html("");
                $("#movimientosPrestamoTable").append('<tbody class="movimientosPrestamoTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#movimientosPrestamoTable_processing").css("display", "none");
            },
            data: function(data) {
                data.IdPersonal = $("#idPersonalMovValue").val(),
                data.IdMaterial = $("#idMaterialMovValue").val(),
                data.FechaIni = $("#fIniMov").val(),
                data.FechaFin = $("#fFinMov").val(),
                data.TipoMovimiento = $("#tipoMovimientoMov").val()
            }
        },
        'createdRow': function( row, data, dataIndex) {
            if (data.TipoMovimiento == 'SALIDA')
                $(row).addClass('entradaPR');
            else
                $(row).addClass('salidaPR');
        },
        'columns': [
            { 'data': "Fecha", orderable: true, width: "20%" },
            { 'data': "Cantidad",orderable: true, width: "15%" },
            { 'data': "Material",orderable: true, width: "25%" },
            { 'data': "Personal", orderable: true, width: "20%" },
            { 'data': "TipoMovimiento", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    /*if(row.TipoMovimiento === "ENTRADA")
                        return "SALIDA";
                    else
                        return "ENTRADA";*/ //03/01/2024 PENSÉ QUE ERA ASÍ PERO VIENDO LA INTERFAZ NO ME DIO SENTIDO
                    if(row.TipoMovimiento === "SALIDA")
                        return "SALIDA";
                    else
                        return "ENTRADA";
                }
            }
        ],
        "order": [[ 0, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}