<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../../config.php";
    include_once '../../../clases/permisos.php';
    include_once '../../../clases/usuario.php';
    $permisosDet = new Permisos();
    $usuarioDet = new Usuario();
?>

<script src="pages/requisiciones/detalleReq/detalleReqScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <table id="detalleReqTable" class="table table-hover reqsDetalle">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad</th>
                <th>Unidad</th>
				<th>Piezas</th>
				<th>Cantidad Atendida</th>
                <th>Material</th>
                <th>Proyecto</th>
                <th>Solicita</th>
                <th>Fecha en la que se requiere</th>
                <th>Estado</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<div id="eliminarDetalleReqrevModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminar</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cacelar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal" onclick="eliminarDetalleRequisicionrev($('#IdRequisicionDetalle').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoCancelarDet = <?php echo json_encode($permisosDet->acceso("8192", $usuarioDet->obtenerPermisos($_SESSION['username']))); ?>;
        inicializaDetalleReqTable(permisoCancelarDet);
        
        $('#detalleReqTable').on('click', 'button', function () {
            var data = $("#detalleReqTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "detallereq_eliminar":
                    $("#IdRequisicionDetalle").val(data.IdRequisicionDetalle);
                    $("#eliminarDetalleReqrevModal").modal("show");
                break;
                case "detallereq_editar":
                    $("#IdRequisicionDetalle").val(data.IdRequisicionDetalle);
                    mostrarOcultarNuevaRequisiscion(1, 4);
                    llenarDetalleRequisicionEditar(data.IdRequisicionDetalle);
                break;
            }
        });
    });
</script>