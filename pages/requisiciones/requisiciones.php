<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/requisiciones/requisicionesScript.js" type="text/javascript"></script>
<script src="pages/requisiciones/requisicionesEspecialScript.js" type="text/javascript"></script>
<script src="pages/requisiciones/requisicionesEditarScript.js" type="text/javascript"></script>
<link href="pages/requisiciones/requisicionesStyles.css" rel="stylesheet" type="text/css"/>

<h3>REQUISICIONES</h3>
<br/>
<div class="row">
    <div class="col-md-7"></div>
    <div class="col-md-3" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="mostrarOcultarNuevaRequisiscion(1, 2)"><i class="fa fa-plus"></i>&nbsp;Nueva RequisiciónEspecial</button>
    </div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="mostrarOcultarNuevaRequisiscion(1, 1)"><i class="fa fa-plus"></i>&nbsp;Nueva Requisición</button>
    </div>
    <div class="col-md-12">
        <?php
            include_once 'nuevaRequisicion.php';
            include_once 'nuevaRequisicionEspecial.php';
            include_once 'editarRequisicion.php';
            include_once 'detalleReq/editarDetalle.php';
        ?>
    </div>
</div>
<div class="panel-group" id="accordion">
    <?php if ($permisos->acceso("8192", $usuario->obtenerPermisos($_SESSION['username']))): ?>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#sinAutorizarPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    SIN AUTORIZAR
                </h4>
            </div>
        </a>
        <div id="sinAutorizarPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadRequisicionesSinAutorizar">
                    <?php
                        include 'sinAutorizar/sinAutorizar.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#pendientesPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    PENDIENTES
                </h4>
            </div>
        </a>
        <div id="pendientesPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadRequisicionesPendientes">
                    <?php
                        include 'pendientes/requisiciones.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-danger">
        <a data-toggle="collapse" data-parent="#accordion" href="#parcialmenteAtendidasPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    PARCIALMENTE ATENDIDAS
                </h4>
            </div>
        </a>
        <div id="parcialmenteAtendidasPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadRequisicionesParcialmenteAtendidas">
                    <?php
                        include 'parcialmenteAtendidas/requisiciones.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#atendidasPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    ATENDIDAS
                </h4>
            </div>
        </a>
        <div id="atendidasPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadRequisicionesAtendidas">
                    <?php
                        include 'atendidas/requisiciones.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-info">
        <a data-toggle="collapse" data-parent="#accordion" href="#parcialmenteCanceladasPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    PARCIALMENTE CANCELADAS
                </h4>
            </div>
        </a>
        <div id="parcialmenteCanceladasPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadRequisicionesParcialmenteCanceladas">
                    <?php
                        include 'parcialmenteCanceladas/requisiciones.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#canceladasPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CANCELADAS
                </h4>
            </div>
        </a>
        <div id="canceladasPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadRequisicionesCanceladas">
                    <?php
                        include 'canceladas/requisiciones.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#consultasPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CONSULTAS
                </h4>
            </div>
        </a>
        <div id="consultasPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadConsultasRequisiciones">
                    <?php
                        include 'consultas/consultas.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        mostrarOcultarNuevaRequisiscion(0, 1);
        mostrarOcultarNuevaRequisiscion(0, 2);
        mostrarOcultarNuevaRequisiscion(0, 3);
        mostrarOcultarNuevaRequisiscion(0, 4);
        
        $("#pendientesPanel").on("show.bs.collapse", function() {
            $('#requisicionesPendientesTable').DataTable().ajax.reload();
        });
        
        $("#pendientesPanel").on("hidden.bs.collapse", function() {
            $('#requisicionesPendientesTable').DataTable().ajax.reload();
        });
        
        $("#parcialmenteAtendidasPanel").on("show.bs.collapse", function() {
            $('#requisicionesParcialmenteAtendidasTable').DataTable().ajax.reload();
        });
        
        $("#pendientesPanel").on("show.bs.collapse", function() {
            $('#requisicionesPendientesTable').DataTable().ajax.reload();
        });
        
        $("#parcialmenteAtendidasPanel").on("hidden.bs.collapse", function() {
            $('#requisicionesParcialmenteAtendidasTable').DataTable().ajax.reload();
        });
        
        $("#atendidasPanel").on("show.bs.collapse", function() {
            $('#requisicionesAtendidasTable').DataTable().ajax.reload();
        });
        
        $("#atendidasPanel").on("hidden.bs.collapse", function() {
            $('#requisicionesAtendidasTable').DataTable().ajax.reload();
        });
        
        $("#consultasPanel").on("show.bs.collapse", function() {
            $("#statusCons").val("0");
            $("#idProyectoCons").empty();
            $("#idProyectoConsValue").val("-2");
            muestraOcultaProyecto();
            $('#requisicionesConsultaTable').DataTable().ajax.reload();
        });
        
        $("#parcialmenteCanceladasPanel").on("hidden.bs.collapse", function() {
            $('#requisicionesParcialmenteCanceladasTable').DataTable().ajax.reload();
        });
        
        $("#canceladasPanel").on("show.bs.collapse", function() {
            $('#requisicionesCanceladasTable').DataTable().ajax.reload();
        });
        
        $("#canceladasPanel").on("hidden.bs.collapse", function() {
            $('#requisicionesCanceladasTable').DataTable().ajax.reload();
        });
    });
</script>