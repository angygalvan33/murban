<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<h3>UBICACIONES</h3>
<br>
<div class="panel-group" id="accordion">
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#catalogoUbicaciones">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CATÁLOGO DE UBICACIONES
                </h4>
            </div>
        </a>
        <div id="catalogoUbicaciones" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadUbicaciones">
                    <?php
                        include 'ubicaciones/ubicaciones.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#ubicacionesMaterial">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    UBICACIÓN DE MATERIAL
                </h4>
            </div>
        </a>
        <div id="ubicacionesMaterial" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadUbicacionesMaterial">
                    <?php
                        include 'ubicacionMaterial/ubicacionMaterial.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>