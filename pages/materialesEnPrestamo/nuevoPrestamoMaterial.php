<div id="nuevoPrestamoModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Prestamo/Resguardo de materiales</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="tipo">
                <form id="formPrestamo" role="form">
                    <div class="form-group">
                        <label>Material</label>
                        <br/>
                        <select name="idMaterial" id="idMaterial" class="form-control" required="" style="width:100% !important">
                        </select>
                        <span id="cantidadMaterialPrestamoInformativo">-</span>
                        <input type="hidden" name="nombreMaterial" id="nombreMaterial">
                    </div>
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input id="cantidad" name="cantidad" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label>Personal</label>
                        <br/>
                        <select name="idPersonal" id="idPersonal" class="form-control" required="" style="width:100% !important">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="2" style="resize:none" maxlength="200" required=""></textarea>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10 tipoPrestamo"> Material en:
                            <br/>
                            <div class="col-md-4">
                                <label>
                                    <input type="radio" name="tipoPrestamo" value="P" required class="minimal"> Préstamo
                                </label> 
                            </div>
                            <div class="col-md-4">
                                <label>
                                    <input type="radio" name="tipoPrestamo" value="R" required class="minimal"> Resguardo
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Fecha de salida:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="fecha" required="" disabled>
                        </div>
                        <input type="hidden" id="fechaH">
                    </div>
                    <div id="mostrarDias" class="form-group">
                        <label>No. de Días</label>
                        <input id="diasPrestamo" name="diasPrestamo" class="form-control" required="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarMaterialPrestamo($('#accion').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        llenaMaterialesEPrestamo();
        llenaEPersonal();
        
        $('input[type="radio"].minimal').iCheck( {
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_square-blue'
        });
        
        $('input[type=radio][name=tipoPrestamo]').on('ifChecked', function(event) {
            if ($(this).val() === "P")
                $("#mostrarDias").css("display", "block");
            else
                $("#mostrarDias").css("display", "none");
        });

        $("#formPrestamo").validate( {
            tipoPrestamo: {
                required: function() {
                    var c = $('input[type=radio][name=tipoPrestamo]').is(":checked");

                    if (!c)
                        $(".tipoPrestamo").css("border","1px solid red");
                    else
                        $(".tipoPrestamo").css("border","1px solid white");
                    return c;
                }
            }
	    });
        
        $("#cantidad").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        var hoy = moment().format("DD/MM/YYYY");

        $("#fecha").datepicker( {
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });
        
        $('#fecha').val(hoy);
        $('#fechaH').val(hoy);

        $('#fecha').datepicker().on('changeDate', function (ev) {
            $('#fechaH').val($(this)[0].value);
        });
        
        $("#idMaterial").change( function() {
            var dataU = $('#idMaterial').select2('data');

            if (dataU.length > 0){
                $("#nombreMaterial").val(dataU[0].text);
                getCantidadMaterialActual(dataU[0].id, dataU[0].text);
            }
            else
                $("#cantidadMaterialPrestamoInformativo").val("");
	    });
    });
    
    function validarMaterialPrestamo(accion) {
        if ($("#formPrestamo").valid()) {
            guardarMaterialPR(accion);
            $('#nuevoPrestamoModal').modal('hide');
        }
    }
</script>