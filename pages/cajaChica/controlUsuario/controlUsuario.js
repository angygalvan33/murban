function loadDataTableControlUsuarios() {
    $('#usuarioCChTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/cajaChica/controlUsuario/controlUsuarioData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".usuarioCChTable-error").html("");
                $("#usuarioCChTable").append('<tbody class="usuarioCChTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#usuarioCChTable_processing").css("display", "none");
            },
            data: function(data) {
                data.IdUsuario = $("#usuarioCUValue").val(),
                data.FechaIni = $("#fIni").val(),
                data.FechaFin = $("#fFin").val(),
                data.TipoMovimiento = $("#tipoMovimiento").val()
            }
        },
        'createdRow': function(row, data, dataIndex) {
            if (data.Accion == 'Entrada')
                $(row).addClass('entradaCajaChica');
            else
                $(row).addClass('salidaCajaChica');
        },
        'columns': [
            { 'data': "Creado",orderable: true, width: "10%" },
            { 'data': "Obra",orderable: true, width: "10%" },
            { 'data': "Material", orderable: true, width: "15%" },
            { 'data': "FolioFactura", orderable: true, width: "15%" },
            { 'data': "Total", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return formatNumber(parseFloat(row.Total).toFixed(2));
                }
            },
            { width: "10%",
                mRender: function (data, type, row) {
                    if (row.Accion == "Salida") {
                        if (row.FolioFactura != null) {
                            if (row.Pagada == 1) {
                                $(row).addClass("selected");
                                return "SI";
                            }
                            else
                                return "NO";
                        }
                        else
                            return "NO REEMBOLSABLE";
                    }
                    else {
                        return "-";
                    }
                }
            },
            { 'data': "Accion", orderable: true, width: "10%" },
            { 'data': "TipoAbono", orderable: true, width: "10%" },
            { 'data': "Descripcion", orderable: true, width: "10%" }
        ],
        "order": [[ 0, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}
//metodos de autocomplete
function autoCompleteObrasCU() {
    $('.obraAut').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: 'pages/cajaChica/controlUsuario/autocompletesCU.php',
            type: "post",
            dataType: 'json',
            delay: 250,
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

function autoCompleteMaterialesCU() {
    $('.materialAut').select2( {
        placeholder: "Selecciona una opción",
        tags: true,
        createTag: function (params) {
            $("#materialAut").text("");
            $("#materialValue").val("-1");
            return {
                id: "-1",
                text: params.term
            }
        },
        ajax: {
            url: 'pages/cajaChica/controlUsuario/autocompletesCU.php',
            type: "post",
            dataType: 'json',
            delay: 250,
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

function getGastadoDeUsuario(idUsuario) {
    var datos = {};
    datos["accion"] = "getGastadoDeUsuario";
    datos["idUsuario"] = idUsuario;
    
    $.post("pages/cajaChica/controlUsuario/datos.php", datos, function(result) {
        $("#pCCh").html("<h4>Gastado:&nbsp;<strong>$"+ formatNumber(result.toFixed(2)) +"<strong></h4>");
    }, "json");
}