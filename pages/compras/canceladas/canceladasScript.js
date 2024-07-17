function loadDataTableCanceladas() {
    $('#canceladasTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/canceladas/canceladasData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".canceladasTable-error").html("");
                $("#canceladasTable").append('<tbody class="canceladasTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#canceladasTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "FolioOC", orderable: true, width: "5%", className: 'details-control' },
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "NombreProveedor", orderable: true, width: "20%" },
            { 'data': "Total", orderable: true, width: "15%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$" + formatNumber(row.Total);
                }
            },
            { 'data': "Descripcion", orderable: true, width: "25%" },
            { 'data': "Genera", orderable: true, width: "15%" },
            { 'data': "Motivo", orderable: true, width: "10%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}