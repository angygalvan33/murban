<script src="pages/cuentasPorCobrar/detalleCobros/cxcDetalleGeneralScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-6">
        <button id="descargadc" type="button" class="btn btn-success" >Exportar a Excel</button>
    </div>
    <div class="form-group col-md-6" align="right">
        <div style="margin-right: 84px">
            <input class='icheckbox_flat-green' type='checkbox' id="todosFiltro" name="todosFiltro" checked>
            <label>Ver todos los cobros</label>
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
<div class="col-md-12 table-responsive">
    <table id="cxcDetalleCobrosTable" class="table table-hover">
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>Cliente</th>
                <th>Folio Factura</th>
                <th>Monto (MXN)</th>
                <th>Tipo cobro</th>
                <th>Fecha Cobro</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTableGeneral2();
        inicializaFechas();
        
        $("#todosFiltro").change( function() {
            habilitarFiltrofechas();
        });
		
		$("#descargadc").click( function() {
            DescargaRepDC();
        });
    });
    
	function DescargaRepDC() {
		fIni = $("#fIni").val();
		fFin = $("#fFin").val();
		
		window.location.href = "./excel/reportes/rdetalledecobros.php?fIni="+ fIni +"&fFin="+ fFin;
	}
	
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
            
            $('#cxcDetalleCobrosTable').DataTable().ajax.reload();
        });
        
        $('#fechasFiltro').val('');
    }
    
    function habilitarFiltrofechas() {
        if ($('#todosFiltro').prop('checked')) {
            $("#fechasFiltro").prop( "disabled", true);
            $('#fechasFiltro').val('');
            $("#fIni").val('-1');
            $("#fFin").val('-1');
            
            $('#cxcDetalleCobrosTable').DataTable().ajax.reload();
        }
        else
            $("#fechasFiltro").prop("disabled", false);
    }
</script>