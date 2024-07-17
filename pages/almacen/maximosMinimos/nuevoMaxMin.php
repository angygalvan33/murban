<script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>

<div id="nuevaMaxMinModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Máximos y mínimos</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accionMaxMin">
                <form id="formMaxMin" role="form">
                    <div class="form-group">
                        <input type="hidden" name="idMaxMin" id="idMaxMin">
                        <label>Material</label>
                        <br/>
                        <select name="idMaterial" id="mm_material" class="form-control" required="" style="width:80% !important">
                        </select>
                        <label style="margin-left: 15px">Alerta</label>
                        <input type="checkbox" class='icheckbox_flat-green' id="mm_alerta" name="alerta">
                    </div>
                    <div class="form-group" style="padding: 0px 0px !important; margin: 0px !important">
                        <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
                            <legend class="scheduler-border">Cantidad</legend>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>Min</label>
                                    <input type="text" name="min" id="mm_min" class="form-control mm_cantidad" required="" value="0">
                                </div>
                                <div class="col-md-6">
                                    <label>Max</label>
                                    <input type="text" name="max" id="mm_max" class="form-control mm_cantidad" required="" value="0">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioMaxMin($('#accionMaxMin').val(), $('#idMaxMin').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        llenaMateriales();
        
        $("#formMaxMin").validate( {
            rules: {
                mm_min: { number: true },
                mm_max: { number: true }
            }
	    });
        
        $(".mm_cantidad").inputmask(
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
    
    function validarFormularioMaxMin(accion, idRegistro) {
        if ($("#formMaxMin").valid()) {
            guardarMaxMin(accion, idRegistro);
            $('#nuevaMaxMinModal').modal('hide');
        }
    }
</script>