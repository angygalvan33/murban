function loadDataTableOCEsperaRecepcion() {
    $('#ocEsperaTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/OCEsperaRecepcion/OCEsperaRecepcionData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
              $(".ocEsperaTable-error").html("");
              $("#ocEsperaTable").append('<tbody class="ocEsperaTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
              $("#ocEsperaTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "10%", className: "details-control" },
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "NombreProveedor", orderable: true, width: "20%" },
            { 'data': "Total", orderable: true, width: "10%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Total);
                } 
            },
            { 'data': "Descripcion", orderable: true, width: "15%" },
            { 'data': "Genera", orderable: true, width: "10%" },
            { 'data': "Autoriza", orderable: true, width: "10%" }
        ],
        'order': [[1, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}