function loadDataTableRequisicionesCanceladas() {
    $('#requisicionesCanceladasTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'order': [[1, 'desc']],
        'ajax': {
            url: "pages/requisiciones/canceladas/requisicionesData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".requisicionesCanceladasTable-error").html("");
                $("#requisicionesCanceladasTable").append('<tbody class="requisicionesCanceladasTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesCanceladasTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "IdRequisicion", orderable: true, width: "15%", className: "details-control" },
            { 'data': "Fecha", orderable: true, width: "15%" },
            { 'data': "Observaciones", orderable: true, width: "60%" },
            { 'data': "TipoRequisicion", orderable: true, width: "20%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}