function inicializaPersonalResguardoTable() {
    $('#PersonalResguardoTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/materialesEnPrestamo/materialEnResguardo/materialEnResguardoData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".PersonalResguardoTabla-error").html("");
                $("#PersonalResguardoTabla").append('<tbody class="PersonalResguardoTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#PersonalResguardoTablaprocessing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Personal", orderable: true, width: "20%", className: 'details-control' }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}