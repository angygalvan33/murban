<script src="pages/compras/requisicionesEspeciales/NuevoMaterial/materialScript.js" type="text/javascript"></script>

<div id="nuevoMatModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formMat" role="form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-control" maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Categoría</label>
                        <select name="idCategoria" id="idCategoria" class="form-control idCategoriaNM" required style="width: 100%"></select>
                    </div>
                    <div class="form-group" style="padding: 0px 0px !important; margin: 0px !important">
                        <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
                            <legend class="scheduler-border">Medida</legend>
                            <div class="form-group">
                                <div class="col-md-6" style="padding-bottom: 10px !important">
                                    <label>Tipo de medida</label>
                                    <select name="tipoMedida" id="tipoMedida" class="form-control" required="">
                                        </select>
                                </div>
                                <div class="col-md-12" style="padding: 0px !important">
                                    <div class="form-group" id="tiposDinamicos">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormulario($('#accion').val(), $('#idRegistro').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        llenaMedidas();
        llenaCategorias();
        //en el cambio de tipo de medida
        $("#tipoMedida").change( function() {
            getJsonMedidas($(this).val());
        });
        
        $("#formMat").validate({ });
        
        $.validator.addClassRules('inputDinamico', {
            required: true,
            number: true,
            min: 0
        });
    });
    
    function validarFormulario(accion, idRegistro) {
        if ($("#formMat").valid()) {
            guardarMaterial(accion, idRegistro);
            $('#nuevoMatModal').modal('hide');
        }
    }
</script>