<div id="recibirModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recepción de material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idDetalleR">
                <input type="hidden" id="cantidadPR">
                <input type="hidden" id="tipoR">
                <input type="hidden" id="idPersonalR">
                <input type="hidden" id="idMaterialR">
                <form id="formRecepcion" role="form">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input id="cantidadR" name="cantidadR" class="form-control" required="">
                        <span id="errorCantidadPR" class="error" style="display:none; color: red">La cantidad recibida no puede ser mayor a la prestada</span>
                    </div>
                    <div class="form-group">
                        <label>Fecha:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="fechaR" required="" disabled="disabled">
                        </div>
                        <input type="hidden" id="fechaHR">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarRecepcionMaterial($('#idDetalleR').val(), $('#tipoR').val(), $('#idPersonalR').val(), $('#idMaterialR').val(), $('#cantidadR').val(), $('#fechaR').val(), $('#cantidadPR').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#formRecepcion").validate({ });
        
        $("#cantidadR").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        var hoy = moment().format("DD/MM/YYYY");

        $("#fechaR").datepicker( {
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });
        
        $('#fechaR').val(hoy);
        $('#fechaHR').val(hoy);

        $('#fechaR').datepicker().on('changeDate', function (ev) {
            $('#fechaHR').val($(this)[0].value);
        });
    });
    
    function validarRecepcionMaterial(idDetalle, tipo, idPersonal, idMaterial, cantidad, fecha, cantidadPR) {
        if ($("#formRecepcion").valid()) {
            if (parseFloat(cantidad.replace(/\,/g, '')) > parseFloat(cantidadPR.replace(/\,/g, '')))
                $("#errorCantidadPR").css('display', 'block');
            else {
                recibirMaterial(idDetalle, tipo, idPersonal, idMaterial, cantidad, fecha);
                $('#recibirModal').modal('hide');
            }
        }
    }
</script>