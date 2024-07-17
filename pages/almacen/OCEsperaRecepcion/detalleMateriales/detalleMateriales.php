<script src="pages/almacen/OCEsperaRecepcion/detalleMateriales/detalleMaterialesScript.js" type="text/javascript"></script>

<div class="col-md-10"> </div>
<div class="col-md-2" style="margin-bottom: 10px !important">
    <button type="button" class="btn btn-success btn-flat btn-block" onclick="recibirTodoModal()"><i class="fa fa-angle-double-right"></i>&nbsp;Recibir todo</button>
</div>

<div class="col-md-12">
    <table id="detalleOCRecepcionTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad Solicitada</th>
                <th>Cantidad Recibida</th>
                <th>Material</th>
                <th>Proyecto</th>
                <th>Solicita</th>
                <th>Archivo</th>
                <th>Ubicación</th>
                <th>Fecha de Proveedor</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<div id="recibirTodoModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recepción de materiales</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas hacer recepción de todos los materiales?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button id="recepcionTodo" type="button" class="btn btn-outline" data-dismiss="modal" onclick="recibirTodo($('.detalles').attr('id'))">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div id="editarMatxObraModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recibir</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="iiMatxObra_idOC">
                <input type="hidden" id="iiMatxObra_idObra">
                <input type="hidden" id="iiMatxObra_idMaterial">
                <input type="hidden" id="iiMatxObra_cantidadFaltante">
                <input type="hidden" id="iiMatxObra_idProveedor">
                <input type="hidden" id="iiMatxObra_idDetalleOC">
                <input type="hidden" id="iiMatxObra_nombreMaterial">
                <input type="hidden" id="iiMatxObra_precioUnitario">
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
                <button type="button" class="btn btn-primary" onclick="recibirMatxObra()">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaDetalleMaterialesTable(); 
        
        $('#detalleOCRecepcionTable').on('click', 'button', function () {
            var data = $("#detalleOCRecepcionTable").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "recibirMaterial":
                    $("#iicantRecibida").val("");
                    $("#errorCantidadRecibida").css("display","none");
                    $("#iiMatxObra_idOC").val(data.IdOrdenCompra);
                    $("#iiMatxObra_idObra").val(data.IdObra);
                    $("#iiMatxObra_idMaterial").val(data.IdMaterial);
                    $("#iiMatxObra_cantidadFaltante").val(parseFloat(data.Cantidad)-parseFloat(data.Recibido));
                    $("#iiMatxObra_idProveedor").val(data.IdProveedor);
                    $("#iiMatxObra_idDetalleOC").val(data.IdDetalleOrdenCompra);
                    $("#iiMatxObra_nombreMaterial").val(data.Nombre);
                    $("#iiMatxObra_precioUnitario").val(data.PrecioUnitario);
                    $("#editarMatxObraModal").modal("show");
                break;
            }
        });
    });
    
    function recibirMatxObra() {
        if ($("#formEditMatxObra").valid()) {
            var cantidadFaltante = $('#iiMatxObra_cantidadFaltante').val();
            //la cantidad a recibir no debe ser mayor a la faltante 
            if (parseFloat($("#iicantRecibida").val()) > parseFloat(cantidadFaltante))
                $("#errorCantidadRecibida").css("display","block");
            else {
                recibirMaterialAlmacen($("#iiMatxObra_idObra").val(), $("#iiMatxObra_idMaterial").val(), $("#iicantRecibida").val(), $(".detalles").attr("precioUnitario"), $("#iiMatxObra_idProveedor").val(), $("#iiMatxObra_idDetalleOC").val(), $("#iiMatxObra_idOC").val(), $("#iiMatxObra_nombreMaterial").val(), $("#iiMatxObra_precioUnitario").val());
                $("#editarMatxObraModal").modal("hide");
            }
        }
    }

    function recibirTodoModal() {
       $("#recibirTodoModal").modal("show");
    }
</script>