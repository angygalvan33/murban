<script src="pages/compras/sinAutorizacion/sinAutorizacionScript.js" type="text/javascript"></script>
<link href="pages/compras/ocompraStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12 table-responsive">
    <table id="sinAutTable" class="table table-hover">
        <thead>
            <tr>
                <th style="width: 5%">Folio</th>
                <th style="width: 10%">Fecha</th>
                <th style="width: 15%">Proveedor</th>
                <th style="width: 15%">Total OC</th>
                <th style="width: 30%">Descripción</th>
                <th style="width: 15%">Genera</th>
                <th style="width: 10% !important"></th>
            </tr>
        </thead>
    </table>
</div>
<input type="hidden" id="idOrdenCompra">
<div id="autorizarModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Autorización</h4>
                <input type="hidden" id="tipoAutorizacion">
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas autorizar la Orden de Compra?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button id="autorizarOrden" type="button" class="btn btn-outline" data-dismiss="modal" onclick="autorizarOC($('#idOrdenCompra').val(), <?php echo $usuario->getIdFromUsername($_SESSION['username']); ?>, $('#tipoAutorizacion').val())">Autorizar Orden</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoAutorizar = <?php echo json_encode($permisos->acceso("32768", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        loadDataTableSinAutorizacion(permisoAutorizar);
    });
    
    $('#sinAutTable').on('click', 'button', function () {
        var data = $("#sinAutTable").DataTable().row( $(this).parents('tr') ).data();
        
        switch ($(this).attr("id")) {
            case "ea_cancelar":
                $("#idOrdenCompra").val(data.IdOrdenCompra);
                $("#cancelarModal").modal("show");
            break;
            case "ea_autorizar":
                $("#idOrdenCompra").val(data.IdOrdenCompra);
                $("#tipoAutorizacion").val(0);
                $("#autorizarModal").modal("show");
            break;
            case "ea_autorizarPagar":
                $("#idOrdenCompra").val(data.IdOrdenCompra);
                $("#tipoAutorizacion").val(1);
                $("#autorizarModal").modal("show");
            break;
        }
    });
    
    $('#sinAutTable').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = $('#sinAutTable').DataTable().row(tr);
        
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            if ($('#sinAutTable').DataTable().row('.shown').length) {
                $('.details-control', $('#sinAutTable').DataTable().row('.shown').node()).click();
            }
            row.child( formatEA(row.data()) ).show();
            tr.addClass('shown');
        }
    });
    /*Formatting function forl row details - modify as you need*/
    function formatEA (rowData) {
        var divTipo = $('<div/>', { class:'tipo', id:"SinAutorizacion" });
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOrdenCompra });
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>