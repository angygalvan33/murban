function loadDataTablePreOC() {
    $('#requispreoc_Table').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requispreoc/requispreocData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requispreoc_Table-error").html("");
                $("#requispreoc_Table").append('<tbody class="requispreoc_Table-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requispreoc_Table_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Creado", orderable: true, width: "15%", className: "details-control" },
            { 'data': "Proveedor", orderable: true, width: "15%" },
            { orderable: true, width: "10%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$"+ parseFloat(row.Total).toFixed(3);
                }
            },
            { 'data': "Genera", orderable: true, width: "10%" },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    var buttons = "<button id='descargaPreOC' type='button' class='btn btn-info btn-sm' onclick='descargaPreOC("+ row.IdProveedor +")'>Descargar Archivo</button>";
                    buttons += "<button type='button' id='req_comprar' style='margin-right:5px; margin-left:5px' class='btn btn-success btn-sm'>Comprar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}