<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';  
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<script src="pages/cuentasPorCobrar/cuentasPorCobrarScript.js" type="text/javascript"></script>

<h3>CUENTAS POR COBRAR</h3>
<div id="dTotalCobrarSinFac"></div>
<div id="dTotalCobrarConFac"></div>
<div id="dTotalCobro"></div>
<div class="row">
    <div class="col-md-9">
        <div id="dtcobro"></div>
    </div>
</div>
<br/>
<div class="panel-group" id="accordion">
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#cxcGeneralPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CUENTAS POR COBRAR
                </h4>
            </div>
        </a>
        <div id="cxcGeneralPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadGeneralPanel">
                    <?php
                        include 'general/cxcGeneral.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#detalleCobrosGeneralPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    DETALLE DE COBROS
                </h4>
            </div>
        </a>
        <div id="detalleCobrosGeneralPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadGeneralPanel">
                    <?php
                        include 'detalleCobros/cxcDetalleGeneral.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include './pagarModal.php';
    include './modalFacurar.php';
?>
<script type="text/javascript">
    tcobro = 0;
    dTotalCobrarSinFac_ = 0;
    dTotalCobrarConFac_ = 0;
    dTotalCobro_ = 0;
    
    $( document ).ready( function() {
        getTotalesCobro();
        
        $("#cxcGeneralPanel").on("show.bs.collapse", function() {
            $('#cxcTable').DataTable().ajax.reload();
        });
        
        $("#detalleCobrosGeneralPanel").on("show.bs.collapse", function() {
            $('#cxcDetalleCobrosTable').DataTable().ajax.reload();
        });
    });
</script>