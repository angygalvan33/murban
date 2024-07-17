function inicializaEntradasMaterialPrestamoTable() {
    $('#MaterialPrestamoPTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/materialesEnPrestamo/materialEnPrestamo/materialEnPrestamoData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".MaterialPrestamoPTabla-error").html("");
                $("#MaterialPrestamoPTabla").append('<tbody class="MaterialPrestamoPTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#MaterialPrestamoPTablaprocessing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "20%", className: 'details-control' },
            { 'data': "CantidadPrestamo", orderable: true, width: "10%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}