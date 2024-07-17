function loadDataTableGeneral() {
    $('#detallePagosTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/detallePagos/general/detallePagosData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
              $(".detallePagosTable-error").html("");
              $("#detallePagosTable").append('<tbody class="detallePagosTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
              $("#detallePagosTable_processing").css("display", "none");
            },
            data: function(data) {
                data.FechaIni = $("#fIni").val(),
                data.FechaFin = $("#fFin").val()
            }
        },
        'columns': [
            { 'data': "FolioOC", orderable: true, width: "5%", className: 'details-control' },
            { 'data': "NombreProveedor", orderable: true, width: "15%" },
            { 'data': "FolioFactura", orderable: true, width: "5%" },
            { 'data': "TipoDP", orderable: true, width: "5%" },
            { 'data': "Concepto", orderable: true, width: "10%" },
            { 'data': "NombreMetodoPago", orderable: true, width: "10%" },
            { 'data': "Total", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var color = "black";
                    if (row.Total !== null)
                        var result = "<p style='color:"+ color +"'>" + "$"+ formatNumber(parseFloat(row.Total).toFixed(2)) +"</p>";
                    else
                        var result = "<p style='color:"+ color +"'>- - -</p>";
                    return result;
                 }
            },
            { 'data': "Monto", orderable: true, width: "5%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Monto).toFixed(2));
                }
            },
            { 'data': "Creado", orderable: true, width: "25%",
                mRender: function (data, type, row) {
                    return "<a class='td.details-control' href='#' data-toggle='tooltip' data-html='true' data-placement='right' title='<p>Autorizada por "+ row.UsuarioAutoriza +", "+ row.AutorizaDate +"</p>'>"+ row.Creado +"</a>";
                }
            },
            { 'data': "TipoOC", orderable: true, width: "10%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'drawCallback': function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        'order': [[ 7, "desc" ]]
    });
}