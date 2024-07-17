<script src="pages/compras/canceladas/canceladasScript.js" type="text/javascript"></script>
<link href="pages/compras/ocompraStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12 table-responsive">
    <table id="canceladasTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Total OC</th>
                <th>Descripción</th>
                <th>Genera</th>
                <th>Motivo</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        loadDataTableCanceladas();
    
        $('#canceladasTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#canceladasTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#canceladasTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#canceladasTable').DataTable().row('.shown').node()).click();
                }
                row.child( formatCanceladas(row.data()) ).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatCanceladas (rowData) {
        var divTipo = $('<div/>', { class:'tipo', id:"Facturadas" });
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOrdenCompra });
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>