<script src="pages/obras/obraScript.js" type="text/javascript"></script>
<link href="pages/obras/obraStyles.css" rel="stylesheet" type="text/css"/>

<div id="nuevaObraModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Proyecto</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formObra" role="form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Proyecto</label>
                        <select name="tipoObra" id="tipoObra" class="form-control" required="">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Cliente</label>
                        <select name="ComboClientes" id="ComboClientes" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Folio OC</label>
                        <input type="text" id="ocFolio" name="ocFolio" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Monto OC</label>
                        <input type="text" id="ocMonto" name="ocMonto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Fecha entrega aproximada:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="fechaEntregaEstimada" required="">
                            </div>
                            <input type="hidden" id="fechaH">
                        </div>
                    <div class="form-group"></div>
                </form>
				<label>Foto:</label>
				<img id="foto_articulo" src="images/fotoparte.png" alt="img_parte" height="160px" width="200px">
                <input type='file' id="archivo"/>
				<input type="hidden" id="artFotoNombre"/>
				<br>
				<button class="btn btn-default" onclick="cambiarFotoArt()">Subir foto</button>
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
        llenaTiposObra();
        llenaClientes();
        
        var hoy = moment().format("DD/MM/YYYY");
        $("#fechaEntregaEstimada").datepicker( {
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });

        $('#fechaEntregaEstimada').val(hoy);

        $('#fechaH').val(hoy);
        
        $('#fechaEntregaEstimada').datepicker().on('changeDate', function (ev) {
            $('#fechaH').val($(this)[0].value);
        });

        $("#formObra").validate({ });

        $("#ocMonto").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

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
    });
    
    function validarFormulario(accion, idRegistro) {
        if ($("#formObra").valid()) {
            guardarObra(accion, idRegistro);
            $('#nuevaObraModal').modal('hide');
        }
    }
</script>