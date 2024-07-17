function loadDataTableProveedoresRequisiciones(checkboxHabilitado, tipo, idProyecto, callback) {
    tablaProveedoresRequi = $('#proveedoresRequisicionesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requisproy/detalleProveedores/listadoProveedoresData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdReqDetalle": $(".detallesProvRequisiciones").attr("id"),
                "Tipo": tipo,
                "IdProyecto": idProyecto,
                "piezas": checkboxHabilitado
            },
            error: function() { //error handling
                $(".proveedoresRequisicionesTable-error").html("");
                $("#proveedoresRequisicionesTable").append('<tbody class="proveedoresRequisicionesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#proveedoresRequisicionesTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "CantidadPreOC", orderable: true, width: "10%" },
            { 'data': "Proveedor", orderable: true, width: "35%" },
            { 'data': "Precio", orderable: true, width: "20%" },
            { 'data': "FechaPrecio", orderable: true, width: "20%" },
            { 'data': "UnidadReq", orderable: true, width: "15%" },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
					return "<button type='button' id='req_comprar' style='margin-right:5px' class='btn btn-success btn-sm'>PreOC</button>";
                }
            }
        ],
        "order": [[ 1, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
	callback();
}

function cuentaCantidad() {
    var $rows = $("#materialesRequisicionesTable tr");
}