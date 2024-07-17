<script src="pages/requisiciones/consultas/consultas.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-group">
                <label>Proyecto</label>
                <br/>
                <select name="idProyectoCons" id="idProyectoCons" class="form-control" required="" style="width:100% !important">
                </select>
                <input type="hidden" name="idProyectoConsValue" id="idProyectoConsValue">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Status</label>
            <br>
            <select id="statusCons" name="statusCons" class="form-control" required="">
                <option value="0" selected="selected">Todos</option>
                <option value="PENDIENTE">Pendiente</option>
                <option value="PARCIALMENTE ATENDIDA">Parcialmente atendida</option>
                <option value="ATENDIDA">Atendida</option>
                <option value="PARCIALMENTE CANCELADA">Parcialmente cancelada</option>
                <option value="CANCELADA">Cancelada</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <br>
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="descargaConsultaRequisicion()"><i class="fa fa-download"></i>&nbsp;Descargar reporte</button>
    </div>
</div>
<div class="col-md-12 table-responsive">
    <table id="requisicionesConsultaTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Proyecto</th>
                <th>Cantidad Solicitada</th>
                <th>Cantidad Atendida</th>
                <th>Material</th>
                <th>Unidad</th>
                <th>Solicita</th>
                <th>Status</th>
                <th>Creado</th>
                <th>Fecha requerida</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        llenaProyectosCons();
        
        $("#idProyectoConsValue").val("-2");
        
        inicializaFechasCons();
        inicializaTablaConsultasReq();
        
        $("#idProyectoCons").change( function() {
            var dataU = $('#idProyectoCons').select2('data');

            if (dataU.length > 0)
                $("#idProyectoConsValue").val($("#idProyectoCons").val());
            else
                $("#idProyectoConsValue").val("-2");
            
            muestraOcultaProyecto();
            $('#requisicionesConsultaTable').DataTable().ajax.reload();
	    });
        
        $("#statusCons").change( function() {
            $('#requisicionesConsultaTable').DataTable().ajax.reload();
        });
    });
    
    function inicializaFechasCons() {
        var fIni = moment().subtract(7, "days").format("YYYY-MM-DD");
        var fFin = moment().format("YYYY-MM-DD");
        $("#fIniCons").val(fIni);
        $("#fFinCons").val(fFin);

        $('#fechasFiltroCons').daterangepicker( {
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
            $("#fIniCons").val(fIni);
            $("#fFinCons").val(fFin);
            $('#requisicionesConsultaTable').DataTable().ajax.reload();
        });
    }
    
    function descargaConsultaRequisicion() {
        var status = $("#statusCons").val();
        var s = 0;
        
        switch (status) {
            case "PENDIENTE":
                s = 1;
            break;
            case "PARCIALMENTE ATENDIDA":
                s = 2;
            break;
            case "ATENDIDA":
                s = 3;
            break;
            case "PARCIALMENTE CANCELADA":
                s = 4;
            break;
            case "CANCELADA":
                s = 5;
            break;
            default:
                s=0;
            break;
        }
        
        window.open('html2pdf-master/reportes/reporteRequisiciones.php?id='+ $("#idProyectoConsValue").val() +'&s='+ s, '_blank');
    }
</script>