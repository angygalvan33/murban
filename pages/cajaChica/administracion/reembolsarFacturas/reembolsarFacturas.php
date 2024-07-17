<script src="pages/cajaChica/administracion/reembolsarFacturas/reembolsarFacturas.js" type="text/javascript"></script>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-2" style="text-align:right">
            <div id="tref"></div>
        </div>
        <div class="col-md-1" style="text-align:right">
            <button type="button" id="btnPagoFacturas" class="btn btn-block btn-sm btn-danger" onclick="pagarFacturadas()"><i class="fa fa-usd"></i>&nbsp;Pagar</button>
        </div>
    </div>
    <table id="reembolsarFacturasTable" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Folio de factura</th>
                <th>Total de factura</th>
                <th>Reembolsar</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        loadDataTableReembolsarFacturas();
        
        $('#reembolsarFacturasTable tbody').on('click', 'input.pagarFacturaReembolso', function(event, state) {
            var data = $("#reembolsarFacturasTable").DataTable().row($(this).parents('tr')).data();
            
            if ($(this).prop('checked')) {
                tfactura = tfactura + parseFloat(data.Total);
                foliosFacturas.push(data.IdCajaChicaDetalle);
            }
            else {
                tfactura = tfactura - parseFloat(data.Total);
                foliosFacturas = $.grep(foliosFacturas, function(value) {
                    return value != data.IdCajaChicaDetalle;
                });
            }
            
            $("#tref").html("<h4>Total:&nbsp;<strong>$" + formatNumber(tfactura.toFixed(2)) + "<strong></h4>");
            // si hay facturas por pagar
            if (foliosFacturas.length != 0)
                $("#btnPagoFacturas").prop("disabled", false);
            else
                $("#btnPagoFacturas").prop("disabled", true);
        });
    });
</script>