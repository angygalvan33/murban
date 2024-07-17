<script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>

<div id="editarUbicacionMaterialModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ubicación de material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formNuevaUbicacionMaterial" role="form">
                    <div class="form-group">
                        <label>Material</label>
                        <br/>
                        <select name="idMaterial" id="ubMat_material_edit" class="form-control" required="" style="width:100% !important">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ubicación actual</label>
                        <div>
                            <select name="idUbicaciona" id="ubMat_ubicaciona_edit" class="form-control" required="" style="width: 100% !important"></select>
                        </div>
                        <label>Cantidad en ubicación actual</label>
                        <div>
                            <input type="text" name="cantidadact" id="ubMat_cantidadact_edit" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nueva ubicación</label>
                        <div>
                            <select name="idUbicacionn" id="ubMat_ubicacionn_edit" class="form-control" required="" style="width: 100% !important">
                            </select>
                        </div>
                        <label>Cantidad para nueva ubicación</label>
                        <div>
                            <input type="text" name="cantidadnva" id="ubMat_cantidadnva_edit" class="form-control" required=""/>
                        </div>
                    </div>
                    <input type="hidden" id="idMaterialUbicEdit" name="idMaterialUbicEdit">
                    <input type="hidden" id="nombreMaterial" name="nombreMaterial">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioUbicacionMaterial($('#accion').val(), $('#idRegistro').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        llenaUbicaciones_ub($('#ubMat_material_edit'));

        $("#formNuevaUbicacionMaterial").validate({ });
    });
    
    function validarFormularioUbicacionMaterial(accion, idRegistro) {
        if ($("#formNuevaUbicacionMaterial").valid()) {
            guardarNuevaUbicacionMaterial(accion, idRegistro);
            $('#editarUbicacionMaterialModal').modal('hide');
        }
    }
</script>