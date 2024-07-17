function loadDataTablePendientesFacturacion() {
    $('#ppusuarioPfCChTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/cajaChica/pendienteFacturacion/pendienteFacturacionData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".ppusuarioPfCChTable-error").html("");
                $("#ppusuarioPfCChTable").append('<tbody class="ppusuarioPfCChTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#ppusuarioPfCChTable_processing").css("display", "none");
            },
            data: function(data) {
                data.IdUsuario = $("#ppusuarioPfValue").val()
            }
        },
        'columns': [
            { 'data': "Creado",orderable: true, width: "10%" },
            { 'data': "Obra",orderable: true, width: "15%" },
            { 'data': "Material", orderable: true, width: "15%" },
            { 'data': "FolioFactura", orderable: true, width: "10%" },
            { 'data': "FechaFactura", orderable: true, width: "25%" },
            { 'data': "Total", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Total).toFixed(2));
                }
            },
            {   orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    var button = "<div id='"+ row.IdCajaChicaDetalle +"Facturacion'>";
                    button += "<button type='button' id='ch_facturar' style='margin-right:5px' class='btn btn-success btn-sm'>Facturar</button>";
                    return button;
                }
            }
        ],
        "order": [[ 0, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function facturarCompra(idRegistroEsperaFacturacion, numFact, fecha) {
    var datos = {};
    datos["accion"] = 'facturar';
    datos["id"] = idRegistroEsperaFacturacion;
    datos["numFact"] = numFact;
    datos["fecha"] = fecha;
    
    $.post("./pages/cajaChica/pendienteFacturacion/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#ppusuarioPfCChTable').DataTable().ajax.reload();
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text(msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}