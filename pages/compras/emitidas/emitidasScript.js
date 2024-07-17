function loadDataTableEmitidas() {
    $('#emitidasTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/emitidas/emitidasData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".emitidasTable-error").html("");
                $("#emitidasTable").append('<tbody class="emitidasTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#emitidasTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "5%", className: "details-control" },
            { 'data': "Creado", orderable: true, width: "10%" },
            { width: "15%",
                mRender: function (data, type, row) {
                    return "<a onclick='changeProv("+ row.IdOrdenCompra +")' style='cursor:pointer'>"+ row.NombreProveedor +"</a>";
                }
            },
            { 'data': "Total", orderable: true, width: "5%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Total);
                }
            },
            { 'data': "Descripcion", orderable: true, width: "10%" },
            { 'data': "Genera", orderable: true, width: "10%" },
            { 'data': "Autoriza", orderable: true, width: "10%" },
            { width: "10%",
                mRender: function (data, type, row) {
                    var folio = '"'+row.aFolio+'"';
                    return "<a class='linkPDF' onclick='showopciones("+ row.IdOrdenCompra +", "+ folio +")' style='cursor:pointer'><i class='fa fa-file'></i>Descargar OC</a>";
                }
            },
            { width: "25%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='em_precios' style='margin-right:5px' class='btn btn-warning btn-sm'>Actualiza Precios</button>";
                    buttons += "<button type='button' id='em_recibir' style='margin-right:5px' class='btn btn-success btn-sm'>Recibir</button>";
                    buttons += "<button type='button' id='em_cancelar' style='margin-right:5px' class='btn btn-danger btn-sm'>Cancelar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function recibirOC(IdOC) {
    var datos = { "accion":'recibirOC', "IdOC":IdOC };
    
    $.post("./pages/compras/datos.php", datos, function(result) {
        $('#emitidasTable').DataTable().ajax.reload();
        $('#esperaFacturacionTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
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

function actualizarPreciosOC(IdOC) {
    var datos = { "accion":'preciosOC', "IdOC":IdOC };
    
    $.post("./pages/compras/datos.php", datos, function(result) {
        $('#emitidasTable').DataTable().ajax.reload();
        $('#esperaFacturacionTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
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

function cambiaNvoProv(idOccp, provnvo) {
    var datos = { "accion":'cambiaNvoProv', "IdOC":idOccp, "IdProvNvo":provnvo };
    
    $.post("./pages/compras/datos.php", datos, function(result) {
        $('#emitidasTable').DataTable().ajax.reload();
        $('#esperaFacturacionTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
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