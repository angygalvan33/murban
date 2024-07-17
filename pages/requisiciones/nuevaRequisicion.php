<?php
    include "../../config.php";
    include_once '../../clases/usuario.php';
    $usuarioNR = new Usuario();
?>

<div id="nuevaRequisicion">
    <div class="well" style="display: block;">
        <form id="formRequisicion" role="form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Proyecto</label>
                        <select id="obraEspecial" name="obraEspecial" class="form-control obraEspecial" required=""></select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Material</label>
                                <br>
                                <select id="materialEspecial" name="materialEspecial" class="form-control materialEspecial enReq" required=""></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Cantidad</label>
                                <br>
                                <input id="cantidadEspecial" name="cantidadEspecial" class="form-control enReq" required="" type="text">
                            </div>
							<div class="col-md-5">
							    <input id="medidaEspecial" type="hidden">
                                <input id="unidadMatEspecial" type="hidden">
                                <input id="largoMatEspecial" type="hidden">
                                <input id="anchoMatEspecial" type="hidden">
                                <input id="altoMatEspecial" type="hidden">
                                <input id="pesoMatEspecial" type="hidden">
								<label>Unidad</label>
                                <br>
                                <select id="unidadMat" name="unidadMat" class="form-control" style="padding:0px">
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
                            <div class="col-md-2" style="margin-top: 30px !important;padding: 0px 0px !important;">
                                <button type="button" onclick="agregaDetalleOC_Especial()"><i class="fa fa-angle-double-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display: none">
                        <label>Solicita</label>
                        <select disabled="disabled" id="solicitaEspecial" name="solicitaEspecial" class="form-control solicitaEspecial enReq" required="">
                            <option value="<?php echo $usuarioNR->getIdFromUsername($_SESSION['username']) ?>" selected="selected"><?php echo $usuarioNR->getNameFromUsername($_SESSION['username']) ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha en la que se requiere</label>
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input data-date-format="yyyy-mm-dd" type="text" class="form-control pull-right" id="fecharequi" required />
                        <input type="hidden" id="frequi" value="-1">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="well">
                         <table id="matsEspecial" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
									<th>Material</th>
									<th>Piezas</th>
                                    <th>Proyecto</th>
                                    <th>Solicita</th>
                                    <th>Fecha en la que se requiere</th>
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
                        <textarea id="descripcionRequisicion" name="descripcionRequisicion" class="form-control" rows="2" style="resize:none" maxlength="200"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger btn-block" onclick="cancelarNuevaRequisicion(1)">Cancelar</button>
        </div>
        <div class="col-md-4">
            <button type="button" id="BtnGuardar"  class="btn btn-bitbucket btn-block" onclick="guardarRequisicion($('#descripcionRequisicion').val())">Guardar</button>
        </div>
    </div>
    <br>
    <br>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaMaterialesByProveedorEspecial();
        autoCompleteObrasEspecial();
        //autoCompleteUsuariosEspecial();
        autoCompleteMaterialesEspecial(0);
        inicializaFechas();

		$("#materialEspecial").change( function() {
		    var dataM = $('#materialEspecial').select2('data');
            getMaterialByIdEspecial(dataM[0].id);
		});
		
        $('#matsEspecial tbody').on('click', 'button', function () {
            switch ($(this).attr("id")) {
                case "eliminarEspecial":
                    var actualRow = $("#matsEspecial").DataTable().row($(this).parents('tr'));
                    eliminarOCEspecial(actualRow);
                break;
            }
        });
        
        $("#formRequisicion").validate( {
            rules: {
                cantidadEspecial: { number: true }
            }
	    });

        $("#cantidadEspecial").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        function inicializaFechas() {
            $('#fecharequi').datepicker( {
                "setDate": new Date(),
                "autoclose": true,
                "inmediateUpdates": true,
                "todayBtn": true,
                "todayHighlight": true,
            }).datepicker("setStartDate", "0");
        }
    });
</script></script>