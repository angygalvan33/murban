function loadDataTableDetalle() {
    $('#detalleTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/detallePagos/detallePorProveedor/detallePagosByProveedor/detallesData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".detalleTable-error").html("");
                $("#detalleTable").append('<tbody class="detalleTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#detalleTable_processing").css("display",  "none");
            },
            data: {
                "idProveedor": $(".detalles2").attr("id"),
                "FechaIni": $("#fIni").val(),
                "FechaFin": $("#fFin").val()
            }
        },
        'columns': [
            { 'data': "FolioFactura", orderable: true, width: "15%", className: 'details-control2'},
            { 'data': "TipoDP", orderable: true, width: "15%" },
            { 'data': "NombreMetodoPago", orderable: true, width: "15%" },
            { 'data': "Monto", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Monto).toFixed(2));
                }
            },
            { 'data': "Creado", orderable: true, width: "15%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'order': [[ 4, "desc" ]]
    });
}