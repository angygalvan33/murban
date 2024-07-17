<?php
    include "../../config.php";
    include_once '../../clases/usuario.php';
    $usuarioER = new Usuario();
?>
<div id="nuevaRequisicion3">
    <div class="well" style="display:block;">
        <form id="formRequisicion3" role="form">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Proyecto</label>
                        <select id="obraEspecial3" name="obraEspecial3" class="form-control obraEspecial" required=""></select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Material</label>
                                <br>
                                <select id="materialEspecial3" name="materialEspecial3" class="form-control materialEspecial enReq" required=""></select>
                            </div>
                            <div class="col-md-2">
                                <label>Cantidad</label>
                                <br>
                                <input id="cantidadEspecial3" name="cantidadEspecial3" class="form-control enReq" required="" type="text">
                            </div>
							<div class="col-md-3">
							    <input id="medidaEspecial3" type="hidden">
                                <input id="unidadMatEspecial3" type="hidden">
                                <input id="largoMatEspecial3" type="hidden">
                                <input id="anchoMatEspecial3" type="hidden">
                                <input id="altoMatEspecial3" type="hidden">
                                <input id="pesoMatEspecial3" type="hidden">
								<label>Unidad</label>
                                <br>
                                <select id="unidadMat3" name="unidadMat3" class="form-control" style="padding:0px">
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
                            <div class="col-md-1" style="margin-top: 30px !important;padding: 0px 0px !important;">
                                <button type="button" onclick="agregaDetalleOC_Especial3()"><i class="fa fa-angle-double-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Solicita</label>
                        <select disabled="disabled" id="solicitaEspecial3" name="solicitaEspecial3" class="form-control solicitaEspecial enReq" required="">
                            <option value="<?php echo $usuarioER->getIdFromUsername($_SESSION['username']) ?>" selected="selected"><?php echo $usuarioER->getNameFromUsername($_SESSION['username']) ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="well">
                        <table id="matsEspecial3" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
									<th>Material</th>
									<th>Piezas</th>
                                    <th>Proyecto</th>
                                    <th>Solicita</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <div class="well">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label>Observaciones</label>
                        <br>
                        <textarea id="descripcionRequisicion3" name="descripcionRequisicion3" class="form-control" rows="2" style="resize:none" maxlength="200"></textarea>
                    </div>
                    <input type="hidden" name="idRequisicion" id="idRequisicion">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger btn-block" onclick="cancelarNuevaRequisicion3(3)">Cancelar</button>
        </div>
        <div class="col-md-4">
            <button type="button" id="BtnGuardar"  class="btn btn-bitbucket btn-block" onclick="guardarRequisicion3($('#descripcionRequisicion3').val(), $('#idRequisicion').val())">Guardar</button>
        </div>
    </div>
    <br>
    <br>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaMaterialesByProveedorEspecial3();
        autoCompleteObrasEspecial3();
        autoCompleteUsuariosEspecial3();
        autoCompleteMaterialesEspecial3(0);
        
		$("#materialEspecial3").change( function() {
		    var dataM = $('#materialEspecial3').select2('data');
		    getMaterialByIdEspecial3(dataM[0].id);
		});
		
        $('#matsEspecial3 tbody').on('click', 'button', function () {
            switch ($(this).attr("id")) {
                case "eliminarEspecial":
                    var actualRow = $("#matsEspecial3").DataTable().row($(this).parents('tr'));
                    eliminarOCEspecial3(actualRow);
                break;
            }
        });
        
        $("#formRequisicion3").validate( {
            rules: {
                cantidadEspecial: { number: true }
            }
	    });

        $("#cantidadEspecial3").inputmask(
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