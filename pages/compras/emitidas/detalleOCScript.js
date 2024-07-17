function inicializaDetalleOCTableEmitidas() {
    $('#detalleOCTableEmitidas').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/compras/emitidas/detalleOCEmitidasData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".detalleOCTableEmitidas-error").html("");
                $("#detalleOCTableEmitidas").append('<tbody class="detalleOCTableEmitidas-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#detalleOCTableEmitidas_processing").css("display", "none");
            },
            data: {
                "IdOrdenCompra": $(".detalles").attr("id")
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "15%" },
            { 'data': "Nombre", orderable: true, width: "25%" },
            { 'data': "PrecioUnitario", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.PrecioUnitario);
                }
            },
            { 'data': "Subtotal", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Subtotal);
                }
            },
            { 'data': "NombreObra", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return row.NombreObra;
                }
            },
            { 'data': "UsuarioSolicita", orderable: true, width: "15%" },
            { 'data': "Archivo", orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    if (row.Archivo == null) {
                        return "-";
                    }
                    else {
                        return "<a href='descargarArchivo.php?id="+ row.IdDetalleOrdenCompra +"' class='linkArchivo'><i class='fa fa-file'></i>Descargar</a>";
                    }
                }
            },
            { orderable:false, width: "25%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' style='margin-right:5px' class='btn btn-warning btn-sm' onclick='actualizaPrecio("+ row.IdDetalleOrdenCompra +", "+ row.IdOrdenCompra +")'>Actualiza Precio</button>";
                    //buttons += "<button type='button' id='em_eliminar' style='margin-right:5px' class='btn btn-danger btn-sm'>Eliminar partida</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function actualizaPrecio(idDetalleOC, idOC) {
    var datos = { "accion":'precioDetalleOC', "idDetalleOC":idDetalleOC, "IdOC":idOC };
    
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

function eliminarPartida(idDetalleOC, idOC) {
    var datos = { "accion":'eliminarPartida', "idDetalleOC":idDetalleOC, "IdOC":idOC };
    
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