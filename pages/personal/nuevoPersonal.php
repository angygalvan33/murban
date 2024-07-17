<div id="nuevoPersonalModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Personal</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formPersonal" role="form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Fecha de ingreso</label>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <input type="text" class="form-control" id="fechaing" name="fechaing" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Departamento</label>
                        <select id="depto" name="depto" class="form-control depto" style="width:100% !important"></select>
                    </div>
                    <div class="form-group">
                        <label>Puesto</label>
                        <input type="text" id="puesto" name="puesto" class="form-control" maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Periodo de pago</label>
                        <p>
                            <label><input type="radio" id="semanal" name="periodo" value="0" checked>Semanal</label> &nbsp;
                            <label><input type="radio" id="quincenal" name="periodo" value="1">Quincenal</label>
                        </p>
                    </div>
                    <div class="form-group">
                        <label>Sueldo</label>
                        <input type="text" id="sueldo" name="sueldo" class="form-control" maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Fecha de nacimiento</label>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <input type="text" class="form-control" id="fechanac" name="fechanac" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>NSS</label>
                        <input type="text" id="nss" name="nss" class="form-control" maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Teléfono de emergencia</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioPersonal($('#accion').val(), $('#idRegistro').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        autoCompleteDepartamentos();

        $("#formPersonal").validate( {
            rules: { }
	    });

        $("#nuevoPersonalModal").validate( {
            rules: {
                sueldo: { number: true },
                nss: { number: true }
            }
        });

        $('[data-mask]').inputmask();
        
        $('#fechaing').datepicker( {
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $('#fechanac').datepicker( {
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $('#fechabaja').datepicker( {
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $("input[type='button']").click( function() {
            var radioValue = $("input[name='periodo']:checked").val();
        });
    });
    
    function validarFormularioPersonal(accion, idRegistro) {
        if ($("#formPersonal").valid()) {
            guardarPersonal(accion, idRegistro);
            $('#nuevoPersonalModal').modal('hide');
        }
    }
</script>