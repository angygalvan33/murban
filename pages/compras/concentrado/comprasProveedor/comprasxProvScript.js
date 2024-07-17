function inicializaComprasxProvTable(fechaIni, fechaFin, idProveedor, idEstado) {
    $('#comprasxProvTable').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/compras/concentrado/comprasProveedor/comprasxProvData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".comprasxProvTable-error").html("");
                $("#comprasxProvTable").append('<tbody class="comprasxProvTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#comprasxProvTable_processing").css("display", "none");
            },
            data: {
                "fechaIni": fechaIni,
                "fechaFin": fechaFin,
                "idEstado": idEstado,
                "idProveedor": idProveedor
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "5%" },
            { 'data': "NumeroFactura", orderable: true, width: "10%" },
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "NombreProveedor", orderable: true, width: "15%" },
            { orderable: true, width: "5%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Total, 2);
                }
            },
            { orderable: true, width: "5%",
                mRender: function (data, type, row) {
                    return "$"+ (row.Pago ? formatNumber(row.Pago, 2) : 0);
                }
            },
            { 'data': "FechaPago", orderable: true, width: "10%" },
            { orderable: true, width: "5%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Total - row.TotalPagos, 2);
                }
            },
            { 'data': "Genera", orderable: true, width: "15%" },
            { 'data': "Autoriza", orderable: true, width: "15%" },
            { 'data': "EstadoOC", orderable: true, width: "5%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function llenaProveedores() {
    $('.multisp').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/compras/concentrado/comprasProveedor/datos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'proveedor',
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

function obtenerSaldo(fechaIni, fechaFin, idProveedor, idEstado) {
    var datos = { "accion":'saldo', "idProveedor":idProveedor, "idEstado":idEstado, "fechaIni":fechaIni, "fechaFin":fechaFin };
    
    $.post("./pages/compras/concentrado/comprasProveedor/datos.php", datos, function(result){
        var saldo = result;

        if (result["error"] === 0) {
            if (result['result'] == null) {
                $("#saldo").text('$'+ formatNumber(saldo));
            }
            else {
                saldo = result["result"];
                $("#saldo").text('$'+ formatNumber(saldo));
            }
        }
        else {
            $("#saldo").text('$'+ formatNumber(saldo));
        }
    }, "json");
}