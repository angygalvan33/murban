function loadDataTableByProveedor() {
    $('#cxpByProveedorTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/cuentasPorPagar/cuentasPorProveedor/cuentasPorPagarData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".cxpByProveedorTable-error").html("");
                $("#cxpByProveedorTable").append('<tbody class="cxpByProveedorTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#cxpByProveedorTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Proveedor", orderable: true, width: "30%", className: 'details-control' },
            { 'data': "LimiteCredito", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    return "$"+ parseFloat(row.LimiteCredito).toFixed(2);
                }
            },
            { 'data': "Deuda", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Deuda).toFixed(2));
                }
            },
            { 'data': "TotalPropuesto", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "<div id='"+ row.IdProveedor +"_tp'>$"+ formatNumber(parseFloat(row.TotalPropuesto).toFixed(2)) +"</div><input type='hidden' id='"+ row.IdProveedor +"_tpvalue' value='"+ parseFloat(row.TotalPropuesto).toFixed(2) +"'/>";
                }
            },
            { 'data': "TotalAutorizado", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "<div id='"+ row.IdProveedor +"_ta'>$"+ formatNumber(parseFloat(row.TotalAutorizado).toFixed(2)) +"</div><input type='hidden' id='"+ row.IdProveedor +"_tavalue' value='"+ parseFloat(row.TotalAutorizado).toFixed(2) +"'/>";
                }
            }
        ],
        'order': [[ 3, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}