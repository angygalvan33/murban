function loadDataTableGeneral(permisoProponer, permisoAutorizar, permisoPagar) {
    $('#cxpTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/cuentasPorPagar/general/cuentasPorPagarData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxpTable-error").html("");
                $("#cxpTable").append('<tbody class="cxpTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxpTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "5%", className: 'details-control' },
            { 'data': "Proveedor", orderable: true, width: "15%" },
            { 'data': "FolioFactura", orderable: true, width: "10%" },
            { 'data': "FechaFacturacion", orderable: true, width: "10%" },
            { 'data': "Deuda", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var color = "black";
                    var cd = row.CreditoDisponible;
                    
                    if (cd == 0)
                        color = "red";
                    
                    var result = "<p style='color:"+ color +"'><strong>" + "$"+ formatNumber(parseFloat(row.Deuda).toFixed(2)) +"</strong></p>";
                    return result;
                 }
            },
            { 'data': "DiasCreditoRestantes", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var dc = parseInt(row.DiasCreditoRestantes);
                    var color = "black";

                    if (dc == 0)
                        color = "orange";
                    else if (dc < 0)
                        color = "red";
                    
                    var result = "<p style='color:"+ color +"'><strong>"+ dc +"</strong></p>";
                    return result;
                 }
            },
            {  'data': "Proponer", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    if (permisoProponer) {
                        if (row.Proponer == 1)
                            return "<input id='proponer' class='proponer icheckbox_flat-green' checked type='checkbox'>";
                        else
                            return "<input id='proponer' class='proponer icheckbox_flat-green' type='checkbox'>";
                    }
                    else {
                        if (row.Proponer == 1)
                            return "<input id='proponer' class='proponer icheckbox_flat-green' checked type='checkbox' disabled>";
                        else
                            return "<input id='proponer' class='proponer icheckbox_flat-green' type='checkbox' disabled>";
                    }
                }
            },
            { 'data': "TipoOC", orderable: true, width: "10%" },
            { orderable: false, width: "10%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if (permisoPagar)
                        buttons += "<button type='button' id='pagar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-dollar'></i>&nbsp;Pagar</button>";
                    
                    buttons += "<button type='button' id='cancelar' style='margin-right:5px' class='btn btn-danger btn-sm'>Cancelar</button>";
                    return buttons;
                }
            }
        ],
        "order": [[ 6, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'footerCallback': function (row, data, start, end, display) {
            var api = this.api(), data;
            $("#dt").html("<h4>Deuda total:&nbsp;<strong>$"+ formatNumber(cont) +"<strong></h4>");
            $("#dtpropuesto").html("<h4>Total Propuesto:&nbsp;<strong>$"+ formatNumber(tpropuesto) +"<strong></h4>");
            $("#dtautorizado").html("<h4>Total Autorizado:&nbsp;<strong>$"+ formatNumber(tautorizado) +"<strong></h4>");
        }
    });
}

function llenaMetodoPago() {
    var datos = { "accion":'getMetodosPago' };

    $("select[name='metodoPago']").append($("<option value='' selected='selected' disabled>Selecciona método de pago</option>"));
    
    $.post("./pages/compras/datos.php", datos, function(result) {
        $.each(result, function(i, val) {
            $("select[name='metodoPago']").append($("<option>", {
                value: val.IdMetodoPago,
                text: val.Nombre
            }));
        });
    }, "json");
}
//método para guardar el pago de la orden de compra
function pagarOC(idOC_, IdMetodoPago_, tipoPago, cantidad, cantidadFact, concepto, deuda, fechaPago) {
    //pagar oc
    var datos = { "accion":'pagar', 'IdOC':idOC_, 'IdMetodoPago':IdMetodoPago_, 'TipoPago':tipoPago, 'Cantidad':cantidad, 'CantidadFact':cantidadFact, 'Concepto':concepto, 'Deuda':deuda, 'Fecha':fechaPago };
    
    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            $('#cxpTable').DataTable().ajax.reload();
            $("#successModal .modal-body").text("SE HA REALIZADO EL PAGO.");
            $("#successModal").modal("show");
            
            var datos = { "accion":'obtenerDeudaTotal' };

            $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
                cont = result;
                if (cont == null) {
                    cont = 0;
                }
            }, "json");

            var datos = { "accion":'obtenerDeudaPropuesta' };

            $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
                tpropuesto = result;
                if (tpropuesto == null) {
                    tpropuesto = 0;
                }
            }, "json");

            var datos = { "accion":'obtenerDeudaAutorizada' };

            $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
                tautorizado = result;
                if (tautorizado == null) {
                    tautorizado = 0;
                }
            }, "json");
            
            $('#cxpPendientesPago').DataTable().ajax.reload();
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL PAGAR. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}

function autorizarOC(idOC, valor, tipo, idProv, valorFactura) {
    if (tipo == 1)
        proponer(idOC, valor, idProv, valorFactura);
    else if (tipo == 2)
        autorizar(idOC, valor, idProv, valorFactura);
}

function proponer(idOC, valor, idProv, valorFactura) {
    var datos = { "accion":"proponer", "IdOrdenCompra":idOC, "edo":valor, "idProveedor":idProv, "ValorFactura":valorFactura };
        
    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        if (result["error"] == 1) {
            $("#errorModal .modal-body").text("ERROR AL CAMBIAR EL ESTADO DE PROPUESTO. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
        else {
            if (valor == 1)
                tpropuesto += parseFloat(valorFactura);
            else
                tpropuesto -= parseFloat(valorFactura);
            
            if (tpropuesto < 0)
                tpropuesto = 0;
            
            $("#dtpropuesto").html("<h4>Total Propuesto:&nbsp;<strong>$"+ formatNumber(tpropuesto.toFixed(2)) +"<strong></h4>");
        }
    }, "json");
}

function autorizar(idOC, valor, idProv, valorFactura) {
    var datos = { "accion":"autorizar", "IdOrdenCompra":idOC, "edo":valor, "idProveedor":idProv, "ValorFactura":valorFactura };
    
    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        if (result["error"] == 1) {
            $("#errorModal .modal-body").text("ERROR AL CAMBIAR EL ESTADO DE AUTORIZAR. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
        else {
            if (valor == 1)
                tautorizado += parseFloat(valorFactura);
            else
                tautorizado -= parseFloat(valorFactura);

            if (tautorizado < 0)
                tautorizado = 0;

            $("#dtautorizado").html("<h4>Total Autorizado:&nbsp;<strong>$"+ formatNumber(tautorizado.toFixed(2)) +"<strong></h4>");
        }
    }, "json");
}

function cancelarAutorizarOC() {
    $('#cxpTable').DataTable().ajax.reload();
}

function habilitaCantidad(tipo) {
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

function getAbonosAnticipo(idOC, valorFactura, deuda) {
    var datos = { "accion":"modalPagar", "IdOrdenCompra":idOC };
    var html = "";
    
    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        var tablaCuenta = $("#cuenta");
        html = "<tr> <th>Total Factura</th> <td class='derecha'><strong>$</strong></td><td class='derecha'>"+ formatNumber(valorFactura) +"</td></tr>";
        
        $.each(result, function(key, value) {
            html += "<tr> <th>"+ value.TipoDP +"</th> <td class='derecha'><strong>- $</strong></td><td class='derecha'>"+ formatNumber(value.Monto) +"</td></tr>";
        });
        
        html += "<tr style='border-top: 1px solid black;'> <th>Total</th> <td class='derecha'><strong>$</strong></td><td class='derecha'><p id='pDeuda' id='pDeuda'>"+ formatNumber(deuda) +"</p></td></tr>";
        tablaCuenta.html(html);
    }, "json");
}

function cancelarOC(idOC, motivo) {
    var datos = { "accion":"cancelarOC", "IdOrdenCompra":idOC, "Motivo":motivo };
    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        if (result["error"] == 1) {
            $("#errorModal .modal-body").text(result["result"]);
            $("#errorModal").modal("show");
        }
        else {
            $("#errorModal .modal-body").text(result["result"]);
            $("#errorModal").modal("show");
            $('#cxpTable').DataTable().ajax.reload();
        }
    }, "json");
}