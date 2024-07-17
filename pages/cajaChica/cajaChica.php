<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/cajaChica/cajaChicaScript.js" type="text/javascript"></script>
<link href="pages/cajaChica/cajaChicaStyles.css" rel="stylesheet" type="text/css"/>

<h3>CAJA CHICA</h3>
<div class="panel-group" id="accordion">
    <?php if ($permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#cajasChicasPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    ADMINISTRACIÓN
                </h4>
            </div>
        </a>
        <div id="cajasChicasPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadAdminCajaChica">
                    <?php
                        include 'administracion/adminCajaChica.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($permisos->acceso("34359738368", $usuario->obtenerPermisos($_SESSION['username']))): ?>
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#usuarioControlCChPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    REPORTES
                </h4>
            </div>
        </a>
        <div id="usuarioControlCChPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadUCCajaChica">
                    <?php
                        include 'controlUsuario/controlUsuario.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($permisos->acceso("68719476736", $usuario->obtenerPermisos($_SESSION['username']))): ?>
    <div class="panel box box-danger">
        <a data-toggle="collapse" data-parent="#accordion" href="#pagosPenientesCChPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CAJA CHICA
                </h4>
            </div>
        </a>
        <div id="pagosPenientesCChPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadPPendCajaChica">
                    <?php
                        include 'pagosPendientes/pagosPendientes.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#pendienteFacturacionCChPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    PENDIENTES DE FACTURACIÓN
                </h4>
            </div>
        </a>
        <div id="pendienteFacturacionCChPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadFactCajaChica">
                    <?php
                        include 'pendienteFacturacion/pendienteFacturacion.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
       //reload datatables al abrir paneles
        $("#cajasChicasPanel").on("show.bs.collapse", function() {
            $('#adminCChTable').DataTable().ajax.reload();
        });
        
        $("#usuarioControlCChPanel").on("show.bs.collapse", function() {
            inicializaFechas();
           $("#usuarioCU").empty();
           $("#usuarioCUValue").val("-1");
            $('#usuarioCChTable').DataTable().ajax.reload();
        });
        
        $("#pagosPenientesCChPanel").on("show.bs.collapse", function() {
            $("#ppusuarioCU").empty();
            $("#ppusuarioCUValue").val("-1");
            $("#ppCCh").html("<h4>Presupuesto:&nbsp;<strong>$0<strong></h4>");
            $("#nuevaComprausuario").prop("disabled", true);
            $('#ppusuarioCChTable').DataTable().ajax.reload();
        });
        
        $("#pendienteFacturacionCChPanel").on("show.bs.collapse", function() {
           $("#ppusuarioPf").empty();
           $("#ppusuarioPfValue").val("-1");
        });

        $("#usuarioControlCChPanel").on("show.bs.collapse", function() {
            $("#tipoMovimiento").val(0);
            $("#usuarioCUValue").val("-2");
            $("#usuarioCU").empty();
            $("#pCCh").html("");
            $("#nuevaComprausuario").prop("disabled", true);
            autoCompleteUsuarios($('.usuarioCU'), 'IN');

            <?php if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                $("#usuarioCUValue").val( <?php echo $usuario->getIdFromUsername($_SESSION['username']) ?> );
                $("#usuarioCU").prop("disabled", true);
                $("#nuevaComprausuario").prop("disabled", false);
            <?php endif; ?>

            getGastadoDeUsuario($("#usuarioCUValue").val());
            inicializaFechas();
            $('#usuarioCChTable').DataTable().ajax.reload();
        });
    });
</script>