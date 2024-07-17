<div id="nuevoProvModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Proveedor</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formProv" role="form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <select id="nombre" name="nombre" class="form-control nombre" required="" maxlength="250" style="width:100% !important"></select>
                        <!--<input type="text" id="nombre" name="nombre" class="form-control" required maxlength="250">-->
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-control" maxlength="500">
                    </div>
                    <div class="form-group">
                        <label>Representante</label>
                        <input type="text" id="representante" name="representante" class="form-control" maxlength="250">
                    </div>
                    <div>
                        <div class="form-group col-md-5" style="padding-left: 0px !important; padding-right: 0px !important">
                            <label>Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                        </div>
                        <div class="form-group col-md-1" style="margin: 0px">
                        </div>
                        <div class="form-group col-md-6" style="padding-left: 0px !important; padding-right: 0px !important">
                            <label>Correo electrónico</label>
                            <input type="text" id="email" name="email" class="form-control" maxlength="250">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>RFC</label>
                        <input type="text" id="rfc" name="rfc" class="form-control" maxlength="50">
                    </div>
                    <div class="form-group" style="padding: 0px 0px !important; margin: 0px !important">
                        <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
                            <legend class="scheduler-border">Crédito</legend>
                            <div class="form-group">
                                <div class="col-md-12 monedas" style="padding:0px 0px !important">
                                    <div class="col-md-4" style="padding-left:0px !important;">
                                        <label>Días de crédito</label>
                                        <input type="text" id="diasCredito" name="diasCredito" class="form-control">
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-7" style="padding-left:0px !important;">
                                        <label>Límite de crédito (MXN)</label>
                                        <input type="text" id="limiteCredito" name="limiteCredito" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioNP($('#accion').val(), $('#idRegistro').val(), $('#nombre').find('option:selected').text())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        autoCompleteMaterialesInventario();
        $("#formProv").validate( {
            rules: {
                email: { email: true },
                diasCredito: { number: true },
                limiteCredito: { number: true }
            }
	    });
        
        $('[data-mask]').inputmask();

        $("#limiteCredito").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $("#diasCredito").inputmask(
            "integer", {
                allowMinus: false,
                allowPlus: false,
            }
        );
    });
    
    function validarFormularioNP(accion, idRegistro, nombre) {
        if ($("#formProv").valid()) {
            guardarProveedor(accion, idRegistro, nombre);
            $('#nuevoProvModal').modal('hide');
        }
    }
</script>