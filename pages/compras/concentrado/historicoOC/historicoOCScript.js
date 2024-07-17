function loadDataTableHOCs() {
    $('#historicooc_Table').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/concentrado/historicoOC/historicoOCData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".historicooc_Table-error").html("");
                $("#historicooc_Table").append('<tbody class="historicooc_Table-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#historicooc_Table_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "aFolio", orderable: true, width: "5%", className: "details-control" },
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "NombreProveedor", orderable: true, width: "15%" },
            { 'data': "Total", orderable: true, width: "10%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                return "$"+ formatNumber(row.Total);
                }
            },
            { 'data': "Descripcion", orderable: true, width: "15%" },
            { 'data': "Genera", orderable: true, width: "10%" },
            { 'data': "Autoriza", orderable: true, width: "10%" },
            { width: "10%",
                mRender: function (data, type, row) {
                    var folio = '"'+row.aFolio+'"';
                    return "<a class='linkPDF' onclick='showopciones("+ row.IdOrdenCompra +", "+ folio +")' style='cursor:pointer'><i class='fa fa-file'></i>Descargar OC</a>";
                }
            },
            { 'data': "TipoOC", orderable: true, width: "15%" },
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}