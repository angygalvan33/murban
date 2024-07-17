<div id="pagarModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cobrar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="IdProyecto" id="IdProyecto">
                <div style="width: 60% !important; margin: auto">
                    <table class="table" id="cuenta"></table>
                </div>
                <div style="width: 70% !important; margin: auto">
                    <form id="pagarForm">
                        <div class="form-inline">
                            <div class="form-group" style="margin-right: 15px">
                                <input type="radio" name="tpago" id="liquidar" class='icheckbox_flat-green form-check-input' value="1" checked>
                                <label class="form-check-label" for="liquidar">
                                    Liquidar
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="radio" name="tpago" id="abonar" class='icheckbox_flat-green form-check-input' value="2">
                                <label class="form-check-label" for="abonar">
                                    Abonar
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="divMetodoPago" style="margin-top:15px">
                            <label>Método de cobro:</label>
                            <select id="metodoPago" name="metodoPago" class="form-control" required></select>
                        </div>
                        <div class="form-group" id="divMetodoPago">
                            <label>Cantidad (MXN):</label>
                            <input type="text" id="cantidad" name="cantidad" class="form-control" required disabled>
                            <label id="errorCantidad" style="display:none; color:red">La cantidad a cobrar es mayor a la deuda</label>
                        </div>
                        <div class="form-group" id="divMetodoPago">
                            <label>Concepto:</label>
                            <input type="text" id="concepto" name="concepto" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Fecha:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="fechaPago" required="">
                            </div>
                            <input type="hidden" id="fechaPagoH">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormulario($('#IdProyecto').val(), $('#metodoPago').val(), $('input[type=radio][name=tpago]').val(), $('#cantidad').val(), $('#concepto').val(), $('#pDeuda').text().replace(/\,/g, ''), $('#fechaPagoH').val())">Pagar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        llenaMetodoCobro();
        $("#pagarForm").validate( {
            rules:{
               cantidad: { number: true }
            }
	    });

        $("#cantidad").inputmask(
            "decimal", {
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $('input[type=radio][name=tpago]').change( function() {
            habilitaCantidad1(this.value);
        });
        
        var hoy = moment().format("DD/MM/YYYY");
        $('#fechaPago').datepicker( {
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });
        
        $('#fechaPago').val(hoy);
        
        $('#fechaPagoH').val(hoy);

        $('#fechaPago').datepicker().on('changeDate', function (ev) {
            $('#fechaPagoH').val($(this)[0].value);
        });
    });
    
    function validarFormulario(idObra, metodoPago, tpago, cantidad, concepto, deuda, fechaPago) {
        if ($("#pagarForm").valid()) {
            if (esCantidadMenorOIgualADeuda($("#cantidad").val().replace(/\,/g, ''), $("#pDeuda").text().replace(/\,/g, ''))) {
                $("#errorCantidad").css("display", "none");
                pagarObra(idObra, metodoPago, tpago, cantidad, concepto, deuda, fechaPago);
                $("#pagarModal").modal("hide");
            }
            else
                $("#errorCantidad").css("display", "block");
        }
    }
</script>