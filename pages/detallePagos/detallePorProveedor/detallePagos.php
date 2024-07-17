<script src="pages/detallePagos/detallePorProveedor/detallePorProveedorScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-12 table-responsive">
        <table id="detalleByProveedorTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Teléfono</th>
                    <th>Representante</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTableDetalleProveedor();
        
        //Add event listener for opening and closing details
        $('#detalleByProveedorTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#detalleByProveedorTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                //This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                //Open this row
                if ($('#detalleByProveedorTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#detalleByProveedorTable').DataTable().row('.shown').node()).click();
                }
                row.child( detallePagosByProveedor(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    
    function detallePagosByProveedor (rowData) {
        var div = $('<div/>', { class:'row detalles2', id:rowData.IdProveedor });
        div.load("pages/detallePagos/detallePorProveedor/detallePagosByProveedor/detalles.php");
        return div;
    }
</script>