function loadDataTablePendientesAutorizar(permisoAutorizar) {
    $('#cxpPendientesAutorizar').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/cuentasPorPagar/pendientesAutorizacion/cuentasPorPagarData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxpPendientesAutorizar-error").html("");
                $("#cxpPendientesAutorizar").append('<tbody class="cxpPendientesAutorizar-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxpPendientesAutorizar_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "10%", className: 'details-control' },
            { 'data': "Proveedor", orderable: true, width: "15%" },
            { 'data': "FolioFactura", orderable: true, width: "15%" },
            { 'data': "FechaFacturacion", orderable: true, width: "10%" },
            { 'data': "Total", orderable: true, width: "5%",
                mRender: function (data, type, row) {
                    var color = "black";
                    
                    if(row.Total !== null)
                        var result = "<p style='color:"+ color +"'>" + "$"+ formatNumber(parseFloat(row.Total).toFixed(2)) +"</p>";
                    else
                        var result = "<p style='color:"+ color +"'>- - -</p>";
                    
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
            { 'data': "TipoOC", orderable: true, width: "10%" },
            { orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if(permisoAutorizar) {
                        buttons += "<button type='button' id='pAutorizar' style='margin-right:5px' class='btn btn-success btn-sm'>Autorizar</button>";
                        buttons += "<button type='button' id='pNoAutorizar' style='margin-right:5px' class='btn btn-warning btn-sm'>No autorizar</button>";
                    }
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