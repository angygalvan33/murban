function loadDataTableDetalles(permisoProponer, permisoAutorizar) {
    $('#cxpDetallesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/cuentasPorPagar/cuentasPorProveedor/detallesCxP/detallesCxPData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxpDetallesTable-error").html("");
                $("#cxpDetallesTable").append('<tbody class="cxpDetallesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxpDetallesTable_processing").css("display", "none");
            },
            data: {
                "idProveedor": $(".detalles2").attr("id")
            }
        },
        'columns': [
            { 'data': "FolioFactura", sortable: false, width: "25%", className: 'details-control2' },
            { 'data': "FechaFacturacion", sortable: false, width: "15%" },
            { 'data': "Deuda",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Deuda).toFixed(2));
                },
                sortable: false, width: "15%"
            },
            { 'data': "DiasCreditoRestantes", sortable: false, width: "20%" },
            {  'data': "Proponer",
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
                }, width: "10%", sortable: false
            },
            { 'data': "TipoOC", sortable: false, width: "20%" }
        ],
        'footerCallback': function (row, data, start, end, display) {
        },
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autorizarOC2(idOC, edo, tipo, idProveedor, valorFactura) {
    if (tipo === "1")
        proponer2(idOC, edo, idProveedor, valorFactura);
    else if (tipo === "2")
        autorizar2(idOC, edo, idProveedor, valorFactura);
}

function proponer2(idOC, edo, idProveedor, valorFactura) {
    var datos = { "accion":"proponer", "IdOrdenCompra":idOC, "edo":edo, "idProveedor":idProveedor, "ValorFactura":valorFactura };
    
    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            if (edo == 1) {
                tp += parseFloat(valorFactura);
                tpropuesto += parseFloat(valorFactura);
            }
            else {
                tp -= parseFloat(valorFactura);
                tpropuesto -= parseFloat(valorFactura);
            }
            
            $("#"+ idProveedor +"_tp").text("$"+ formatNumber(tp.toFixed(2)));
            
            if (tpropuesto < 0)
                tpropuesto = 0;

            $("#dtpropuesto").html("<h4>Total Propuesto:&nbsp;<strong>$"+ formatNumber(tpropuesto.toFixed(2)) +"<strong></h4>");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL CAMBIAR EL ESTADO DE PROPUESTO. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}

function autorizar2(idOC, edo, idProveedor, valorFactura) {
    var datos = { "accion":"autorizar", "IdOrdenCompra":idOC, "edo":edo,"idProveedor":idProveedor, "ValorFactura":valorFactura };

    $.post("./pages/cuentasPorPagar/general/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            if (edo == 1) {
                ta += parseFloat(valorFactura);
                tautorizado += parseFloat(valorFactura);
            }
            else {
                ta -= parseFloat(valorFactura);
                tautorizado -= parseFloat(valorFactura);
            }
            
            $("#"+ idProveedor +"_ta").text("$"+ formatNumber(ta.toFixed(2)));
            
            if (tautorizado < 0)
                tautorizado = 0;
            
            $("#dtautorizado").html("<h4>Total Autorizado:&nbsp;<strong>$"+ formatNumber(tautorizado.toFixed(2)) +"<strong></h4>");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL CAMBIAR EL ESTADO DE AUTORIZAR. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
        
    }, "json");
}

function activarProponer(IdOC) {
    $('#cxpDetallesTable').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        var row = $(this.node());
        var d = this.data();
        
        if(d.IdOC === IdOC) {
            var p = row.find('td input.proponer');

            if (p.prop("checked") === false)
                proponer2(d.IdOC, 1, d.IdProveedor, d.Deuda);
            
            p.prop("checked", true);
        }
    });
}