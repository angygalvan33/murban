<script src="pages/cuentasPorPagar/esperaFacturacion/esperaFacturacionScript.js" type="text/javascript"></script>
<script src="pages/compras/esperaFacturacion/esperaFacturacionScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="cxpEsperaFacturacion" class="table table-hover">
        <thead>
            <tr>
                <th>Folio OC</th>
                <th>Proveedor</th>
                <th>Folio de factura</th>
                <th>Fecha de facturación</th>
                <th>Monto (MXN)</th>
                <th>Días de crédito restantes</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
        </tfoot>
    </table>
</div>
<?php
    include '../compras/esperaFacturacion/modalFacturarEmitida.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTableCPEsperaFacturacion();
        
        $('#cxpEsperaFacturacion').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#cxpEsperaFacturacion').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#cxpEsperaFacturacion').DataTable().row('.shown').length) {
                    $('.details-control', $('#cxpEsperaFacturacion').DataTable().row('.shown').node()).click();
                }
                row.child( formatCP(row.data()) ).show();
                tr.addClass('shown');
            }
        });
        
        $('#cxpEsperaFacturacion').on('click', 'button', function () {
            var data = $("#cxpEsperaFacturacion").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "pEditarEF":
                    data["IdOrdenCompra"] = data.IdOC;
                    data["Total"] = data.ValorFactura;
                    data["NumeroFactura"] = data.FolioFactura;
                    loadFacturacion(data, 1);
                    $("#valor_factura").val(data.ValorFactura);
                    $("#factura").attr("placeholder", data.FolioFactura);
                    $("#factura").attr("disabled", false);
                    $("#valor_factura").attr("disabled", true);
                    $("#fecha_factura").attr("disabled", true);
                break;
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatCP (rowData) {
        var divTipo = $('<div/>', { class:'tipo', id:"cuentasPorPagarEsperaFacturacion" });
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOC });
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>