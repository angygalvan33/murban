<script src="./pages/compras/concentrado/requisicionesmaterial/requisicionesxfecha.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha de requerimiento:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="fechasRequisiciones">
            </div>
        </div>
        <input type="hidden" id="fIniR" value="-1">
        <input type="hidden" id="fFinR" value="-1">
        <br>
    </div>
    <div class="col-md-2">
        <br>
        <button id="descargarequisiciones" type="button" class="btn btn-success">Exportar a Excel</button>
    </div>
</div>
<div class="col-md-12 table-responsive">
    <table id="requisicionesxFechaTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio Req</th>
                <th>Fecha Creación</th>
                <th>Fecha Requerida</th>
                <th>Usuario</th>
                <th>Proyecto</th>
                <th>Material</th>
                <th>Cantidad atendida</th>
                <th>Cantidad requerida</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaFechasR();
        loadDataTablerequisicionesxFecha(null, null);
    });

    function inicializaFechasR() {
        $('#fechasRequisiciones').daterangepicker( {
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
        
        function (start, end, label) {
            var fIni = start.format('YYYY-MM-DD');
            var fFin = end.format('YYYY-MM-DD');
            
            $("#fIniR").val(fIni);
            $("#fFinR").val(fFin);
            
            loadDataTablerequisicionesxFecha(fIni, fFin);
        });
        
        $('#fechasRequisiciones').val('');
    }

    $("#descargarequisiciones").click(function() {
        DescargarRequisiciones();
    });
    
    function DescargarRequisiciones() {
        var fIni = $("#fIniR").val();
        var fFin = $("#fFinR").val();
        
        window.location.href = "./excel/reportes/reporteRequisxFecha.php?fIni="+ fIni +"&fFin="+ fFin;
    }
</script>