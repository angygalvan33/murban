<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/metodoPago/metodoPagoScript.js" type="text/javascript"></script>
<link href="pages/metodoPago/metodoPagoStyles.css" rel="stylesheet" type="text/css"/>

<h3>MÉTODO DE PAGO</h3>
<div class="row">
    <div class="col-md-10"> </div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <?php if ($permisos->acceso("32", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalMP()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        <?php endif; ?>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="mPTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Referencia</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevoMetodoPago.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        var permisoAdministrar = <?php echo json_encode($permisos->acceso("32", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        loadDataTable(permisoAdministrar);
        
        $('#mPTable tbody').on('click', 'button', function () {
            var data = $("#mPTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarMetodoPago(data);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdMetodoPago);
                    $("#warningModal").modal("show");
                break;
            }
        });
    });
</script>