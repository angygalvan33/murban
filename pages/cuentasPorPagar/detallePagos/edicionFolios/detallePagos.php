<script src="pages/detallePagos/edicionFolios/edicionFoliosScript.js" type="text/javascript"></script>
<script src="pages/compras/esperaFacturacion/esperaFacturacionScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-12 table-responsive">
        <div id="dt" style="text-align: right"></div>
        <table id="edicionFoliosTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Folio OC</th>
                    <th>Proveedor</th>
                    <th>Folio de Factura</th>
                    <th>Tipo de Pago</th>
                    <th>Método de Pago</th>
                    <th>Monto (mxn)</th>
                    <th>Fecha de Factura</th>
                    <th>Tipo</th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
            </tfoot>
        </table>
    </div>
</div>
<?php
    include '../compras/esperaFacturacion/modalFacturarEmitida.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        loadEdicionFoliosTable();
        
        $('#edicionFoliosTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#edicionFoliosTable').DataTable().row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#edicionFoliosTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#edicionFoliosTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatCP(row.data())).show();
                tr.addClass('shown');
            }
        });
        
        $('#edicionFoliosTable').on('click', 'button', function () {
            var data = $("#edicionFoliosTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editarFolios":
                    data["NumeroFactura"] = data.FolioFactura;
                    data["Total"] = data.Monto;
                    loadFacturacion(data, 1);
                    $("#factura").val(data.FolioFactura);
                    $("#valor_factura").val(data.Total);
                    $("#valor_factura").attr("disabled", true);
                break;
            }
        });
    });
    
    /*Formatting function forl row details - modify as you need*/
    function formatCP (rowData) {
        var divTipo = $('<div/>', { class:'tipo', id:"edicionFoliosTable" });
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOrdenCompra });
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>