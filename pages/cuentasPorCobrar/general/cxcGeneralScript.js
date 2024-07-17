function loadDataTableGeneral() {
    var table = $('#cxcTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'stripeClasses':['stripe1', 'stripe2'],
        'ajax': {
            url: "pages/cuentasPorCobrar/general/cxcGeneralData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxcTable-error").html("");
                $("#cxcTable").append('<tbody class="cxcTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxcTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Proyecto", orderable: true, width: "10%" },
            { 'data': "Cliente", orderable: true, width: "10%" },
            { 'data': "OCFolio", orderable: true, width: "10%" },
            { 'data': "FacturaNumero", orderable: true, width: "10%" },
            { 'data': "FacturaFecha", orderable: true, width: "10%" },
            { 'data': "FacturaValor", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var result = '';
                    if (row.FacturaValor !== null)
                        result = "<p class='text-right'>" + "$"+ formatNumber(parseFloat(row.FacturaValor).toFixed(2)) +"</p>";
                    return result;
                 }
            },
            { 'data': "DiasCreditoRestantes", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var result = "<p class='text-center'>"+ row.DiasCreditoRestantes +"</p>";
                    return result;
                }
            },
            { 'data': "CobroRestante", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var result = '';
                    if (row.FacturaValor !== null)
                        result = "<p class='text-right'>" + "$"+ formatNumber(parseFloat(row.CobroRestante).toFixed(2)) +"</p>";
                    else
                        result = "<p class='text-center'>-</p>";
                    return result;
                }
            },
            { orderable: false, width: "30%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if (row.CobroRestante === '-' || row.FacturaNumero.replace(/ /g, "") === '')
                        buttons += "<button type='button' id='facturar' style='margin-right:5px' class='btn btn-warning btn-sm btn-block'>Facturar</button>";
                    else {
                        buttons += "<button type='button' id='editar_factura' style='margin-right:5px' class='btn btn-warning btn-sm btn-block'>Editar Factura</button>";
                        buttons += "<button type='button' id='cobrar' style='margin-right:5px' class='btn btn-success btn-sm btn-block'><i class='fa fa-dollar'></i>&nbsp;Cobrar</button>";
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

function loadFacturacion(data, accion) {
    $("#idRegistroEsperaFacturacion").val(data.IdProyecto);
    $("#folioInformativo").html(data.IdProyecto);
    $("#montoInformativo").html(data.OCMonto);
    
    if (accion === 'facturar') {
        $("#valor_factura").val("");
        $("#factura").val("");
        reiniciarFecha();
    }
    else if (accion === 'editar_factura') {
        $("#valor_factura").val(data.FacturaValor);
        $("#factura").val(data.FacturaNumero);
        $("#fecha_factura").val(data.FacturaFecha);
    }
    
    $("#formFact").validate().resetForm();
    $(".error").removeClass("error");
}

function facturar(idRegistroEsperaFacturacion, numFact, valorFact, fecha, tipoFactura) {
    var datos = {};
    datos["accion"] = 'facturar';
    datos["id"] = idRegistroEsperaFacturacion;
    datos["numFact"] = numFact;
    datos["valorFact"] = valorFact.replace(/\,/g, '');
    datos["fecha"] = fecha;
    datos["tipoFactura"] = tipoFactura;
    
    $.post("./pages/cuentasPorCobrar/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA FACTURADO");
                $("#successModal").modal("show");
                $('#cxcTable').DataTable().ajax.reload();
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL FACTURAR LA ORDEN DE COMPRA. ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}
//método para guardar el pago de la orden de compra
function pagarObra(idObra_, IdMetodoPago_, tipoPago, cantidad, concepto, deuda, fechaPago) {
    //pagar oc
    var datos ={ "accion":'cobrar', 'IdOObra':idObra_, 'IdMetodoPago':IdMetodoPago_, 'TipoPago':tipoPago, 'Cantidad':cantidad, 'Concepto':concepto,'Deuda':deuda, 'Fecha':fechaPago };
    
    $.post("./pages/cuentasPorCobrar/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            $('#cxcTable').DataTable().ajax.reload();
            $("#successModal .modal-body").text("SE HA REALIZADO EL COBRO");
            $("#successModal").modal("show");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL PAGAR. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}

function habilitaCantidad1(tipo) {
    switch (tipo) {
        case "1": //liquidar
            $("#cantidad").prop("disabled", true);
            $("#cantidad").val($("#pDeuda").text());
        break;
        case "2": //abonar
            $("#cantidad").prop("disabled", false);
            $("#cantidad").val("");
        break;
    }
}

function esCantidadMenorOIgualADeuda(cantidad, deuda) {
    if (parseFloat(cantidad) > parseFloat(deuda))
        return false;
    return true;
}

function getAbonosAnticipo1(idObra, valorFactura, deuda) {
    var datos = { "accion":"pagarModal", "IdObra":idObra };
    var html = "";
    
    $.post("./pages/cuentasPorCobrar/datos.php", datos ,function(result) {
        var tablaCuenta = $("#cuenta");
        html = "<tr> <th>Total Factura</th> <td class='derecha'><strong>$</strong></td><td class='derecha'>"+ formatNumber(valorFactura) +"</td></tr>";
        
        $.each(result, function(key, value) {
            html += "<tr> <th>"+ value.TipoDC +"</th> <td class='derecha'><strong>- $</strong></td><td class='derecha'>"+ formatNumber(value.Monto) +"</td></tr>";
        });
        
        html += "<tr style='border-top: 1px solid black;'> <th>Total</th> <td class='derecha'><strong>$</strong></td><td class='derecha'><p id='pDeuda' id='pDeuda'>"+ formatNumber(deuda) +"</p></td></tr>";
        tablaCuenta.html(html);
        
    }, "json");
}