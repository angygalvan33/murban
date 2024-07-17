function inicializaDetalleMaterialResguardoTable() {
    $('#DetalleMaterialResguardoTabla').DataTable( {
      'processing': true,
      'serverSide': true,
      'ajax': {
            url: "pages/materialesEnPrestamo/materialEnResguardo/detalleMaterialResguardo/detalleMaterialResguardoData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdPersonal": $(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".DetalleMaterialResguardoTabla-error").html("");
                $("#DetalleMaterialResguardoTabla").append('<tbody class="DetalleMaterialResguardoTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#DetalleMaterialResguardoTablaprocessing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "15%" },
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "Descripcion", orderable: true, width: "20%" },
            { 'data': "Fecha", orderable: true, width: "15%" },
            { orderable: false,
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='recibir' class='btn btn-warning btn-sm'><i class='fa fa-angle-double-left'></i>&nbsp;Recibir</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}