<script src="pages/cuentasPorPagar/general/cuentasGeneralScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="cxpTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio OC</th>
                <th>Proveedor</th>
                <th>Folio de factura</th>
                <th>Fecha de facturación</th>
                <th>Monto (MXN)</th>
                <th>Días de crédito restantes</th>
                <th>Proponer</th>
                <th>Tipo</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
        </tfoot>
    </table>
</div>
<div id="autorizarModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
                <input type="hidden" id="idOCGeneral">
                <input type="hidden" id="tipoAutorizacion">
                <input type="hidden" id="edoOC">
                <input type="hidden" id="idProveedorOCGeneral">
                <input type="hidden" id="ValorFacturaOCGeneral">
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" onclick="cancelarAutorizarOC()">Cancelar</button>
                <button id="autorizarOrden" type="button" class="btn btn-outline" data-dismiss="modal" onclick="autorizarOC($('#idOCGeneral').val(), $('#edoOC').val(), $('#tipoAutorizacion').val(), $('#idProveedorOCGeneral').val(), $('#ValorFacturaOCGeneral').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<div id="cancelarModal" class="modal modal-danger fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cancelar OC</h4>
                <input type="hidden" id="cancelar_idOC">
            </div>
            <div class="modal-body">
                <label>Motivo:</label>
                <textarea id="motivoCancelacion" class="form-control" rows="5" style="resize:none"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button id="cancelarOC" type="button" class="btn btn-outline" data-dismiss="modal" onclick="cancelarOC($('#cancelar_idOC').val(), $('#motivoCancelacion').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoProponer = <?php echo json_encode($permisos->acceso("33554432", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var permisoAutorizar = <?php echo json_encode($permisos->acceso("67108864", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var permisoPagar = <?php echo json_encode($permisos->acceso("134217728", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        
        loadDataTableGeneral(permisoProponer, permisoAutorizar, permisoPagar);
        
        $('#cxpTable').on('click', 'button', function () {
            var data = $("#cxpTable").DataTable().row( $(this).parents('tr') ).data();

            switch ($(this).attr("id")) {
                case "pagar":
                    getAbonosAnticipo(data.IdOC, data.ValorFactura, data.Deuda);
                    $("#idOC").val(data.IdOC);
                    $("#pValorFactura").text(data.ValorFactura);
                    $("#pAnticipo").text(data.Anticipo);
                    $("#pDeuda").text(data.Deuda);
                    $("#pagarForm").validate().resetForm();
                    $("#pagarForm :input").removeClass('error');
                    $("input[type=radio][name=tpago][value='1']").prop("checked", true);
                    $('#fechaPago').val(moment().format("DD/MM/YYYY"));
                    $('#fechaPagoH').val(moment().format("DD/MM/YYYY"));
                    habilitaCantidad("1");
                    $("#metodoPago").val("");
                    $("#cantidad").val(data.Deuda);
                    $("#cantidadFact").val("");
                    $("#pagarModal").modal("show");
                break;
                case "cancelar":
                    $("#cancelar_idOC").val(data.IdOC);
                    $("#motivoCancelacion").val("");
                    $("#cancelarModal").modal("show");
                break;
            }
        });
        
        $('#cxpTable tbody').on('click', 'input.proponer', function(event, state) {
            var data = $("#cxpTable").DataTable().row($(this).parents('tr')).data();
            
            $("#idOCGeneral").val(data.IdOC);
            $("#idProveedorOCGeneral").val(data.IdProveedor);
            $("#ValorFacturaOCGeneral").val(data.Deuda);
            $("#tipoAutorizacion").val(1);

            var pregunta = "";

            if ($(this).prop('checked')) {
                pregunta = "¿Estás seguro de que deseas PROPONER la órden de compra?";
                $("#edoOC").val(1);
            }
            else {
                pregunta = "¿Estás seguro de que deseas quitar PROPUESTO a la órden de compra?";
                $("#edoOC").val(0);
            }
            
            autorizarOC($('#idOCGeneral').val(), $('#edoOC').val(), $('#tipoAutorizacion').val(), $('#idProveedorOCGeneral').val(), $('#ValorFacturaOCGeneral').val());
        });
        
        $('#cxpTable tbody').on('click', 'input.autorizar', function(event, state) {
            var data = $("#cxpTable").DataTable().row($(this).parents('tr')).data();
            
            $("#idOCGeneral").val(data.IdOC);
            $("#idProveedorOCGeneral").val(data.IdProveedor);
            $("#ValorFacturaOCGeneral").val(data.Deuda);
            $("#tipoAutorizacion").val(2);

            var pregunta = "";
            
            if ($(this).prop('checked')) {
                pregunta = "¿Estás seguro de que deseas AUTORIZAR la órden de compra?";
                $("#edoOC").val(1);
            }
            else {
                pregunta = "¿Estás seguro de que deseas quitar AUTORIZACIÓN a la órden de compra?";
                $("#edoOC").val(0);
            }

            if ($('#edoOC').val() == 1) {
                if ($(this).parents('tr').find('input.proponer').prop('checked') == false) {
                    autorizarOC($('#idOCGeneral').val(), $('#edoOC').val(), 1, $('#idProveedorOCGeneral').val(), $('#ValorFacturaOCGeneral').val());
                }

                $(this).parents('tr').find('input.proponer').prop('checked', true);
            }
            
            autorizarOC($('#idOCGeneral').val(), $('#edoOC').val(), $('#tipoAutorizacion').val(), $('#idProveedorOCGeneral').val(), $('#ValorFacturaOCGeneral').val());
        });
        
        $('#cxpTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#cxpTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#cxpTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#cxpTable').DataTable().row('.shown').node()).click();
                }

                row.child(formatCP(row.data())).show();
                tr.addClass('shown');
            }
        });
        /*Formatting function forl row details - modify as you need*/
        function formatCP (rowData) {
            var divTipo = $('<div/>', { class:'tipo', id:"cuentasPorPagar" });
            var divDetalles = $('<div/>', {class:'row detalles', id:rowData.IdOC });

            divTipo.append(divDetalles);
            divDetalles.load("pages/compras/detalleOC/detalleOC.php");
            return divTipo;
        }
    });
</script>