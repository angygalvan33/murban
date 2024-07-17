function loadDataTableCPEsperaFacturacion() {
    $('#cxpEsperaFacturacion').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/cuentasPorPagar/esperaFacturacion/cuentasPorPagarData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxpEsperaFacturacion-error").html("");
                $("#cxpEsperaFacturacion").append('<tbody class="cxpEsperaFacturacion-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxpEsperaFacturacion_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "10%", className: 'details-control' },
            { 'data': "Proveedor", orderable: true, width: "20%" },
            { 'data': "FolioFactura", orderable: true, width: "15%" },
            { 'data': "FechaFacturacion", orderable: true, width: "10%" },
            { 'data': "Deuda", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    var color = "black";
                    var cd = row.CreditoDisponible;
                    
                    if (cd == 0)
                        color = "red";
                    
                    var result = "<p style='color:"+ color +"'><strong>" + "$"+ formatNumber(parseFloat(row.Deuda).toFixed(2)) +"</strong></p>"
                    return result;
                 }
            },
            { 'data': "DiasCreditoRestantes", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var dc = parseInt(row.DiasCreditoRestantes);
                    var color = "black";

                    if (dc == 0)
                        color = "orange";
                    else if(dc < 0)
                        color = "red";

                    var result = "<p style='color:"+ color +"'><strong>"+ dc +"</strong></p>";
                    return result;
                 }
            },
            { orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";
                        buttons += "<button type='button' id='pEditarEF' style='margin-right:5px' class='btn btn-success btn-sm'>Editar</button>";
                    
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
        } 
    });
}