function loadDataTableFacturadas(permisoPresupuestos) {
    $('#facturadasTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/facturadas/facturadasData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".facturadasTable-error").html("");
                $("#facturadasTable").append('<tbody class="facturadasTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#facturadasTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "IdOrdenCompra", orderable: true, width: "10%", className: 'details-control' },
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "NombreProveedor", orderable: true, width: "15%" },
            { 'data': "Total", orderable: true, width: "15%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$"+ row.Total;
                }
            },
            { 'data': "Pagada", orderable: true, width: "5%",
                mRender: function (data, type, row) {
                    var txt;
                    if (row.Pagada == 1)
                        txt = "Sí";
                    else
                        txt = "No";
                    return txt;
                } 
            },
            { width: "10%",
                mRender: function (data, type, row) {
                    return "<a href='pdf/reportes/reporteOC.php?id="+ row.IdOrdenCompra +"' class='linkPDF' target='_blank'><i class='fa fa-file'></i>Generar Reporte</a>";
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}