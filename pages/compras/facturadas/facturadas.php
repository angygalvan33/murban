<script src="pages/compras/facturadas/facturadasScript.js" type="text/javascript"></script>
<link href="pages/compras/ocompraStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12">
    <table id="facturadasTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Total OC</th>
                <th>¿Pagada?</th>
                <th>PDF</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoPresupuestos = <?php echo json_encode($permisos->acceso("2097152", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        loadDataTableFacturadas(permisoPresupuestos);

        $('#facturadasTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#facturadasTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#facturadasTable').DataTable().row( '.shown' ).length) {
                    $('.details-control', $('#facturadasTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatFacturadas(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatFacturadas (rowData) {
        var divTipo = $('<div/>', {class:'tipo', id:"Facturadas"});
        var divDetalles = $('<div/>', {class:'row detalles', id:rowData.IdOrdenCompra});
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>