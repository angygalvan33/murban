function inicializaDetalleReqTable() {    
    $('#detalleReqTable').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/requisiciones/detalleReqAtendidas/detalleReqData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".detalleReqTable-error").html("");
                $("#detalleReqTable").append('<tbody class="detalleReqTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#detalleReqTable_processing").css("display", "none");
            },
            data: {
                "IdRequisicion": $(".detalles").attr("id")
            }
        },
        'columns': [
            { 'data': "CantidadSolicitada", orderable: true, width: "10%" },
			{ 'data': "Unidad", orderable: true, width: "10%" },
			{ 'data': "Piezas", orderable: true, width: "10%" },
            { 'data': "CantidadAtendida", orderable: true, width: "10%" },
            { 'data': "Material", orderable: true, width: "25%" },
            { 'data': "Proyecto", orderable: true, width: "25%" },
            { 'data': "Solicita", orderable: true, width: "25%" },
            { 'data': "Estado", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    if (row.Estado === "CANCELADA" || row.Estado === "PARCIALMENTE CANCELADA")
                        return "<a href='#' data-toggle='tooltip' data-html='true' title='<p>Cancelada por "+ row.UsuarioCancelacion +" el "+ row.FechaCancelacion +"</p><p>"+ row.Motivo +"</p>'>"+ row.Estado +"</a>";
                    else
                        return "<p>"+ row.Estado +"</p>";
                }
            }
        ],
        'drawCallback': function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}