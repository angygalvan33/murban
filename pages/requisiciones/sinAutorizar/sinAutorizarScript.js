function loadDataTableRequisicionesSinAutorizar(permisoCancelar) {
    $('#requisicionesSinAutorizarTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'order': [[1, 'desc']],
        'ajax': {
            url: "pages/requisiciones/sinAutorizar/sinAutorizarData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requisicionesSinAutorizarTable-error").html("");
                $("#requisicionesSinAutorizarTable").append('<tbody class="requisicionesSinAutorizarTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesSinAutorizarTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "IdRequisicion", orderable: true, width: "15%", className: "details-control" },
            { 'data': "Fecha", orderable: true, width: "15%" },
            { 'data': "Observaciones", orderable: true, width: "30%" },
            { width: "20%", orderable: false,
                mRender: function (data, type, row) {
                    if (permisoCancelar == true)
                        return "<button type='button' id='req_autorizar' class='btn btn-success btn-sm reqAutorizar'>Autorizar</button>";
                    else
                        return "";
                }
            },
            { width: "20%", orderable: false,
                mRender: function (data, type, row) {
                    if (permisoCancelar == true)
                        return "<button type='button' id='req_cancelarSA' class='btn btn-danger btn-sm reqCancelar'>Cancelar</button>";
                    else
                        return "";
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function cancelarRequisicionSA(idRequisicion, motivo) {
    var datos = { "accion":'cancelarRequisicion', "idRequisicion":idRequisicion, "Motivo":motivo };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#requisicionesSinAutorizarTable').DataTable().ajax.reload();
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

function autorizarRequisicionSA(idRequisicion) {
    var datos = { "accion":'autorizarRequisicion', "idRequisicion":idRequisicion };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#requisicionesSinAutorizarTable').DataTable().ajax.reload();
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