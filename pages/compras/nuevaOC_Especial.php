<?php
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<input type="hidden" id="tipoOCEspecial">
<div id="nuevaOC_Especial">
    <div class="well" style="display:block;">
        <form id="formOC_Especial" role="form">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Tipo de OC:</label>
                        <select id="tipoPagoEspecial" name="tipoPagoEspecial" class="form-control" required="">
                            <option value="0" selected>Por pagar</option>
                            <option value="1">Pago requerido</option>
                            <option value="3">Sin Autorizacion</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Proveedor</label>
                        <select id="proveedorEspecial" name="proveedorEspecial" class="form-control proveedorEspecial enReq" required=""></select>
                    </div>
                    <div class="form-group">
                        <label>Proyecto</label>
                        <select id="obraEspecial" name="obraEspecial" class="form-control obraEspecial enReq" required=""></select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-7">
                                <label>Material</label>
                                <br>
                                <select id="materialEspecial" name="materialEspecial" class="form-control materialEspecial enReq" required=""></select>
                            </div>
                            <div class="col-md-3">
                                <label>Cantidad</label>
                                <br>
                                <input id="cantidadEspecial" name="cantidadEspecial" class="form-control enReq" required="" type="text">
                            </div>
                            <div class="col-md-1" style="margin-top: 30px !important;padding: 0px 0px !important;">
                                <button type="button" onclick="agregaDetalleOC_Especial()"><i class="fa fa-angle-double-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-7">
                                <label>Archivo adjunto</label>
                                <br>
                                <input type="file" id="adjunto" name="adjunto" accept="image/jpg, image/jpeg, application/pdf" style="width: 100%" class="enReq">
                            </div>
                            <div class="col-md-5">
                                <label>Precio unitario (MXN)</label>
                                <br>
                                <input id="precioEspecial" name="precioEspecial" class="form-control enReq" required="" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Solicita</label>
                        <select id="solicitaEspecial" name="solicitaEspecial" class="form-control solicitaEspecial enReq" required=""></select>
                    </div>
                    <div class="form-group">
                        <label>No. de cotización</label>
                        <input id="numCotizacionEspecial" name="numCotizacionEspecial" class="form-control enReq">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="well">
                        <table id="matsEspecial" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Material</th>
                                    <th>Precio unitario (MXN)</th>
                                    <th>Costo total (MXN)</th>
                                    <th>Proyecto</th>
                                    <th>Solicita</th>
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
            </div>
        </form>
        <form id="pagoFormEspecial">
            <div class="well">
                <div class="row">
                    <div class="col-md-8">
                        <div class="facturaEspecial">
                            <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
                                <legend class="scheduler-border">Pago</legend>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total factura (MXN):</label>
                                        <input type="text" id="totalFacturaEspecial" name="totalFacturaEspecial" class="form-control" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>Folio de factura:</label>
                                        <input type="text" id="folioFacturaEspecial" name="folioFacturaEspecial" class="form-control" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de factura:</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="fechaFacturaEspecial" required="">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Notas internas</label>
                                    <br>
                                    <textarea id="descripcionCompra" name="descripcionCompra" class="form-control" rows="2" style="resize:none" maxlength="200"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Notas a proveedor</label>
                                    <br>
                                    <textarea id="notasProveedor" name="notasProveedor" class="form-control" rows="2" style="resize:none" maxlength="200"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <th>Subtotal</th>
                                <td>
                                    <div id="subtotalEspecial" valor=""></div>
                                </td>
                            </tr>
                            <tr>
                                <th>IVA</th>
                                <td>
                                    <div id="ivaEspecial" valor=""></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>
                                    <div id="totalEspecial" valor=""></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <div class="row">
            <div class="col-md-5"></div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-block" onclick="cancelar_Especial()">Cancelar</button>
            </div>
            <div class="col-md-4">
                <input type="hidden" id="tipoOC_Req">
                <button type="button" id="BtnGuardar"  class="btn btn-bitbucket btn-block" onclick="guardarOC($('#tipoOC_Req').val())">Guardar</button>
            </div>
        </div>
    </div>
    <div id="ordenCompraCompleta" class="well" style="display:block;">
        <table id="ocCompleteTabla" class="table table-hover">
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
        inicializaMaterialesByProveedorEspecial();
        inicializaVistaPreviaOC();
        autoCompleteProveedoresEspecial();
        autoCompleteObrasEspecial();
        autoCompleteUsuariosEspecial();
        autoCompleteMaterialesEspecial(0);
        llenaMetodoPagoEspecial();

        $("#proveedorEspecial").change( function() {
            $("#materialEspecial").val("");
            $("#precioEspecial").val("");
            $("#cantidadEspecial").val("");
            autoCompleteMaterialesEspecial($("#proveedorEspecial").val());
	    });
        
        $("#materialEspecial").change( function() {
            var dataM = $('#materialEspecial').select2('data');

            if (dataM[0].id === "-1")
                $("#precioEspecial").val("");
            else {
                var dataP = $('#proveedorEspecial').select2('data');
                if (dataP.length > 0)
                    getMaterialByIdEspecial(dataM[0].id, dataP[0].id);
                else
                    $("#presupuestoEspecial").html("");
            }
	    });
        
        $("#tipoPagoEspecial").change( function() {
            muestraExtraTipoPagoEspecial($(this).val());
        });
        
        $('#matsEspecial tbody').on('click', 'button', function () {
            switch ($(this).attr("id")) {
                case "eliminarEspecial":
                    actualRow = $("#matsEspecial").DataTable().row($(this).parents('tr'));
                    eliminarOCEspecial(actualRow);
                break;
            }
        });
        
        $("#formOC_Especial").validate( {
            rules: {
                cantidadEspecial: { number: true },
                precioEspecial: { number: true }
            }
	    });
        
        $("#pagoFormEspecial").validate( {
            rules: {
                anticipo: { number: true }
            }
	    });
        
        $("#anticipoEspecial").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $("#totalFacturaEspecial").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

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

        $("#precioEspecial").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $('#fechaFacturaEspecial').datepicker( {
              autoclose: true
        });
        
        $("#metodoPagoEspecial").change(function() {
            dameReferencia($(this).val());
        });
    });
 
    function guardarOC_Especial() {
        var tipo = $('#tipoOCEspecial').val();
        var permisoAutorizar = <?php echo json_encode($permisos->acceso("32768", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var descripcionCompra = $('#descripcionCompra').val();
        var notasProveedor = $('#notasProveedor').val();
        
        if ($("#pagoFormEspecial").valid()) {
            if ($("#tipoPagoEspecial").val() === 2) {
                if (esCantidadMenorOIgualADeuda($("#anticipoEspecial").val().replace(/\,/g, ''), $("#totalEspecial").text().replace(/\$/g, ''))) {
                    $("#BtnGuardar").prop("disabled", true);
                    $("#errorCantidadEspecial").css("display", "none");
                    guardarOrdenDeCompraEspecial(permisoAutorizar, tipo, descripcionCompra, notasProveedor, 0, <?php echo $usuario->getIdFromUsername($_SESSION['username']); ?>);
                }
                else
                    $("#errorCantidadEspecial").css("display", "block");
            }
            else {
                $("#BtnGuardar").prop("disabled", true);
                guardarOrdenDeCompraEspecial(permisoAutorizar, tipo, descripcionCompra, notasProveedor, 0, <?php echo $usuario->getIdFromUsername($_SESSION['username']); ?>);
            }
        }
    }
    
    function guardarOC_Requisicion() {
        var tipo = $('#tipoOCEspecial').val();
        var permisoAutorizar = <?php echo json_encode($permisos->acceso("32768", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var descripcionCompra = $('#descripcionCompra').val();
        var notasProveedor = $('#notasProveedor').val();

        if ($("#pagoFormEspecial").valid()) {
            if ($("#tipoPagoEspecial").val() === 2) {
                if (esCantidadMenorOIgualADeuda($("#anticipoEspecial").val().replace(/\,/g, ''), $("#totalEspecial").text().replace(/\$/g, ''))) {
                    $("#BtnGuardar").prop("disabled", true);
                    $("#errorCantidadEspecial").css("display", "none");
                    guardarOrdenDeCompra_Requisicion(permisoAutorizar, tipo, descripcionCompra, notasProveedor, 0, <?php echo $usuario->getIdFromUsername($_SESSION['username']); ?>);
                }
                else
                    $("#errorCantidadEspecial").css("display","block");
            }
            else {
                $("#BtnGuardar").prop("disabled", true);
                guardarOrdenDeCompra_Requisicion(permisoAutorizar, tipo, descripcionCompra, notasProveedor, 0, <?php echo $usuario->getIdFromUsername($_SESSION['username']); ?>);
            }
        }
    }
    
    function guardarOC(tipo) {
        if (tipo === "1")
            guardarOC_Especial();
        else
            guardarOC_Requisicion();
    }
</script>