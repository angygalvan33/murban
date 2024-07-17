<?php
    include "../../config.php";
    include_once '../../clases/usuario.php';
    $usuarioNRE = new Usuario();
?>
<div id="nuevaRequisicionEspecial">
    <div class="well" style="display:block;">
        <form id="formRequisicion2" role="form">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Proyecto</label>
                        <select id="obraEspecial2" name="obraEspecial2" class="form-control obraEspecial2" required="" ></select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-7">
                                <label>Material</label>
                                <br>
                                <input type="text" id="materialEspecial2" name="materialEspecial2" class="form-control materialEspecial2 enReq" required="">
                            </div>
                            <div class="col-md-3">
                                <label>Cantidad</label>
                                <br>
                                <input id="cantidadEspecial2" name="cantidadEspecial2" class="form-control enReq" required="" type="text">
                            </div>
                            <div class="col-md-1" style="margin-top: 30px !important;padding: 0px 0px !important;">
                                <button type="button" onclick="agregaDetalleOC_Especial2()"><i class="fa fa-angle-double-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="checkbox" class='icheckbox_flat-green' id="unicaOcasion" name="unicaOcasion" checked disabled>
                                <label>Por única ocasión</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha en la que se requiere</label>
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    <input data-date-format="yyyy-mm-dd" type="text" class="form-control pull-right" id="fecharequiesp" required=""/>
                                    <input type="hidden" id="frequiesp" value="-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group"  style="display: none">
                        <label>Solicita</label>
                        <select disabled="disabled" id="solicitaEspecial2" name="solicitaEspecial2" class="form-control solicitaEspecial2 enReq" required="">
                            <option value="<?php echo $usuarioNRE->getIdFromUsername($_SESSION['username']) ?>" selected="selected"><?php echo $usuarioNRE->getNameFromUsername($_SESSION['username']) ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="well">
                        <table id="matsEspecial2" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Material</th>
                                    <th>Proyecto</th>
                                    <th>Solicita</th>
                                    <th>Única ocasión</th>
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
                        <textarea id="descripcionRequisicion2" name="descripcionRequisicion2" class="form-control" rows="2" style="resize:none" maxlength="200"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger btn-block" onclick="cancelarNuevaRequisicion(2)">Cancelar</button>
        </div>
        <div class="col-md-4">
            <button type="button" id="BtnGuardar2"  class="btn btn-bitbucket btn-block" onclick="guardarRequisicion2($('#descripcionRequisicion2').val())">Guardar</button>
        </div>
    </div>
    <br>
    <br>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaMaterialesByProveedorEspecial2();
        autoCompleteObrasEspecial2();
        autoCompleteUsuariosEspecial2();
        inicializaFechas();

        $('#matsEspecial2 tbody').on('click', 'button', function () {
            switch ($(this).attr("id")) {
                case "eliminarEspecial":
                    var actualRow = $("#matsEspecial2").DataTable().row($(this).parents('tr'));
                    eliminarOCEspecial2(actualRow);
                break;
            }
        });
        
        $("#formRequisicion2").validate( {
            rules: {
                cantidadEspecial2: { number: true }
            }
	    });

        $("#cantidadEspecial2").inputmask(
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

    function inicializaFechas() {
        $('#fecharequiesp').datepicker( {
            "setDate": new Date(),
            "autoclose": true,
            "inmediateUpdates": true,
            "todayBtn": true,
            "todayHighlight": true,
        }).datepicker("setStartDate", "0");
    }
</script>