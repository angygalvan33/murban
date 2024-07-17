function loadDataTablePendientesPago() {
    $('#cxpPendientesPago').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/cuentasPorPagar/pendientesPago/cuentasPorPagarData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxpPendientesPago-error").html("");
                $("#cxpPendientesPago").append('<tbody class="cxpPendientesPago-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxpPendientesPago_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "IdOC", orderable: true, width: "10%", className: 'details-control',
                mRender: function (data, type, row) {
                    return "<a class='td.details-control' href='#' data-toggle='tooltip' data-html='true' data-placement='right' title='<p>Autorizada por "+ row.UsuarioAutoriza +", "+ row.AutorizaDate +"</p>'>"+ row.aFolio +"</a>";
                }
            },
            { 'data': "Proveedor", orderable: true, width: "20%" },
            { 'data': "FolioFactura", orderable: true, width: "15%" },
            { 'data': "FechaFacturacion", orderable: true, width: "10%" },
            { 'data': "Total", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var color = "black";

                    if (row.Total !== null)
                        var result = "<p style='color:"+ color +"'><strong>" + "$"+ formatNumber(parseFloat(row.Total).toFixed(2)) +"</strong></p>";
                    else
                        var result = "<p style='color:"+ color +"'><strong>- - -</strong></p>";
                    return result;
                }
            },
            { 'data': "Deuda", orderable: true, width: "15%",
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
            { orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='pPagar' style='margin-right:5px' class='btn btn-success btn-sm'>Pagar</button>";
                    buttons += "<button type='button' id='pCancelarPago' style='margin-right:5px' class='btn btn-warning btn-sm'>Cancelar pago</button>";
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
        },
        'drawCallback': function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
    });
}