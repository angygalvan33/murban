function loadEdicionFoliosTable() {
    $('#edicionFoliosTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/detallePagos/edicionFolios/detallePagosData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".edicionFoliosTable-error").html("");
                $("#edicionFoliosTable").append('<tbody class="edicionFoliosTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#edicionFoliosTable_processing").css("display", "none");
            },
            data: function(data) {
                data.FechaIni = $("#fIni").val(),
                data.FechaFin = $("#fFin").val()
            }
        },
        'columns': [
            { 'data': "FolioOC", orderable: true, width: "5%", className: 'details-control' },
            { 'data': "NombreProveedor", orderable: true, width: "15%" },
            { 'data': "FolioFactura", orderable: true, width: "10%" },
            { 'data': "TipoDP", orderable: true, width: "10%" },
            { 'data': "Concepto", orderable: true, width: "10%" },
            { 'data': "NombreMetodoPago", orderable: true, width: "10%" },
            { 'data': "Monto", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    return "$" + formatNumber(parseFloat(row.Monto).toFixed(2));
                }
            },
            { 'data': "FechaFactura", orderable: true, width: "10%" },
            { 'data': "TipoOC", orderable: true, width: "10%" },
            { orderable: false, width: "10%",
                mRender: function (data, type, row) {
                    var buttons = "";
                        buttons += "<button type='button' id='editarFolios' style='margin-right:5px' class='btn btn-success btn-sm'>Editar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'order': [[ 5, "desc" ]]
    });
}