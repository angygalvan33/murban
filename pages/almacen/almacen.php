<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    include_once '../../clases/panelAdmin.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
    $panel = new PanelAdmin();
    include_once '../../pages/material/nuevoMaterial.php';
    include '../../commonModals.html';
?>

<script src="pages/almacen/almacen.js" type="text/javascript"></script>
<link href="pages/almacen/almacen.css" rel="stylesheet" type="text/css"/>

<h3>MOVIMIENTOS</h3>
<br>
<div class="panel-group" id="accordion">
    <?php if(($_SESSION['username']) != 'Josue' && ($_SESSION['username']) != 'Roberto') : ?>
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#consultaStockAlmacen">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CONSULTA STOCK
                </h4>
            </div>
        </a>
        <div id="consultaStockAlmacen" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadStockAlmacen">
                    <?php
                        include 'stock/stock.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($permisos->acceso("131072", $usuario->obtenerPermisos($_SESSION['username']))): ?>
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#salidasAlmacen">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    SALIDAS
                </h4>
            </div>
        </a>
        <div id="salidasAlmacen" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadSalidasAlmacen">
                    <?php
                        include 'salidas/salidas.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#OCEnEsperaAlmacen">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    RECEPCIÓN DE MATERIALES
                </h4>
            </div>
        </a>
        <div id="OCEnEsperaAlmacen" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadOCEsperaRecepcion">
                    <?php
                        include 'OCEsperaRecepcion/OCEsperaRecepcion.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#movimientosAlmacen">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CONSULTAS
                </h4>
            </div>
        </a>
        <div id="movimientosAlmacen" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMovimientos">
                    <?php
                        include 'movimientos/movimientos.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        //reload datatables al abrir paneles
        $("#salidasAlmacen").on("show.bs.collapse", function() {
            $('#salidasTabla').DataTable().ajax.reload();
        });
        
        $("#consultaStockAlmacen").on("show.bs.collapse", function() {
            $('#stockTabla').DataTable().ajax.reload();
        });
       
        $("#OCEnEsperaAlmacen").on("show.bs.collapse", function() {
            $('#ocEsperaTable').DataTable().ajax.reload();
        });
        
        $("#movimientosAlmacen").on("show.bs.collapse", function() {
            $('#movimientosInventarioTable').DataTable().ajax.reload();
        });
        
        $("#salidasAlmacen").on("hidden.bs.collapse", function() {
            $('#salidasTabla').DataTable().ajax.reload();
        });
        
        $("#consultaStockAlmacen").on("hidden.bs.collapse", function() {
            $('#stockTabla').DataTable().ajax.reload();
        });
        
        $("#OCEnEsperaAlmacen").on("hidden.bs.collapse", function() {
            $('#ocEsperaTable').DataTable().ajax.reload();
        });
       
        $("#movimientosAlmacen").on("hide.bs.collapse", function() {
            $('#movimientosInventarioTable').DataTable().ajax.reload();
        });
    });
</script>