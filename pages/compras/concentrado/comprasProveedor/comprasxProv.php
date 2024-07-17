<script src="pages/compras/concentrado/comprasProveedor/comprasxProvScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-2">
        <label for="proveedorCtrl">Proveedor</label>
        <br>
        <select class="form-control multisp" id="proveedorCtrl" name="proveedorCtrl" style="width:100% !important"></select>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="fechasFiltroCxP">
            </div>
        </div>
        <input type="hidden" id="fInicxp" value="-1">
        <input type="hidden" id="fFincxp" value="-1">
        <br>
    </div>
    <div class="col-md-2">
        <label for="estadoCtrl">Estado</label>
        <br>
        <select id="estadoCtrl" name="estadoCtrl" class="form-control" style="width:100% !important">
            <option value="0">Seleccionar opción...</option>
            <option value="1">Activa</option>
            <option value="5">Cancelada</option>
        </select>
    </div>
    <div class="col-md-2">
        <br>
        <button id="descargacxprov" type="button" class="btn btn-success" disabled="disabled">Exportar a Excel</button>
    </div>
    <div class="col-md-3"><br>
        <h4>
            <label>Saldo:</label>&nbsp;<label id="saldo" name="saldo"></label>
        </h4>
    </div>
</div>

<div class="col-md-12">
    <table id="comprasxProvTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Folio OC</th>
                <th>Factura</th>
                <th>Fecha Factura</th>
                <th>Proveedor</th>
                <th>Total</th>
                <th>Pago</th>
                <th>Fecha Pago</th>
                <th>Saldo</th>
                <th>Genera</th>
                <th>Autoriza</th>
                <th>Estado</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var idEstado = 0;
    var idProveedor = 0;
    var fIni = null;
    var fFin = null;
    var saldo = 0;

    $( document ).ready(function() {
        inicializaComprasxProvTable(fIni, fFin, idProveedor, idEstado);
        inicializaFechasCxP();
        llenaProveedores();
        obtenerSaldo(fIni, fFin, idProveedor, idEstado);

        $("#estadoCtrl").change( function() {
            idEstado = $(this).children("option:selected").val();
            inicializaComprasxProvTable(fIni, fFin, idProveedor, idEstado);
            obtenerSaldo(fIni, fFin, idProveedor, idEstado);
        });

        $("#proveedorCtrl").change(function() {
            var dataP = $('#proveedorCtrl').select2('data');

            if (dataP.length > 0) {
                idEstado = 0;
                idProveedor = dataP[0].id;
                inicializaFechasCxP();
                inicializaComprasxProvTable(fIni, fFin, idProveedor, idEstado);
                $("#descargacxprov").prop("disabled", false);
            }
            else {
                idEstado = 0;
                idProveedor = 0;
                inicializaFechasCxP();
                inicializaComprasxProvTable(fIni, fFin, idProveedor, idEstado);
                $("#descargacxprov").prop("disabled", true);
            }
            obtenerSaldo(fIni, fFin, idProveedor, idEstado);
            $("#estadoCtrl option[value='0']").prop('selected', true);
        });

        $( "#descargacxprov" ).click(function() {
            descargacxprov();
        });
    });

    function inicializaFechasCxP() {
        $('#fechasFiltroCxP').daterangepicker( {
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
            fIni = start.format('YYYY-MM-DD');
            fFin = end.format('YYYY-MM-DD');
            $("#fInicxp").val(fIni);
            $("#fFincxp").val(fFin);
            $("#descargagxc").prop("disabled", false);

            inicializaComprasxProvTable(fIni, fFin, idProveedor, idEstado);
            llenaProveedores();
            obtenerSaldo(fIni, fFin, idProveedor, idEstado);
        });

        $('#fechasFiltroCxP').val('');
    }
    
    function descargacxprov() {
        var fIni = $("#fInicxp").val();
        var fFin = $("#fFincxp").val();
        var saldo = $("#saldo").text();

        window.location.href = "./excel/reportes/reporteComprasxProv.php?fIni="+ fIni +"&fFin="+ fFin +"&idProveedor="+ idProveedor +"&idEstado="+ idEstado +"&saldo="+ saldo;
    }
</script>