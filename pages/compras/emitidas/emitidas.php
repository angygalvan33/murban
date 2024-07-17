<script src="pages/compras/emitidas/emitidasScript.js" type="text/javascript"></script>
<link href="pages/compras/ocompraStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12 table-responsive">
    <table id="emitidasTable" class="table table-hover" style="width:100% !important;">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Total OC</th>
                <th>Descripción</th>
                <th>Genera</th>
                <th>Autoriza</th>
                <th>PDF</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<!--warning modal-->
<div id="recibirModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recepción de OC</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas marcar como recibida la OC?</p>
                <input type="hidden" id="recIdOC">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal" onclick="recibirOC($('#recIdOC').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<!--warning modal-->
<div id="preciosModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar precios de OC</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas actualizar todos los precios de la OC?</p>
                <input type="hidden" id="preIdOC">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal" onclick="actualizarPreciosOC($('#preIdOC').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        var permisoFacturar = <?php echo json_encode($permisos->acceso("262144", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        loadDataTableEmitidas(permisoFacturar);

        $('#emitidasTable').on('click', 'button', function () {
            var data = $("#emitidasTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "em_cancelar":
                    $("#idOrdenCompra").val(data.IdOrdenCompra);
                    $("#cancelarModal").modal("show");
                break;
                case "em_recibir":
                    $("#recIdOC").val(data.IdOrdenCompra);
                    $("#recibirModal").modal("show");
                break;
                case "em_precios":
                    $("#preIdOC").val(data.IdOrdenCompra);
                    $("#preciosModal").modal("show");
                break;
            }
        });

        $('#emitidasTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#emitidasTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#emitidasTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#emitidasTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatEmitida(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatEmitida (rowData) {
        var divTipo = $('<div/>', {class:'tipo', id:"Emitidas"});
        var divDetalles = $('<div/>', {class:'row detalles', id:rowData.IdOrdenCompra});
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/emitidas/detalleOC.php");
        return divTipo;
    }
</script>