<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<script src="pages/compras/ocompraScript_Especial.js" type="text/javascript"></script>
<script src="pages/cuentasPorPagar/cuentasPorPagarScript.js" type="text/javascript"></script>
<link href="pages/cuentasPorPagar/cuentasPorPagarStyles.css" rel="stylesheet" type="text/css"/>

<h3>CUENTAS POR PAGAR</h3>
<div id="dt"></div>
<div id="dtpropuesto"></div>
<div id="dtautorizado"></div>

<a href='html2pdf-master/reportes/reportePropuestas.php' target='_blank'>
    <button type="button" class="btn btn-success btn-sm">Propuestas de Pago PDF</button>
</a>
<a href='excel/reportes/reportePropuestas.php' target='_blank'>
    <button type="button" class="btn btn-info btn-sm">Excel</button>
</a><br><br>
<a href='html2pdf-master/reportes/reportePropuestasProveedor.php' target='_blank'>
    <button type="button" class="btn btn-success btn-sm">Propuestas de Pago Proveedor PDF</button>
</a>
<a href='excel/reportes/reportePropuestasProveedor.php' target='_blank'>
    <button type="button" class="btn btn-info btn-sm">Excel</button>
</a>

<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="mostrarOcultarNuevaOC_Especial(1)"><i class="fa fa-plus"></i>&nbsp;Nueva CxP Especial</button>
    </div>
    <div class="col-md-12">
        <?php
            include_once '../../pages/compras/nuevaOC_Especial.php';
        ?>
    </div>
</div>
<div class="panel-group" id="accordion">
    <?php if ($permisos->acceso("8388608", $usuario->obtenerPermisos($_SESSION['username']))): ?>
        <div class="panel box box-success">
            <a data-toggle="collapse" data-parent="#accordion" href="#cxpGeneralPanel">
                <div class="box-header with-border headers">
                    <h4 class="box-title tituloPanel">
                        CUENTAS POR PAGAR
                    </h4>
                </div>
            </a>
            <div id="cxpGeneralPanel" class="panel-collapse collapse">
                <div class="box-body">
                    <div id="loadGeneralPanel">
                        <?php
                            include 'general/cuentasPorPagar.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($permisos->acceso("16777216", $usuario->obtenerPermisos($_SESSION['username']))): ?>
        <div class="panel box box-warning">
            <a data-toggle="collapse" data-parent="#accordion" href="#cxpByProveedorPanel">
                <div class="box-header with-border headers">
                    <h4 class="box-title tituloPanel">
                        CUENTAS POR PAGAR POR PROVEEDOR
                    </h4>
                </div>
            </a>
            <div id="cxpByProveedorPanel" class="panel-collapse collapse">
                <div class="box-body">
                    <div id="loadCxPByProveedorPanel">
                        <?php
                            include 'cuentasPorProveedor/cuentasPorPagar.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#cxpPendientesAutPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CUENTAS PENDIENTES DE AUTORIZACIÓN
                </h4>
            </div>
        </a>
        <div id="cxpPendientesAutPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadCxPPendientesAutPanel">
                    <?php
                        include 'pendientesAutorizacion/cuentasPorPagar.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#cxpPendientesPagoPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CUENTAS PENDIENTES DE PAGO
                </h4>
            </div>
        </a>
        <div id="cxpPendientesPagoPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadCxPPendientesPagoPanel">
                    <?php
                        include 'pendientesPago/cuentasPorPagar.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#cxpEsperaFacturacionPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    CUENTAS EN ESPERA DE FACTURACIÓN
                </h4>
            </div>
        </a>
        <div id="cxpEsperaFacturacionPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadCxPEsperaFacturacionPanel">
                    <?php
                        include 'esperaFacturacion/cuentasPorPagar.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-info">
        <a data-toggle="collapse" data-parent="#accordion" href="#reportePanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    REPORTE
                </h4>
            </div>
        </a>
        <div id="reportePanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadReportePanel">
                    <?php
                        include 'reporte/reporte.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include 'pagarModal.php';
?>
<script type="text/javascript">
    tpropuesto = 0;
    tautorizado = 0;
    
    $( document ).ready( function() {
        $(".facturaEspecial").css("display", "block");
        $("#tipoPagoEspecial").val(0);
        $("#tipoPagoEspecial").attr("disabled", true);
        mostrarOcultarNuevaOC_Especial(0);
        $("#tipoOCEspecial").val(2);
        getTotalesCxP();
        
        $("#cxpGeneralPanel").on("show.bs.collapse", function() {
            $('#cxpTable').DataTable().ajax.reload();
        });
        
        $("#cxpByProveedorPanel").on("show.bs.collapse", function() {
            $('#cxpByProveedorTable').DataTable().ajax.reload();
        });
        
        $("#cxpPendientesAutPanel").on("show.bs.collapse", function() {
            $('#cxpPendientesAutorizar').DataTable().ajax.reload();
        });
        
        $("#cxpPendientesPagoPanel").on("show.bs.collapse", function() {
            $('#cxpPendientesPago').DataTable().ajax.reload();
        });
        
        $("#cxpEsperaFacturacionPanel").on("show.bs.collapse", function() {
            $('#cxpEsperaFacturacion').DataTable().ajax.reload();
        });
    });
</script>