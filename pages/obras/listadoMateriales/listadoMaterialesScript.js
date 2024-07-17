function loadMaterialesDataTable() {
    $('#materialesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/obras/listadoMateriales/listadoMaterialesData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdObra": $(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".materialesTable-error").html("");
                $("#materialesTable").append('<tbody class="materialesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#materialesTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "10%" },
            { 'data': "NombreMaterial", orderable: true, width: "30%" },
            { 'data': "Total", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Total).toFixed(2));
                }
            },
            { 'data': "FolioOC", orderable: true, width: "20%" },
            { 'data': "FechaMovimiento", orderable: true, width: "20%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}