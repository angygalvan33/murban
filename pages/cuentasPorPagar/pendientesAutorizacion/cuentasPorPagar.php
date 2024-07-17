<script src="pages/cuentasPorPagar/pendientesAutorizacion/pendientesAutorizarScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="cxpPendientesAutorizar" class="table table-hover">
        <thead>
            <tr>
                <th>Folio OC</th>
                <th>Proveedor</th>
                <th>Folio de factura</th>
                <th>Fecha de facturación</th>
                <th>OC (MXN)</th>
                <th>Monto (MXN)</th>
                <th>Días de crédito restantes</th>
                <th>Tipo</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoAutorizar = <?php echo json_encode($permisos->acceso("67108864", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        loadDataTablePendientesAutorizar(permisoAutorizar);
        
        $('#cxpPendientesAutorizar').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#cxpPendientesAutorizar').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#cxpPendientesAutorizar').DataTable().row('.shown').length) {
                    $('.details-control', $('#cxpPendientesAutorizar').DataTable().row('.shown').node()).click();
                }
                row.child(formatCP(row.data())).show();
                tr.addClass('shown');
            }
        });
        
        $('#cxpPendientesAutorizar').on('click', 'button', function () {
            var data = $("#cxpPendientesAutorizar").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "pAutorizar":
                    autorizar(data.IdOC, 1, data.IdProveedor, data.Deuda);
                    $('#cxpPendientesAutorizar').DataTable().ajax.reload();
                break;
                case "pNoAutorizar":
                    proponer(data.IdOC, 0, data.IdProveedor, data.Deuda);
                    $('#cxpPendientesAutorizar').DataTable().ajax.reload();
                break;
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatCP (rowData) {
        var divTipo = $('<div/>', { class:'tipo', id:"cuentasPorPagarPendientesAutorizar" });
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOC });
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>