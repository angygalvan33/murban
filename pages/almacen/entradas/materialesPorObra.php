<table id="matxObraTabla" class="table table-hover">
    <thead class="encabezadoTabla">
        <tr>
            <th>Proyecto</th>
            <th>Cantidad solicitada</th>
            <th>Cantidad recibida</th>
            <th></th>
        </tr>
    </thead>
</table>

<div id="editarMatxObraModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recibir</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="iiMatxObra_idObra">
                <input type="hidden" id="iiMatxObra_cantidadFaltante">
                <input type="hidden" id="iiMatxObra_idProveedor">
                <input type="hidden" id="iiMatxObra_idDetalleOC">
                <form id="formEditMatxObra" role="form">
                    <div class="input-group">
                        <label>Cantidad</label>
                        <input type="text" id="iicantRecibida" name="iicantRecibida" class="form-control iicantidad" required>
                        <label id="errorCantidadRecibida" style="display:none; color:red">La cantidad a recibir no debe ser mayor a la faltante.</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="recibirMatxObra($('#iiMatxObra_idObra').val(), $('#iiMatxObra_cantidadFaltante').val(), $('#iiMatxObra_idProveedor').val(), $('#iiMatxObra_idDetalleOC').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaMaterialesPorObraTabla();
        
        $('#matxObraTabla').on('click', 'button', function () {
            var data = $("#matxObraTabla").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "iiMatxObra":
                    $("#iicantRecibida").val("");
                    $("#errorCantidadRecibida").css("display", "none");
                    $("#iiMatxObra_idObra").val(data.IdObra);
                    $("#iiMatxObra_cantidadFaltante").val(parseFloat(data.Cantidad) - parseFloat(data.Recibido));
                    $("#iiMatxObra_idProveedor").val(data.IdProveedor);
                    $("#iiMatxObra_idDetalleOC").val(data.IdDetalleOrdenCompra);
                    $("#editarMatxObraModal").modal("show");
                break;
            }
        });
        
        $("#iicantRecibida").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );
    });
    
    function recibirMatxObra(idObra, cantidadFaltante, idProveedor, idDetalleOC) {
        if ($("#formEditMatxObra").valid()) {
            //la cantidad a recibir no debe ser mayor a la faltante 
            if (parseFloat($("#iicantRecibida").val()) > parseFloat(cantidadFaltante))
                $("#errorCantidadRecibida").css("display","block");
            else {
                recibirMaterialAlmacen(idObra, $(".detalles").attr("id"), $("#iicantRecibida").val(), $(".detalles").attr("nombreMaterial"), $(".detalles").attr("precioUnitario"), idProveedor, idDetalleOC);
                $("#editarMatxObraModal").modal("hide");
            }
        }
    }
</script>