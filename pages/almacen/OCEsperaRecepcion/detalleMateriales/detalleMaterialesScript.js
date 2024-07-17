function inicializaDetalleMaterialesTable() {
    $('#detalleOCRecepcionTable').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/compras/detalleOC/detalleOCData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".detalleOCRecepcionTable-error").html("");
                $("#detalleOCRecepcionTable").append('<tbody class="detalleOCRecepcionTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#detalleOCRecepcionTable_processing").css("display", "none");
            },
            data: {
                "IdOrdenCompra": $(".detalles").attr("id")
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "10%" },
            { 'data': "Recibido", orderable: true, width: "10%" },
            { 'data': "Nombre", orderable: true, width: "15%" },
            { 'data': "NombreObra", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return row.NombreObra;
                }
            },
            { 'data': "Solicita", orderable: true, width: "10%" },
            { 'data': "Archivo", orderable: false, width: "10%",
                mRender: function (data, type, row) {
                    if (row.Archivo == null) {
                        return "-";
                    }
                    else {
                        return "<a href='descargarArchivo.php?id="+ row.IdDetalleOrdenCompra +"' class='linkArchivo'><i class='fa fa-file'></i>Descargar</a>";
                    }
                }
            },
            { 'data': "Ubicacion", orderable: true, width: "10%" }, //cambiar por ubicacion
            { 'data': "FechaProv", orderable: true, width: "10%" },
            { orderable: false, width: "10%",
                mRender: function (data, type, row) {
                    var buttons = "";

                    if (parseInt(row.Recibido) < parseInt(row.Cantidad)) {
                        buttons += "<button type='button' id='recibirMaterial' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Recibir</button>";
                    }

                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function recibirMaterialAlmacen(idObra, idMaterial, cantidadRecibida, precioUnitario, idProveedor, idDetalleOC, idOC, nombreMaterial, precioUnitario) {
    var datos = { "accion":'recibirMaterial', "idOC":idOC, "idObra":idObra, "idMaterial":idMaterial, "cantidad":cantidadRecibida, "precioUnitario":precioUnitario, "idProveedor":idProveedor, "idDetalleOC":idDetalleOC, "nombreMaterial":nombreMaterial };
    
    $.post("./pages/almacen/OCEsperaRecepcion/detalleMateriales/datos.php", datos, function(result) {
        $('#detalleOCRecepcionTable').DataTable().ajax.reload();
        $('#ocEsperaTable').DataTable().ajax.reload();
        $('#salidasTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function recibirTodo(idOC) {
    var datos = { "accion":'recibirTodo', "idOC":idOC };
    
    $.post("./pages/almacen/OCEsperaRecepcion/detalleMateriales/datos.php", datos, function(result) {
        $('#detalleOCRecepcionTable').DataTable().ajax.reload();
        $('#ocEsperaTable').DataTable().ajax.reload();
        $('#salidasTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}