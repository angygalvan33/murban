function loadDataTableGastosxCat(tipo, idCategoria, fechaIni, fechaFin) {
    if ($.fn.dataTable.isDataTable('#gastoxcatConsultaTable')) {
        tablagastoxcat.destroy();
    }

    tablagastoxcat = $('#gastoxcatConsultaTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "./pages/compras/concentrado/gastosxcat/gastosxCatData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".gastoxcatConsultaTable-error").html("");
                $("#gastoxcatConsultaTable").append('<tbody class="gastoxcatConsultaTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#gastoxcatConsultaTable_processing").css("display", "none");
            },
            data: {
                "fechaIni": fechaIni,
                "fechaFin": fechaFin,
                "tipo": tipo,
                "IdCategoria": idCategoria
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "10%" },
            { 'data': "Proyecto", orderable: true, width: "10%" },
            { 'data': "NombreMaterial", orderable: true, width: "20%" },
            { 'data': "Cantidad", orderable: true, width: "25%" },
            { width: "10%",
                mRender: function (data, type, row) {
                    return "<p>$"+ row.Total +"</p>";
                }
            },
            { 'data': "Fecha", orderable: true, width: "25%" },
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function getTotalesCobro(tipo, idCategoria, fechaIni, fechaFin) {
    var total = 0;
    var datos = { "accion":'obtenerTotal', "tipo":tipo, "idCategoria":idCategoria, "fechaIni":fechaIni, "fechaFin":fechaFin };

    $.post("./pages/compras/concentrado/gastosxcat/datosGastosxCat.php", datos, function(result) {
        total = result.total;
        if (total === null)
            total = 0;
        
        $("#dTotal").html("<h4>Total:&nbsp; <strong>$"+ formatNumber(total) +"<strong></h4>");
    }, "json");
}