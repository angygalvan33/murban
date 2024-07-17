<script src="pages/materialesEnPrestamo/movimientosResguardo/movimientosResguardoScript.js" type="text/javascript"></script>
<link href="pages/materialesEnPrestamo/movimientosResguardo/movimientosResguardoStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12 table-responsive">
    <table id="movimientosPrestamoTable" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Material</th>
                <th>Personal</th>
                <th>Acción</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        llenaPersonalMov();
        llenaMaterialesMov();
        inicializaFechasMov();
        loadDataTableMovimientosPrestamo();
        
        $("#idPersonalMovValue").val("-1");
        $("#idMaterialMovValue").val("-1");
        $("#idPersonalMov").change( function() {
            var dataU = $('#idPersonalMov').select2('data');
            
            if (dataU.length > 0)
                $("#idPersonalMovValue").val($("#idPersonalMov").val());
            else
                $("#idPersonalMovValue").val("-1");
            
            $('#movimientosPrestamoTable').DataTable().ajax.reload();
        });
        
        $("#idMaterialMov").change(function() {
            var dataU = $('#idMaterialMov').select2('data');
            
            if (dataU.length > 0)
                $("#idMaterialMovValue").val($("#idMaterialMov").val());
            else
                $("#idMaterialMovValue").val("-1");
            
            $('#movimientosPrestamoTable').DataTable().ajax.reload();
	    });
        
        $("#tipoMovimientoMov").change(function() {
            $('#movimientosPrestamoTable').DataTable().ajax.reload();
	    });
    });
    
    function inicializaFechasMov() {
        fIni = moment().subtract(7, "days").format("YYYY-MM-DD");
        fFin = moment().format("YYYY-MM-DD");
        $("#fIniMov").val(fIni);
        $("#fFinMov").val(fFin);
        
        $('#fechasFiltroMov').daterangepicker({
            opens: 'left',
            startDate: moment().subtract(7, "days"),
            endDate: moment(),
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
            fIni = start.format("YYYY-MM-DD");
            fFin = end.format("YYYY-MM-DD");
            $("#fIniMov").val(fIni);
            $("#fFinMov").val(fFin);
            $('#movimientosPrestamoTable').DataTable().ajax.reload();
        });
    }
</script>