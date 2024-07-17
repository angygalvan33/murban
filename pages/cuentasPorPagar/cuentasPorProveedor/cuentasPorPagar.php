<script src="pages/cuentasPorPagar/cuentasPorProveedor/cuentasPorProveedorScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-11 text-left">
        <button id="descargaCxPxP" type="button" class="btn btn-success" onclick="descargaCxPxP()">Descargar</button>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        <table id="cxpByProveedorTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Límite de crédito (MXN)</th>
                    <th>Deuda (MXN)</th>
                    <th>Total propuesto (MXN)</th>
                    <th>Total autorizado (MXN)</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    propuestoDetalle = 0;
    autorizadoDetalle = 0;
    
    $( document ).ready( function() {
        loadDataTableByProveedor();
        //Add event listener for opening and closing details
        $('#cxpByProveedorTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#cxpByProveedorTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                if ($('#cxpByProveedorTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#cxpByProveedorTable').DataTable().row('.shown').node()).click();
                }
                row.child( detallesCxPByProveedor(row.data()) ).show();
                tr.addClass('shown');
            }
        });
    });
    
    function detallesCxPByProveedor (rowData) {
        var div = $('<div/>', { class:'row detalles2', id:rowData.IdProveedor });
        div.load("pages/cuentasPorPagar/cuentasPorProveedor/detallesCxP/detallesCxP.php");
        return div;
    }

    function descargaCxPxP() {
        window.location.href = "./excel/reportes/reporteCxPxP.php";
    }
</script>