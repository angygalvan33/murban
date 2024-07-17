<div class="row">
    <div class="col-md-4">
        <label for="metodosPagoCtrl">Métodos de pago</label>
        <br>
        <select class="form-control multis" id="metodosPagoCtrl" name="metodosPagoCtrl" multiple="multiple" style="width:100% !important">
        </select>
    </div>
    <div class="col-md-4">
        <label for="metodosCobroCtrl">Métodos de cobro</label>
        <br>
        <select class="form-control multis" id="metodosCobroCtrl" name="metodosCobroCtrl" multiple="multiple" style="width:100% !important">
        </select>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="fechasFiltro">
            </div>
        </div>
        <input type="hidden" id="fIni" value="-1">
        <input type="hidden" id="fFin" value="-1">
    </div>
</div>
<div class="row">
    <div class="col-md-11 text-right">
        <button id="descargaReporte" type="button" class="btn btn-success" onclick="DescargarReporte()" disabled="disabled">Descargar</button>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaFechas();
        llenaMetodosPago();
        llenaMetodosCobro();
        
        $('.multis').select2().on("change", function (e) {
            if ($("#metodosPagoCtrl").val().length === 0 && $("#metodosCobroCtrl").val().length === 0)
                $("#descargaReporte").prop("disabled", true);
            else
                $("#descargaReporte").prop("disabled", false);
        });
    });
    
    function inicializaFechas() {
        var fIni = moment().subtract(7, "days").format("YYYY-MM-DD");
        var fFin = moment().format("YYYY-MM-DD");
        $("#fIni").val(fIni);
        $("#fFin").val(fFin);

        $('#fechasFiltro').daterangepicker( {
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
            $("#fIni").val(fIni);
            $("#fFin").val(fFin);
        });

        $('#fechasFiltro').val();
    }
    
    function DescargarReporte() {
        var mP = $("#metodosPagoCtrl").val();
        var mC = $("#metodosCobroCtrl").val();
        var fIni = $("#fIni").val();
        var fFin = $("#fFin").val();
        
        window.location.href = "/cyg/excel/reportes/reporteCuentasPorPagar.php?metodosPago="+ mP +"&metodosCobro="+ mC +"&fIni="+ fIni +"&fFin="+ fFin;
    }
    
    function llenaMetodosPago() {
        var datos = { "accion":'getMetodosPago' };

        $.post("./pages/cuentasPorPagar/reporte/datos.php", datos, function(result) {
            $.each(result, function(i, val) {
                $("#metodosPagoCtrl").append($("<option>", {
                    value: val.IdMetodoPago,
                    text: val.Nombre
                }));
            });
        }, "json");
    }
    
    function llenaMetodosCobro() {
        var datos = { "accion":'getMetodosCobro' };

        $.post("./pages/cuentasPorPagar/reporte/datos.php", datos, function(result) {
            $.each(result, function(i, val) {
                $("#metodosCobroCtrl").append($("<option>", {
                    value: val.IdMetodoCobro,
                    text: val.Nombre
                }));
            });
        }, "json");
    }
</script>