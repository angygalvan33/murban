<style type="text/css">
    .detalles {
        padding: 5px !important;
    }
</style>

<h3>REPORTES</h3>
<div class="panel-group" id="accordion">
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#historicoOC">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    HISTÓRICO OC
                </h4>
            </div>
        </a>
        <div id="historicoOC" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadhistoricoOC">
                <?php
                    include 'historicoOC/historicoOC.php';
                ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#gastosxcategoria">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    GASTOS POR CATEGORÍA
                </h4>
            </div>
        </a>
        <div id="gastosxcategoria" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadgastosxcategoria">
                <?php
                    include 'gastosxcat/gastosxcat.php';
                ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#bitacora">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    BITÁCORA DE COMPRAS
                </h4>
            </div>
        </a>
        <div id="bitacora" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadbitacora">
                <?php
                    include 'bitacora/bitacora.php';
                ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#requisicionxfecha">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    REQUISICIONES POR FECHA
                </h4>
            </div>
        </a>
        <div id="requisicionxfecha" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadrequisicionxfecha">
                <?php
                    include 'requisicionesmaterial/requisicionesxfecha.php';
                ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#comprasxprovPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    COMPRAS POR PROVEEDOR
                </h4>
            </div>
        </a>
        <div id="comprasxprovPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadComprasxprov">
                    <?php
                        include 'comprasProveedor/comprasxProv.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>