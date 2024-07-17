<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';  
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/materialesEnPrestamo/materialesEnPrestamo.js" type="text/javascript"></script>

<h3>PRÉSTAMO Y RESGUARDO</h3>
<br>
<div class="row">
    <div class="col-md-6">
        <button class="btn btn-success btn-sm" id="reporteMaterialPrestamo">Descargar Material en Préstamo</button>
    </div>
    <div class="col-md-4"></div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalNuevoMaterialPrestamoResguardo()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <div class="col-md-12">
        <?php
            include 'nuevoPrestamoMaterial.php';
            include 'recibirModal.php';
        ?>
    </div>
</div>
<div class="panel-group" id="accordion">
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#materialEnPrestamo">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    MATERIAL EN PRÉSTAMO
                </h4>
            </div>
        </a>
        <div id="materialEnPrestamo" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMaterialPrestamo">
                    <?php
                        include 'materialEnPrestamo/materialEnPrestamo.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#materialEnResguardo">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    MATERIAL EN RESGUARDO
                </h4>
            </div>
        </a>
        <div id="materialEnResguardo" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMaterialResguardo">
                    <?php
                        include 'materialEnResguardo/materialEnResguardo.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#movimientosResguardo">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    MOVIMIENTOS
                </h4>
            </div>
        </a>
        <div id="movimientosResguardo" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadMovimientosResguardo">
                    <?php
                        include 'movimientosResguardo/movimientosResguardo.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#formUbicacion").validate({ });

        $("#reporteMaterialPrestamo").on('click', function() {
            window.open('html2pdf-master/reportes/reporteMaterialesPrestamo.php', '_blank');
        });
        //al abrir
        $("#materialEnPrestamo").on("show.bs.collapse", function() {
            $('#MaterialPrestamoPTabla').DataTable().ajax.reload();
        });
        
        $("#materialEnResguardo").on("show.bs.collapse", function() {
            $('#PersonalResguardoTabla').DataTable().ajax.reload();
        });
        
        $("#movimientosResguardo").on("show.bs.collapse", function() {
            $('#movimientosPrestamoTable').DataTable().ajax.reload();
        });
        //al cerrar
        $("#materialEnPrestamo").on("hidden.bs.collapse", function() {
            $('#MaterialPrestamoPTabla').DataTable().ajax.reload();
        });
        
        $("#materialEnResguardo").on("hidden.bs.collapse", function() {
            $('#PersonalResguardoTabla').DataTable().ajax.reload();
        });

        $("#movimientosResguardo").on("hidden.bs.collapse", function() {
            $('#movimientosPrestamoTable').DataTable().ajax.reload();
        });
    });
    
    function openModalNuevoMaterialPrestamoResguardo() {
        resetValuesMaterialesPrestamo();
        $("#accion").val(0); //0 nuevo, 1 editar
        $("#tipo").val(-1); //1 prestamo, 2 resguardo
        $("#nuevoPrestamoModal").modal("show");
    }
</script>