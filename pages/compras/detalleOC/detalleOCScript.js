function inicializaDetalleOCTable() {
    $('#detalleOCTable').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/compras/detalleOC/detalleOCData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".detalleOCTable-error").html("");
                $("#detalleOCTable").append('<tbody class="detalleOCTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#detalleOCTable_processing").css("display", "none");
            },
            data: {
                "IdOrdenCompra": $(".detalles").attr("id")
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "15%" },
            { 'data': "Nombre", orderable: true, width: "25%" },
            { 'data': "PrecioUnitario", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.PrecioUnitario);
                }
            },
            { 'data': "Subtotal", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Subtotal);
                }
            },
            { 'data': "NombreObra", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return row.NombreObra;
                }
            },
            { 'data': "Solicita", orderable: true, width: "15%" },
            { 'data': "Archivo", orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    if (row.Archivo == null) {
                        return "-";
                    }
                    else {
                        return "<a href='descargarArchivo.php?id="+ row.IdDetalleOrdenCompra +"' class='linkArchivo'><i class='fa fa-file'></i>Descargar</a>";
                    }
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}