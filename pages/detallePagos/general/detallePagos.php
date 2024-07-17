<script src="pages/detallePagos/general/detalleGeneralScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-12 table-responsive">
        <div id="dt" style="text-align: right"></div>
        <table id="detallePagosTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Folio OC</th>
                    <th>Proveedor</th>
                    <th>Factura</th>
                    <th>Tipo de Pago</th>
                    <th>Concepto</th>
                    <th>Método de Pago</th>
                    <th>OC (MXN)</th>
                    <th>Monto (MXN)</th>
                    <th>Fecha de Pago</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tfoot>
            </tfoot>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        loadDataTableGeneral();
        
        $('#detallePagosTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#detallePagosTable').DataTable().row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#detallePagosTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#detallePagosTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatCP(row.data())).show();
                tr.addClass('shown');
            }
        });
        /*Formatting function forl row details - modify as you need*/
        function formatCP (rowData) {
            var divTipo = $('<div/>', { class:'tipo', id:"detallePagos" });
            var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOrdenCompra });
            divTipo.append(divDetalles);
            divDetalles.load("pages/compras/detalleOC/detalleOC.php");
            return divTipo;
        }
    });
</script>