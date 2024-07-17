function loadDataTablerequisicionesxFecha(fechaIni, fechaFin) {
    if ($.fn.dataTable.isDataTable('#requisicionesxFechaTable')) {
        tablabitacora.destroy();
    }

    tablabitacora = $('#requisicionesxFechaTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "./pages/compras/concentrado/requisicionesmaterial/requisicionesxfechaData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".requisicionesxFechaTable-error").html("");
                $("#requisicionesxFechaTable").append('<tbody class="requisicionesxFechaTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesxFechaTable_processing").css("display", "none");
            },
            data: {
                "fechaIni" : fechaIni,
                "fechaFin" : fechaFin
            }
        },
        'columns': [
            { 'data': "IdRequisicion", orderable: true, width: "5%" },
            { 'data': "Fecha", orderable: true, width: "10%" },
            { 'data': "FechaReq", orderable: true, width: "10%" },
            { 'data': "Usuario", orderable: true, width: "15%" },
            { 'data': "Proyecto", orderable: true, width: "20%" },
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "CantidadA", orderable: true, width: "10%" },
            { 'data': "CantidadR", orderable: true, width: "10%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}