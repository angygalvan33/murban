function loadDataTableGeneral2() {
    var table = $('#cxcDetalleCobrosTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/cuentasPorCobrar/detalleCobros/cxcDetalleGeneralData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxcDetalleCobrosTable-error").html("");
                $("#cxcDetalleCobrosTable").append('<tbody class="cxcDetalleCobrosTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxcDetalleCobrosTable_processing").css("display", "none");
            },
            data: function(data) {
                data.FechaIni = $("#fIni").val(),
                data.FechaFin = $("#fFin").val()
            }
        },
        'columns': [
            { 'data': "Proyecto", orderable: true, width: "10%" },
            { 'data': "Cliente", orderable: true, width: "10%" },
            { 'data': "Folio", orderable: true, width: "10%" },
            { 'data': "Monto", orderable: true, width: "10%" },
            { 'data': "TipoDC", orderable: true, width: "10%" },
            { 'data': "FechaCobro", orderable: true, width: "10%" },
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function llenaMetodoCobro() {
    var datos = { "accion":'getMetodosCobro' };

    $("select[name='metodoPago']").append($("<option value='' selected='selected' disabled>Selecciona método de cobro</option>"));
    
    $.post("./pages/cuentasPorCobrar/datos.php", datos, function(result) {
        $.each(result, function(i, val) {
            $("select[name='metodoPago']").append($("<option>", {
                value: val.IdMetodoCobro,
                text: val.Nombre
            }));
       });
    }, "json");
}