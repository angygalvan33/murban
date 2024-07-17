function loadDataTableReembolsosFacturados() {
    $('#reembolsosFacturadosTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/cajaChica/administracion/Reembolsos/reembolsosFacturadosData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdCajaChica": $(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".reembolsosFacturadosTable-error").html("");
                $("#reembolsosFacturadosTable").append('<tbody class="reembolsosFacturadosTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#reembolsosFacturadosTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "FechaRegistroCorte", orderable: true, width: "5%", className: 'details-control2' }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function loadDataTableReembolsosNoFacturados() {
    $('#reembolsosNoFacturadosTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/cajaChica/administracion/Reembolsos/reembolsosNoFacturadosData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdCajaChica": $(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".reembolsosNoFacturadosTable-error").html("");
                $("#reembolsosNoFacturadosTable").append('<tbody class="reembolsosNoFacturadosTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#reembolsosNoFacturadosTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "FechaRegistroCorte",orderable: true, width: "5%", className: 'details-control2' }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}