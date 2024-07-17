function loadDataTableRequisicionesPendientes(permisoCancelar) {
    $('#requisicionesPendientesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'order': [[1, 'desc']],
        'ajax': {
            url: "pages/requisiciones/pendientes/requisicionesData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requisicionesPendientesTable-error").html("");
                $("#requisicionesPendientesTable").append('<tbody class="requisicionesPendientesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesPendientesTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "IdRequisicion", orderable: true, width: "15%", className: "details-control" },
            { 'data': "Fecha", orderable: true, width: "15%" },
            { 'data': "Observaciones", orderable: true, width: "30%" },
            { 'data': "TipoRequisicion", orderable: true, width: "20%" },
            { width: "10%", orderable: false,
                mRender: function (data, type, row) {
                    if(row.TipoRequisicion == "Especial")
                        return "";
                    else
                        return "<button type='button' id='req_editar' class='btn btn-success btn-sm btn-block'>Agregar material</button>";
                }
            },
            { width: "10%", orderable: false,
                mRender: function (data, type, row) {
                    if(permisoCancelar==true)
                        return "<button type='button' id='req_cancelar' class='btn btn-danger btn-sm reqCancelar'>Cancelar</button>";
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

function cancelarRequisicion(idRequisicion, motivo) {
    var datos = { "accion":'cancelarRequisicion', "idRequisicion":idRequisicion, "Motivo":motivo };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#requisicionesPendientesTable').DataTable().ajax.reload();
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