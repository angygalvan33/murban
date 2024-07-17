<link href="pages/cajaChica/administracion/Reembolsos/DetalleReembolsos/detalleReembolsosStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12 table-responsive">
    <table id="detalleReembolsosFacturadosTable" class="table table-hover">
        <thead class="encabezadoTablaDetalle">
            <tr>
                <th>Folio de factura</th>
                <th>Descripción</th>
                <th>Total (MXN)</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        loadDataTableDetalleReembolsosFacturados();
    });
    
    function loadDataTableDetalleReembolsosFacturados() {
        $('#detalleReembolsosFacturadosTable').DataTable( {
            'processing': true,
            'serverSide': true,
            'responsive':true,
            'ajax': {
                url: "pages/cajaChica/administracion/Reembolsos/DetalleReembolsos/detalleReembolsosFacturadosData.php", //json datasource
                type: "post", //method, by default get
                data: {
                    "IdCajaChica": $(".detalles").attr("id"),
                    "Fecha": $(".detallesReembolso").attr("id")
                },
                error: function(){ //error handling
                    $(".reembolsosFacturadosTable-error").html("");
                    $("#reembolsosFacturadosTable").append('<tbody class="reembolsosFacturadosTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                    $("#reembolsosFacturadosTable_processing").css("display", "none");
                }
            },
            'columns': [
                { 'data': "FolioFactura",orderable: true, width: "10%" },
                { 'data': "Descripcion",orderable: true, width: "30%" },
                { 'data': "Total", orderable: true, width: "10%", className:"alinearDerecha",
                    mRender: function (data, type, row) {
                        return "$"+ formatNumber(parseFloat(row.Total).toFixed(2));
                    }
                },
            ],
            'language': {
                "url": "bower_components/datatables.net-bs/Spanish.json"
            }
        });
    }
</script>