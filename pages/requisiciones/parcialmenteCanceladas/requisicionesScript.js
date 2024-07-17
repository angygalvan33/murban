function loadDataTableRequisicionesParcialmenteCanceladas() {
    $('#requisicionesParcialmenteCanceladasTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/requisiciones/parcialmenteCanceladas/requisicionesData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requisicionesParcialmenteCanceladasTable-error").html("");
                $("#requisicionesParcialmenteCanceladasTable").append('<tbody class="requisicionesParcialmenteCanceladasTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesParcialmenteCanceladasTable_processing").css("display", "none");
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