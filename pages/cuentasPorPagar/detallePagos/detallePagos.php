<link href="pages/detallePagos/detallePagosStyles.css" rel="stylesheet" type="text/css"/>

<h3>DETALLE DE PAGOS</h3>
<br>
<div class="row">
    <div class="col-md-6">
        <button class="btn btn-success btn-sm" id="reportePagos">Reporte de pagos</button>
    </div>
    <div class="form-group col-md-6" align="right">
        <div style="margin-right: 84px">
            <input class='icheckbox_flat-green' type='checkbox' id="todosFiltro" name="todosFiltro" checked>
            <label>Ver todos los pagos</label>
        </div>
        <div style="margin-top: 10px">
            <label style="margin-right: 10px">Fecha:</label>
            <i class="fa fa-calendar"></i>
            <input type="text" id="fechasFiltro" disabled>
        </div>
        <input type="hidden" id="fIni" value="-1">
        <input type="hidden" id="fFin" value="-1">
    </div>
</div>
<div class="panel-group" id="accordion"> 
    <div class="panel box box-success">
        <a data-toggle="collapse" data-parent="#accordion" href="#detallePagosGeneralPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    PAGOS
                </h4>
            </div>
        </a>
        <div id="detallePagosGeneralPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadGeneralPanel">
                    <?php
                        include 'general/detallePagos.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-warning">
        <a data-toggle="collapse" data-parent="#accordion" href="#detallePagosByProveedorPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    PAGOS POR PROVEEDOR
                </h4>
            </div>
        </a>
        <div id="detallePagosByProveedorPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadProveedorPanel">
                    <?php
                        include 'detallePorProveedor/detallePagos.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel box box-primary">
        <a data-toggle="collapse" data-parent="#accordion" href="#detallePagosFoliosPanel">
            <div class="box-header with-border headers">
                <h4 class="box-title tituloPanel">
                    EDICIÓN DE FOLIOS
                </h4>
            </div>
        </a>
        <div id="detallePagosFoliosPanel" class="panel-collapse collapse">
            <div class="box-body">
                <div id="loadFoliosPanel">
                    <?php
                        include 'edicionFolios/detallePagos.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.daterange').daterangepicker();
    
    $( document ).ready( function() {
        inicializaFechas();
        
        $("#todosFiltro").change( function() {
            habilitarFiltrofechas();
        });
        
        $("#detallePagosGeneralPanel").on("show.bs.collapse", function() {
            $('#detallePagosTable').DataTable().ajax.reload();
        });
        
        $("#detallePagosByProveedorPanel").on("show.bs.collapse", function() {
            $('#detalleByProveedorTable').DataTable().ajax.reload();
        });
        
        $("#detallePagosFoliosPanel").on("show.bs.collapse", function() {
            $('#edicionFoliosTable').DataTable().ajax.reload();
        });
        
        $("#reportePagos").on('click', function() {
            fechas = '';
            if (!$('#todosFiltro').prop('checked')) {
                fechas = $("#fechasFiltro").val();
            }

            window.open('html2pdf-master/reportes/reportePagos.php?fechas='+ fechas, '_blank');
        });
    });
    
    function inicializaFechas() {
        $('#fechasFiltro').daterangepicker( {
            opens: 'left',
            "locale": {
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "fromLabel": "DE",
                "toLabel": "HASTA",
                "customRangeLabel": "Custom",
                "daysOfWeek": [
                    "Dom",
                    "Lun",
                    "Mar",
                    "Mié",
                    "Jue",
                    "Vie",
                    "Sáb"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            }
        },
        
        function(start, end, label) {
            var fIni = start.format('YYYY-MM-DD');
            var fFin = end.format('YYYY-MM-DD');

            $("#fIni").val(fIni);
            $("#fFin").val(fFin);
            
            $('#detallePagosTable').DataTable().ajax.reload();
            $('#detalleByProveedorTable').DataTable().ajax.reload();
        });

        $('#fechasFiltro').val('');
    }
    
    function habilitarFiltrofechas() {
        if ($('#todosFiltro').prop('checked')) {
            $("#fechasFiltro").prop("disabled", true);
            $('#fechasFiltro').val('');
            $("#fIni").val('-1');
            $("#fFin").val('-1');
            $('#detallePagosTable').DataTable().ajax.reload();
            $('#detalleByProveedorTable').DataTable().ajax.reload();
        }
        else
            $("#fechasFiltro").prop("disabled", false);
    }
</script>