function loadDataTableEsperaFacturacion(permisoFacturar) {
    $('#esperaFacturacionTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/esperaFacturacion/esperaFacturacionData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".esperaFacturacionTable-error").html("");
                $("#esperaFacturacionTable").append('<tbody class="esperaFacturacionTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#esperaFacturacionTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "5%", className: 'details-control' },
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "NombreProveedor", orderable: true, width: "15%" },
            { 'data': "Total", orderable: true, width: "10%",className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Total);
                }
            },
            { 'data': "Descripcion", orderable: true, width: "15%" },
            { 'data': "Genera", orderable: true, width: "15%" },
            { 'data': "Pagada", orderable: true, width: "10%" },
            { width: "10%",
                mRender: function (data, type, row) {
                    var folio = '"'+row.aFolio+'"';
                    return "<a class='linkPDF' onclick='showopciones("+ row.IdOrdenCompra +", "+ folio +")' style='cursor:pointer'><i class='fa fa-file'></i>  Descargar OC</a>";
				}
            },
            { orderable: false, width: "10%",
                mRender: function (data, type, row) {
                    var button = "<div id='"+ row.IdOrdenCompra +"Facturacion'>";
                    if (permisoFacturar) {
                        button += "<button type='button' id='em_facturar' style='margin-right:5px' class='btn btn-success btn-sm'>Facturar</button>";

                        if (row.NumeroFactura === null)
                            button += "<button type='button' id='em_pendienteFacturar' class='btn btn-warning btn-sm'>Pendiente de Facturar</button>";
                    }
					return button;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function loadFacturacion(data, tipo) {
    $("#valor_factura").val("");
    $("#tipoFactura").val(tipo);
    
    if (tipo === 1)
        $("#factura").val("");
    else {
        $("#factura").val("PENDIENTE");
        $("#valor_factura").val(data.Total);
    }
    
    reiniciarFecha();
    $("#idRegistroEsperaFacturacion").val(data.IdOrdenCompra);
    $("#folioInformativo").html(data.aFolio);
    $("#montoInformativo").html(data.Total);
    $("#formFact").validate().resetForm();
	$("#facturarModal").modal("show");
    $(".error").removeClass("error");
}

function facturarEsperaFacturacion(idRegistroEsperaFacturacion, numFact, valorFact, fecha, tipoFactura) {
    var datos = {};
    datos["accion"] = 'facturar';
    datos["id"] = idRegistroEsperaFacturacion;
    datos["numFact"] = numFact;
    datos["valorFact"] = valorFact.replace(/\,/g, '');
    datos["fecha"] = fecha;
    datos["tipoFactura"] = tipoFactura;
    
    $.post("./pages/compras/esperaFacturacion/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA FACTURADO LA ORDEN DE COMPRA.");
                $("#successModal").modal("show");
                $('#esperaFacturacionTable').DataTable().ajax.reload();
                $('#cxpEsperaFacturacion').DataTable().ajax.reload();
                $('#edicionFoliosTable').DataTable().ajax.reload();
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL FACTURAR LA ORDEN DE COMPRA. ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}