function loadDataTableDetalleCategoria() {
    $('#detalleCategoriaTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/categoria/detalleCategoria/detalleCategoriaData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".detalleCategoriaTable-error").html("");
                $("#detalleCategoriaTable").append('<tbody class="detalleCategoriaTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#detalleCategoriaTable_processing").css("display", "none");
            },
            data: {
                "IdCategoria": $(".detalles").attr("id")
            }
        },
        'columns': [
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "Nombre", orderable: true, width: "20%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}