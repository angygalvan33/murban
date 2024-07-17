<script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>

<div id="nuevaContraModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cambiar Contraseña</h4>
            </div>
            
            <div class="modal-body">
                <input type="hidden" id="username">
                
                <form id="formContra" role="form">
                    <div class="form-group">
                        <label>Contraseña anterior</label>
                        <input type="password" id="contraAnt" name="contraAnt" class="form-control" required>
                        <label>Nueva contraseña</label>
                        <input type="password" id="contraNueva" name="contraNueva" minlength="6" class="form-control" required>
                        <label>Confirmar nueva contraseña</label>
                        <input type="password" id="contraConf" name="contraConf" minlength="6" class="form-control" required>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormulario($('#username').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
      
        $("#formContra").validate({
           
	});
        
    });
    
    function validarFormulario(username)
    {
        if($("#formContra").valid())
        {
            guardarContra(username);
            $('#nuevaContraModal').modal('hide');
        }
    }
</script>