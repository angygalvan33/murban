<script src="pages/material/materialScript.js" type="text/javascript"></script>
<link href="pages/material/materialStyles.css" rel="stylesheet" type="text/css"/>

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
                        <label>Clave</label>
                        <input type="text" id="clave" name="clave" class="form-control" maxlength="50">
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
                                <div class="col-md-6">
                                    <label class="hidden" id="labUnidad" name="labUnidad">Unidad</label>
                                    <select class="form-control hidden" id="comboLongitud" name="comboLongitud">
                                        <option value="long-cm-100">centímetros</option>
                                        <option value="long-m-1">metros</option>
                                        <option value="long-pulg-39.37">pulgadas</option>
                                        <option value="long-pies-3.281">pies</option>
                                    </select>
                                    <select class="form-control hidden" id="comboPeso" name="comboPeso">
                                        <option value="peso-gr-100">gramos</option>
                                        <option value="peso-kg-1">kilogramos</option>
                                    </select>
                                </div>
                                <div class="col-md-12" style="padding: 0px !important">
                                    <div class="form-group" id="tiposDinamicos"></div>
                                </div>
                            </div>
                        </fieldset>
						<br>
                        <fieldset class="scheduler-border" style="margin-bottom: 0px !important;display:none" id="pesoespecificozona">
					        <div class="col-md-6" >
                                <label>Peso de la pieza</label>
                                <input type="text" id="pesopieza" name="pesopieza"  class="form-control" value="0">
					        </div>
						    <div class="col-md-6" style="margin-top:10px"></div>
                        </fieldset>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btn_guardarMat" class="btn btn-primary" onclick="validarFormulario($('#accion').val(), $('#idRegistro').val())">Aceptar</button>
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
            muestraComboUnidad($(this).val());
			getJsonMedidas($(this).val());

            if ($(this).val() == 4) {
				muestrazonapeso($(this).val());
			}
			else if ($(this).val() == 6) {
				muestrazonapeso($(this).val());
			}
			else {
				$("#pesoespecificozona").hide();
			}
        });

		$("#comboLongitud").change( function() {
    		getJsonMedidas($("#tipoMedida").val());
        });

		$("#comboPeso").change( function() {
    		getJsonMedidas($("#tipoMedida").val());
        });

        $("#formMat").validate({ });

        $.validator.addClassRules('inputDinamico', {
            required: true,
            number: true,
            min: 0
        });
    });
	
	function calcularpeso() {
	    var fttometro = 0.3048;
        var intometro = 0.0254;
        var cmtometro = .01;
        var mmtometro = .001;
        var aalto = Number($('input[name="Alto"]').val());
        var aancho = Number($('input[name="Ancho"]').val());
        var alargo = Number($('input[name="Largo"]').val());
        var afactor = 1;
		var apesoespecifico = Number($("#pesoespecifico").val());

		if ($("#comboLongitud").val() == 'long-cm-100')
			afactor = cmtometro;
		else if ($("#comboLongitud").val() == 'long-pulg-39')
            afactor = intometro;
		else if ($("#comboLongitud").val() == 'long-pies-3.281')
            afactor = fttometro;

		aalto = aalto * mmtometro;
		aancho = aancho * afactor;
		alargo = alargo * afactor;
		var aarea = aalto * aancho * alargo;
		var apesopieza = apesoespecifico * aarea;
		$("#pesopieza").val(apesopieza.toFixed(2));
	}
	
	function muestrazonapeso(amedida_) {
		if (amedida_ == 4) {
			$("#pesoespecificozona").show();
            $("#pesoespecifico").show();
            $("#lblpesoespecifico").show();
			$("#btncalularpeso").show();
        }
		else if(amedida_ == 6) {
			$("#pesoespecificozona").show();
			$("#pesoespecifico").hide();
			$("#lblpesoespecifico").hide();
			$("#btncalularpeso").hide();
        }
	}
    
    function validarFormulario(accion, idRegistro) {
        if ($("#formMat").valid()) {
            guardarMaterial(accion, idRegistro);
            $('#nuevoMatModal').modal('hide');
        }
    }
    
    function muestraComboUnidad(idTipoMedida) {
        $("#labUnidad").addClass("hidden");
        $("#comboLongitud").addClass("hidden");
        $("#comboLongitud").val($("#comboLongitud option:first").val());
        $("#comboPeso").addClass("hidden");
        $("#comboPeso").val($("#comboPeso option:first").val());

        if (idTipoMedida == 4 || idTipoMedida == 6) {
            $("#labUnidad").removeClass("hidden");
            $("#comboLongitud").removeClass("hidden");
        }
        else if (idTipoMedida == 3) {
            $("#labUnidad").removeClass("hidden");
            $("#comboPeso").removeClass("hidden");
        }
    }
</script>