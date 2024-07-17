<link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="pages/articulos/articulosGeneralScript.js" type="text/javascript"></script>
<link href="pages/articulos/articulosGeneralStyles.css" rel="stylesheet" type="text/css"/>

<h3>CATÁLOGO DE PRODUCTOS</h3>
<div class="panel-group" id="accordion">
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#lineasPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    LÍNEAS
                </h4>
            </div>
        </a>
        <div id="lineasPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadLineas">
                    <?php
                        include 'lineas/lineas.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#articulosPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    PRODUCTOS
                </h4>
            </div>
        </a>
        <div id="articulosPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadArticulos">
                    <?php
                        include 'articulos/articulos.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    include '../../commonModals.html';
?>