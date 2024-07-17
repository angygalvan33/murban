<div id="nuevaUbicacionModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ubicación</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formUbicacion" role="form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="nombreUbicacion" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="descripcionUbicacion" class="form-control" rows="2" style="resize:none" maxlength="200" required=""></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioUbicacion($('#accion').val(), $('#idRegistro').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $("#formUbicacion").validate({ });
    });
    
    function validarFormularioUbicacion(accion, idRegistro) {
        if ($("#formUbicacion").valid()) {
            guardarUbicacion(accion, idRegistro);
            $('#nuevaUbicacionModal').modal('hide');
        }
    }
</script>