function loadGastosObraDataTable() {
    $('#gastosObraTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/obras/listadoGastos/listadoGastosData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdObra": $(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".gastosObraTable-error").html("");
                $("#gastosObraTable").append('<tbody class="gastosObraTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#gastosObraTable_processing").css("display", "none");
            }
        },
        'createdRow': function(row, data, dataIndex) {
            if (data.EstadoPago == '2')
                $(row).addClass('gastoPagado');
            else
                $(row).addClass('gastoPendiente');
        },
        'columns': [
            { 'data': "NombreMaterial", orderable: true, width: "25%" },
            { 'data': "Cantidad", orderable: true, width: "25%" },
            { 'data': "Total", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Total).toFixed(2));
                }
            },
            { 'data': "FechaMovimiento", orderable: true, width: "20%" },
            { 'data': "Tipo", orderable: true, width: "20%" },
            { 'data': "EstadoPago", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    if (parseInt(row.EstadoPago) == 1) {
                        return "Pendiente";
                    }
                    else {
                        return "Pagado";
                    }
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}