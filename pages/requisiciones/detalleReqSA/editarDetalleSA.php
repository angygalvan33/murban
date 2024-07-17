<?php
    include "../../config.php";
    include_once '../../clases/usuario.php';
    $usuarioEDR = new Usuario();
?>
<script src="pages/requisiciones/detalleReq/detalleReqScript.js" type="text/javascript"></script>

<div id="nuevaRequisicion4">
    <div class="well" style="display: block;">
        <form id="formRequisicion4" role="form">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
							<div class="col-md-3">
                        		<label>Proyecto</label>
                        		<select id="obraEspecial4" name="obraEspecial4" class="form-control obraEspecial" required="" ></select>
                    		</div>
                            <div class="col-md-3">
                                <label>Material</label>
                                <br>
                                <select disabled="disabled" id="materialEspecial4" name="materialEspecial4" class="form-control materialEspecial enReq" required=""></select>
                            </div>
                            <div class="col-md-1">
                                <label>Cantidad</label>
                                <br>
                                <input id="cantidadEspecial4" name="cantidadEspecial4" class="form-control enReq" required="" type="text">
                            </div>
							<div class="col-md-2">
							    <input id="medidaEspecial4" type="hidden">
                                <input id="unidadMatEspecial4" type="hidden">
                                <input id="largoMatEspecial4" type="hidden">
                                <input id="anchoMatEspecial4" type="hidden">
                                <input id="altoMatEspecial4" type="hidden">
                                <input id="pesoMatEspecial4" type="hidden">
								<label>Unidad</label>
                                <br>
                                <select disabled="disabled" id="unidadMat4" name="unidadMat4" class="form-control" style="padding:0px">
								    <option value="0">Pieza</option>
								    <option value="1">Metros</option>
								    <option value="2">Centimetros</option>
								    <option value="3">Pulgadas</option>
								    <option value="4">Pies</option>
								    <option value="5">Gramos</option>
								    <option value="6">Kilos</option>
								    <option value="7">M³ Cubicos</option>
								    <option value="8">Litros</option>
								    <option value="9">M2</option>
								    <option value="10">ft2</option>
								</select>
                            </div>
	                    	<div class="col-md-3">
	                    		<div class="form-group">
			                        <label>Solicita</label>
			                        <select disabled="disabled" id="solicitaEspecial4" name="solicitaEspecial4" class="form-control solicitaEspecial4 enReq" required="">
			                            <option value="<?php echo $usuarioEDR->getIdFromUsername($_SESSION['username']) ?>" selected="selected"><?php echo $usuarioEDR->getNameFromUsername($_SESSION['username']) ?></option>
			                        </select>
			                    </div>
	                    	</div>
                        </div>
                    </div>
				    <input type="hidden" name="IdRequisicionDetalle" id="IdRequisicionDetalle">
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger btn-block" onclick="cancelarNuevaRequisicion4(4)">Cancelar</button>
        </div>
        <div class="col-md-4">
            <button type="button" id="BtnGuardar"  class="btn btn-bitbucket btn-block" onclick="guardarRequisicion4($('#IdRequisicionDetalle').val())">Guardar</button>
        </div>
    </div>
    <br>
    <br>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
		$("#materialEspecial4").change( function() {
		    var dataM = $('#materialEspecial4').select2('data');
            getMaterialByIdEspecial4(dataM[0].id);
		});
        
        $("#formRequisicion4").validate( {
            rules: {
                cantidadEspecial: { number: true }
            }
		});

        $("#cantidadEspecial4").inputmask(
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
</script>