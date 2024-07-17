<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../../config.php";
    include_once '../../../clases/permisos.php';
    include_once '../../../clases/usuario.php';
    $permisosDet = new Permisos();
    $usuarioDet = new Usuario();
?>

<script src="pages/requisiciones/detalleReqSA/detalleReqSAScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <table id="detalleReqSATable" class="table table-hover reqsDetalle">
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
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoCancelarDet = <?php echo json_encode($permisosDet->acceso("8192", $usuarioDet->obtenerPermisos($_SESSION['username']))); ?>;
        inicializaDetalleReqSATable(permisoCancelarDet);
    });
</script>