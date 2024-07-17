<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<h3>CONFIGURACIÓN</h3>
<br>
<div class="panel-group" id="accordion">
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#inventarioInicial">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    INVENTARIO INICIAL
                </h4>
            </div>
        </a>
        <div id="inventarioInicial" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadinventarioInicial">
                    <?php
                        include 'inventarioInicial/inventarioInicial.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#movimientosMaximosMinimos">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    MÁXIMOS Y MÍNIMOS
                </h4>
            </div>
        </a>
        <div id="movimientosMaximosMinimos" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMaximosMinimos">
                    <?php
                        include 'maximosMinimos/maximosMinimos.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#conciliacioninventario">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CONCILIACIÓN DE INVENTARIO
                </h4>
            </div>
        </a>
        <div id="conciliacioninventario" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMaximosMinimos">
                    <?php
                        include 'conciliacion/conciliacion.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#ajustesinventario">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    AJUSTE DE INVENTARIO
                </h4>
            </div>
        </a>
        <div id="ajustesinventario" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMaximosMinimos">
                    <?php
                        include 'ajuste/ajuste.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#consultaajustes">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CONSULTA DE AJUSTES
                </h4>
            </div>
        </a>
        <div id="consultaajustes" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMaximosMinimos">
                    <?php
                        include 'consultaconciliacion/consulta.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>