<script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>

<div id="facturarModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Facturación</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idRegistroEsperaFacturacion">
                <input type="hidden" id="tipoFactura">
                <form id="formFact" role="form">
                    <div class="form-group">
                        <label>Número de Factura</label>
                        <input type="text" id="factura" name="factura" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label>Valor de Factura</label>
                        <input type="text" id="valor_factura" name="valor_factura" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label>Folio:&nbsp;</label> <span id="folioInformativo"></span> &nbsp;&nbsp;
                        <label>Monto Obra: $ &nbsp;</label><span id="montoInformativo"></span>
                    </div>
                    <div style="margin-top: 10px">
                        <label style="margin-right: 10px">Fecha de facturación:</label>
                        <i class="fa fa-calendar"></i>
                        <input type="text" id="fecha_factura" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioFact($('#idRegistroEsperaFacturacion').val(), $('#factura').val(), $('#valor_factura').val(), $('#fecha_factura').val(), $('#tipoFactura').val())">Facturar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaFecha();

        $("#formFact").validate({ });
        
        $("#valor_factura").inputmask(
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
    
    function validarFormularioFact(idRegistroEsperaFacturacion, numFact, valorFact, fecha, tipoFactura) {
        if ($("#formFact").valid()) {
            facturar(idRegistroEsperaFacturacion, numFact, valorFact, fecha, tipoFactura);
            $('#facturarModal').modal('hide');
        }
    }
    
    function reiniciarFecha() {
        $('#fecha_factura').val("<?php echo date("Y-m-d"); ?>");
    }
    
    function inicializaFecha() {
        $('#fecha_factura').datepicker( {
            format: 'yyyy-mm-dd',
            opens: 'left',
            "locale": {
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "daysOfWeek": [
                    "Dom",
                    "Lun",
                    "Mar",
                    "Mié",
                    "Jue",
                    "Vie",
                    "Sáb"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            }
        },
        
        function(start, end, label) { });
    }
</script>