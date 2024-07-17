<?php
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<script src="pages/compras/nuevaOCReqScript.js" type="text/javascript"></script>
<script src="pages/compras/requispreoc/detallePreOc/listadoMaterialesScript.js" type="text/javascript"></script>
<input type="hidden" id="tipoOCReq">

<div id="nuevaOCReq">
    <div class="well" style="display: block;">
        <form id="formOCReq" role="form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipo de OC:</label>
                        <select id="tipoPagoOCReq" name="tipoPagoOCReq" class="form-control" required="">
                            <option value="0" selected>Por pagar</option>
                            <option value="1">Pago requerido</option>
                            <option value="3">Sin Autorizacion</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Proveedor</label>
                        <select id="proveedorOCReq" name="proveedorOCReq" class="form-control enOCReq" required=""></select>
                        <input type="hidden" id="valIdProvOCReq">
                    </div>
                    <div class="form-group">
                        <label>Proyecto</label>
                        <select id="obraOCReq" name="obraOCReq" class="form-control enOCReq" required=""></select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Material</label>
                                <br>
                                <select id="materialOCReq" name="materialOCReq" class="form-control enOCReq" required=""></select>
                            </div>
                            <div class="col-md-4">
                                <label>Cantidad</label>
                                <br>
                                <input id="cantidadOCReq" name="cantidadOCReq" class="form-control enOCReq" required="" type="text">
                            </div>
							<div class="col-md-2" style="display:none">
                                <label>Unidad</label>
                                <br>
                                <select id="unidadMat" name="unidadMat" class="form-control" disabled>
								    <option value="0" Selected>Pzs</option>
								    <option value="1">Metros</option>
								    <option value="2">Centimetros</option>
								    <option value="3">Pulgadas</option>
								    <option value="4">Pies</option>
								    <option value="5">Gramos</option>
								    <option value="6">Kilos</option>
								    <option value="7">Metros Cubicos</option>
								    <option value="8">Litros</option>
								</select>
                            </div>
							<div class="col-md-1" style="margin-top: 30px !important;padding: 0px 0px !important;">
                                <button type="button" onclick="agregaDetalleOCReq()"><i class="fa fa-angle-double-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-7">
                                <label>Archivo adjunto</label>
                                <br>
                                <input type="file" id="adjuntoOCReq" name="adjuntoOCReq" accept="image/jpg, image/jpeg, application/pdf" style="width: 100%" class="enOCReq">
                            </div>
                            <div class="col-md-5">
                                <label>Precio unitario (MXN)</label>
                                <br>
                                <input id="precioOCReq" name="precioOCReq" class="form-control enOCReq" required="" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Solicita</label>
                        <select id="solicitaOCReq" name="solicitaOCReq" class="form-control enOCReq" required=""></select>
                    </div>
                    <div class="form-group">
                        <label>No. de cotización</label>
                        <input id="numCotizacionOCReq" name="numCotizacionOCReq" class="form-control enOCReq">
                    </div>
                </div>
                <div class="col-md-8" id="tablematsbypz">
                    <div class="well">
                        <table id="matsOCReq" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Material</th>
                                    <th>Precio unitario (MXN)</th>
                                    <th>Costo total (MXN)</th>
                                    <th>Proyecto</th>
                                    <th>Solicita</th>
                                    <th>Fecha de Proveedor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right" rowspan="1">Subtotal: $</th>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
				<div class="col-md-8" style="display:none" id="tablematsbykg">
				    <div class="well">
				        <table id="matsOCReqxkilo" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Material</th>
                                    <th>Precio unitario (MXN)</th>
                                    <th>Costo total (MXN)</th>
                                    <th>Proyecto</th>
                                    <th>Solicita</th>
                                    <th>Fecha de Proveedor</th>
                                    <th><th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right" rowspan="1">Subtotal: $</th>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
				   </div>
				</div>
            </div>
        </form>
        <form id="pagoFormOCReq">
            <div class="well">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Notas internas</label>
                                    <br>
                                    <textarea id="descripcionOCReq" name="descripcionOCReq" class="form-control" rows="2" style="resize:none" maxlength="200"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Notas a proveedor</label>
                                    <br>
                                    <textarea id="notasProveedorOCReq" name="notasProveedorOCReq" class="form-control" rows="2" style="resize:none" maxlength="200"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <th>Subtotal</th>
                                <td>
                                    <div id="subtotalOCReq" valor=""></div>
                                </td>
                            </tr>
                            <tr>
                                <th>IVA</th>
                                <td>
                                    <div id="ivaOCReq" valor=""></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>
                                    <div id="totalOCReq" valor=""></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-block" onclick="cancelarOCReq()">Cancelar</button>
            </div>
            <div class="col-md-3" id="btnsaveocreq">
                <button type="button" id="BtnGuardarOCReq"  class="btn btn-bitbucket btn-block" onclick="guardarOCReq($('#tipoOCReq').val())">Guardar</button>
            </div>
			<div class="col-md-3" id="btnsaveocreqbykg" style="display:none">
                <button type="button" id="BtnGuardarOCReqxkilo"  class="btn btn-bitbucket btn-block" onclick="guardarOCReqxkilo($('#tipoOCReq').val())">Guardar</button>
            </div>
        </div>
    </div>
    <div id="ordenCompraCompletaOCReq" class="well" style="display: block;">
        <table id="ocCompleteTablaOCReq" class="table table-hover">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Material</th>
                    <th>Precio unitario (MXN)</th>
                    <th>Costo total (MXN)</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $(".enOCReq").prop("disabled", true);
        inicializaMaterialesByProveedorOCReq();
        inicializaVistaPreviaOCReq();
        autoCompleteProveedoresOCReq();
        
        $('#matsOCReq tbody').on( 'click', 'button', function () {
            var data = $("#matsOCReq").DataTable().row($(this).parents('tr')).data();
            switch ($(this).attr("id")) {
                case "eliminar_OCReq":
                    eliminarOCReq(data.IdRequisicionAtendida);
                break;
                case "regresar_OCReq":
                    //regresarOCReq(data.IdRequisicionAtendida);
                    seleccionarMaterial(data.IdRequisicionAtendida, 0);
                break;
            }
        });
    });
    
    function guardarOCReq(tipo) {
        var permisoAutorizar = <?php echo json_encode($permisos->acceso("32768", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var descripcionCompra = $('#descripcionOCReq').val();
        var notasProveedor = $('#notasProveedorOCReq').val();
        
        if ($("#pagoFormOCReq").valid()) {
            $("#BtnGuardarOCReq").prop("disabled", true);
            guardarOrdenDeCompraOCReq(tipo, permisoAutorizar, descripcionCompra, notasProveedor, 0, <?php echo $usuario->getIdFromUsername($_SESSION['username']); ?>);
        }
    }
	
	function guardarOCReqxkilo(tipo) {
        var permisoAutorizar = <?php echo json_encode( $permisos->acceso("32768", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var descripcionCompra = $('#descripcionOCReq').val();
        var notasProveedor = $('#notasProveedorOCReq').val();
        if ($("#pagoFormOCReq").valid()) {
            $("#BtnGuardarOCReqxkilo").prop("disabled", true);
            guardarOrdenDeCompraOCReqxkilo(tipo, permisoAutorizar, descripcionCompra, notasProveedor, 0, <?php echo $usuario->getIdFromUsername($_SESSION['username']); ?>);
        }
    }
</script>