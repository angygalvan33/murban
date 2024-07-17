<script src="pages/compras/emitidas/detalleOCScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <table id="detalleOCTableEmitidas" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad</th>
                <th>Material</th>
                <th>Precio unitario (MXN)</th>
                <th>Costo total (MXN)</th>
                <th>Proyecto</th>
                <th>Solicita</th>
                <th>Archivo</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<div id="eliminaPModal" class="modal modal-danger fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminar partida de OC</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas eliminar la partida de la OC?</p>
                <input type="hidden" id="idOrdenCompraE">
                <input type="hidden" id="idDetalleOCE">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal" onclick="eliminarPartida($('#idDetalleOCE').val(), $('#idOrdenCompraE').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaDetalleOCTableEmitidas();

        $('#detalleOCTableEmitidas').on('click', 'button', function () {
            var data = $("#detalleOCTableEmitidas").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "em_eliminar":
                    $("#idOrdenCompraE").val(data.IdOrdenCompra);
                    $("#idDetalleOCE").val(data.IdDetalleOrdenCompra);
                    $("#eliminaPModal").modal("show");
                break;
            }
        });
    });
</script>