<div id="nuevaCajaChicaModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Caja chica</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formCC" role="form">
                     <div class="form-group usuarioDiv">
                        <label>Usuario</label>
                        <br>
                        <select id="usuario" name="usuario" class="form-control usuarios" required="" ></select>
                        <input type="hidden" name="usuarioValue" id="usuarioValue">
                    </div>
                    <div class="form-group">
                        <label>Presupuesto inicial (MXN)</label>
                        <input type="text" id="presupuesto" name="presupuesto" class="form-control" required="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormulario(1)">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="rembolsarModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reembolsar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idRegistro">
                <form id="formRCCh" role="form">
                    <div class="form-group">
                        <label> El total desde el último corte al día de hoy es: $<span id="totalReembolsar"></span></label>
                        <label>Total (MXN)</label>
                        <input type="text" id="totalRembolso" name="totalRembolso" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label> Descripción </label>
                        <textarea id="descripcionRembolso" name="descripcionRembolso" class="form-control" required=""></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormulario(2)">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!--warning modal-->
<div id="edoCajaChicaModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Abrir / Cerrar</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas cambiar el estado de la Caja Chica?</p>
                <input type="hidden" id="idRegistro">
                <input type="hidden" id="tipo">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                <button id="cambiarEdoCajaChica" type="button" class="btn btn-outline" data-dismiss="modal" onclick="cambiarEdoCajaChica($('#idRegistro').val(), $('#tipo').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        autoCompleteUsuarios($('.usuarios'), 'NOT IN');
        $("#usuario").change(function() {
            $("#usuarioValue").val($("#usuario").val());
	    });
        
        $("#formCC").validate( {
            rules: {
                presupuesto: { number: true }
            }
	    });
        
        $("#formRCCh").validate( {
            rules: {
                totalRembolso: { number: true }
            }
	    });
        
        $("#presupuesto").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $("#totalRembolso").inputmask(
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
    
    function validarFormulario(tipo) {
        switch (tipo) {
            case 1:
                if($("#formCC").valid()) {
                    guardarCajaChica($("#accion").val(), $("#idRegistro").val());
                    $('#nuevaCajaChicaModal').modal('hide');
                }
            break;
            case 2:
                if ($("#formRCCh").valid()) {
                    reembolsar($("#idRegistro").val(), $("#totalRembolso").val(), $("#descripcionRembolso").val());
                    $('#rembolsarModal').modal('hide');
                }
            break;
        }
    }
</script>