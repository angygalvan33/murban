function loadDataTableDetalleProveedor() {
    $('#detalleByProveedorTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/detallePagos/detallePorProveedor/detallePorProveedorData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".detalleByProveedorTable-error").html("");
                $("#detalleByProveedorTable").append('<tbody class="detalleByProveedorTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#detalleByProveedorTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "25%", className: 'details-control' },
            { 'data': "Telefono", width: "25%" },
            { 'data': "Representante", width: "25%" },
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}