<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../../../config.php";
    include_once '../../../../clases/permisos.php';
    include_once '../../../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/cuentasPorPagar/cuentasPorProveedor/detallesCxP/detallesCxPScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <table id="cxpDetallesTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Folio de factura</th>
                <th>Fecha de facturación</th>
                <th>Monto (MXN)</th>
                <th>Días de crédito restantes</th>
                <th>Proponer</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tfoot></tfoot>
    </table>
</div>

<div id="autorizarModal2" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
                <input type="hidden" id="idOCGeneral2">
                <input type="hidden" id="tipoAutorizacion2">
                <input type="hidden" id="edoOC2">
                <input type="hidden" id="idProveedorOCGeneral2">
                <input type="hidden" id="ValorFacturaOCGeneral2">
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" onclick="cancelarAutorizarOC2()">Cancelar</button>
                <button id="autorizarOrden" type="button" class="btn btn-outline" data-dismiss="modal" onclick="autorizarOC2($('#idOCGeneral2').val(), $('#edoOC2').val(), $('#tipoAutorizacion2').val(), $('#idProveedorOCGeneral2').val(), $('#ValorFacturaOCGeneral2').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tp = ta = 0;
    $( document ).ready(function() {
        var idProv = $(".detalles2").attr("id");
        tp = $("#"+ idProv +"_tpvalue").val();
        ta = $("#"+ idProv +"_tavalue").val();
        var permisoProponer = <?php echo json_encode($permisos->acceso("33554432", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var permisoAutorizar = <?php echo json_encode($permisos->acceso("67108864", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        
        loadDataTableDetalles(permisoProponer, permisoAutorizar);
        
        $('#cxpDetallesTable tbody').on('click', 'input.proponer', function(event, state) {
            var data = $("#cxpDetallesTable").DataTable().row($(this).parents('tr')).data();
            $("#idOCGeneral2").val(data.IdOC);
            $("#idProveedorOCGeneral2").val(data.IdProveedor);
            $("#ValorFacturaOCGeneral2").val(data.Deuda);
            $("#tipoAutorizacion2").val(1);

            var pregunta = "";
            if ($(this).prop('checked')) {
                pregunta = "¿Estás seguro de que deseas PROPONER la órden de compra?";
                $("#edoOC2").val(1);
            }
            else {
                pregunta = "¿Estás seguro de que deseas quitar PROPUESTO a la órden de compra?";
                $("#edoOC2").val(0);
            }
            
            autorizarOC2($('#idOCGeneral2').val(), $('#edoOC2').val(), $('#tipoAutorizacion2').val(), $('#idProveedorOCGeneral2').val(), $('#ValorFacturaOCGeneral2').val());
        });
        
        $('#cxpDetallesTable tbody').on('click', 'input.autorizar', function(event, state) {
            var data = $("#cxpDetallesTable").DataTable().row($(this).parents('tr')).data();
            $("#idOCGeneral2").val(data.IdOC);
            $("#idProveedorOCGeneral2").val(data.IdProveedor);
            $("#ValorFacturaOCGeneral2").val(data.Deuda);
            $("#tipoAutorizacion2").val(2);

            var pregunta = "";
            if ($(this).prop('checked')) {
                pregunta = "¿Estás seguro de que deseas AUTORIZAR la órden de compra?";
                $("#edoOC2").val(1);
            }
            else {
                pregunta = "¿Estás seguro de que deseas quitar AUTORIZACIÓN a la órden de compra?";
                $("#edoOC2").val(0);
            }

            if ($('#edoOC2').val() == 1) {
                activarProponer(data.IdOC);
            }
            
            autorizarOC2($('#idOCGeneral2').val(), $('#edoOC2').val(), $('#tipoAutorizacion2').val(), $('#idProveedorOCGeneral2').val(), $('#ValorFacturaOCGeneral2').val());
        });
        
        $('#cxpDetallesTable').on('click', 'td.details-control2', function () {
            var tr = $(this).closest('tr');
            var row = $('#cxpDetallesTable').DataTable().row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#cxpDetallesTable').DataTable().row('.shown').length) {
                    $('.details-control2', $('#cxpDetallesTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatCP(row.data())).show();
                tr.addClass('shown');
            }
        });
        /*Formatting function forl row details - modify as you need*/
        function formatCP (rowData) {
            var divTipo = $('<div/>', { class:'tipo', id:"cuentasPorPagarProveedor" });
            var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOC });
            divTipo.append(divDetalles);
            divDetalles.load("pages/compras/detalleOC/detalleOC.php");
            return divTipo;
        }
    });
</script>