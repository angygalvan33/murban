function inicializaDetalleReqTable(permisoCancelar) {
    $('#detalleReqTable').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/requisiciones/detalleReq/detalleReqData.php", //json datasource
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
            { 'data': "CantidadSolicitada", orderable: true, width: "5%" },
			{ 'data': "Unidad", orderable: true, width: "5%" },
			{ 'data': "Piezas", orderable: true, width: "5%" },
            { 'data': "CantidadAtendida", orderable: true, width: "5%" },
            { 'data': "Material", orderable: true, width: "10%" },
            { 'data': "Proyecto", orderable: true, width: "10%" },
            { 'data': "Solicita", orderable: true, width: "10%" },
            { 'data': "FechaReq", orderable: true, width: "5%" },
            { 'data': "Estado", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    if (row.Estado === "CANCELADA" || row.Estado === "PARCIALMENTE CANCELADA")
                        return "<a href='#' data-toggle='tooltip' data-html='true' title='<p>Cancelada por "+ row.UsuarioCancelacion +" el "+ row.FechaCancelacion +"</p><p>"+ row.Motivo +"</p>'>"+ row.Estado +"</a>";
                    else
                        return "<p>"+ row.Estado +"</p>";
                }
            },
            { width: "10%", orderable: false,
                mRender: function (data, type, row) {
                    return "<button type='button' id='detallereq_editar' class='btn btn-success btn-sm'>Editar</button>";
                }
            },
            { width: "10%", orderable: false,
                mRender: function (data, type, row) {
                    if (permisoCancelar)
                        return "<button type='button' id='detallereq_eliminar' class='btn btn-danger btn-sm'>Eliminar</button>";
                    else
                        return '';
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

function eliminarDetalleRequisicionrev(IdRequisicionDetalle) {
    var datos = { "accion":'eliminarDetalle', "IdRequisicionDetalle":IdRequisicionDetalle };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
        switch(result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#detalleReqTable').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}