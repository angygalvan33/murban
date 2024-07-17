<script src="pages/cajaChica/controlUsuario/controlUsuario.js" type="text/javascript"></script>
<link href="pages/cajaChica/cajaChicaStyles.css" rel="stylesheet" type="text/css"/>

<div class="row">
    <div class="col-md-4"> 
        <div class="form-group">
            <label>Usuario</label>
            <br>
            <select id="usuarioCU" name="usuarioCU" class="form-control usuarioCU" required="">
                <?php if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                    <option value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" selected="selected"><?php echo $usuario->getNameFromUsername($_SESSION['username']) ?></option>
                <?php endif; ?>
            </select>
            <input type="hidden" name="usuarioCUValue" id="usuarioCUValue">
        </div>
    </div>
    <div class="col-md-4">
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
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Tipo de movimiento</label>
            <br>
            <select id="tipoMovimiento" name="tipoMovimiento" class="form-control" required="">
                <option value="0" selected="selected">Todos</option>
                <option value="1">Entradas</option>
                <option value="2">Salidas</option>
            </select>
        </div>
    </div>
    <div class="col-md-5"></div>
    <div class="col-md-3">
        <div id="pCCh" style="text-align: left"></div>
    </div>
</div>
<div class="col-md-12 table-responsive">
    <table id="usuarioCChTable" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Proyecto</th>
                <th>Material</th>
                <th>Folio de factura</th>
                <th>Cantidad</th>
                <th>Reembolsado</th>
                <th>Acción</th>
                <th>Tipo de abono</th>
                <th>Descripcion</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $("#usuarioCUValue").val("-2");
        $("#nuevaComprausuario").prop("disabled", true);
        autoCompleteUsuarios($('.usuarioCU'), 'IN');
        
        <?php if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            $("#usuarioCUValue").val( <?php echo $usuario->getIdFromUsername($_SESSION['username']) ?> );
            $("#usuarioCU").prop("disabled", true);
            $("#nuevaComprausuario").prop("disabled", false);
        <?php endif; ?>
        
        getGastadoDeUsuario($("#usuarioCUValue").val());
        loadDataTableControlUsuarios();
        inicializaFechas();
        
        $("#usuarioCU").change(function() {
            var dataU = $('#usuarioCU').select2('data');
           
            if (dataU.length > 0) {
                $("#usuarioCUValue").val($("#usuarioCU").val());
                getGastadoDeUsuario($("#usuarioCUValue").val());
                $("#nuevaComprausuario").prop("disabled", false);
            }
            else
                $("#usuarioCUValue").val("-1");
            $('#usuarioCChTable').DataTable().ajax.reload();
	    });

        $("#tipoMovimiento").change(function() {
            $('#usuarioCChTable').DataTable().ajax.reload();
	    });
    });
    
    function inicializaFechas() {
        fIni = moment().subtract(7, "days").format("YYYY-MM-DD");
        fFin = moment().format("YYYY-MM-DD");

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
            
            $('#usuarioCChTable').DataTable().ajax.reload();
        });
        
        $('#fechasFiltro').val();
    }
    
    function filtrarMovimientos() {
        $('#fechasFiltro').val('');
        $("#fIni").val('-1');
        $("#fFin").val('-1');
        $('#usuarioCChTable').DataTable().ajax.reload();
    }
</script>