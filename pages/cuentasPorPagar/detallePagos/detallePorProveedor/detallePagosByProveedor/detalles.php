<script src="pages/detallePagos/detallePorProveedor/detallePagosByProveedor/detallesScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <table id="detalleTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio de Factura</th>
                <th>Tipo de Pago</th>
                <th>Método de Pago</th>
                <th>Monto (mxn)</th>
                <th>Fecha de Pago</th>
            </tr>
        </thead>
        <tfoot></tfoot>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTableDetalle();
        
        $('#detalleTable').on('click', 'td.details-control2', function () {
            var tr = $(this).closest('tr');
            var row = $('#detalleTable').DataTable().row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#detalleTable').DataTable().row('.shown').length) {
                    $('.details-control2', $('#detalleTable').DataTable().row('.shown').node()).click();
                }

                row.child(formatCP(row.data())).show();
                tr.addClass('shown');
            }
        });
        /*Formatting function forl row details - modify as you need*/
        function formatCP (rowData) {
            var divTipo = $('<div/>', { class:'tipo', id:"detallePagosProveedor" });
            var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOrdenCompra });
            divTipo.append(divDetalles);
            divDetalles.load("pages/compras/detalleOC/detalleOC.php");
            return divTipo;
        }
    });
</script>