<script src="./pages/compras/concentrado/bitacora/consultasBitacora.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-3">
        <label for="proveedorBit">Proveedor</label>
        <br>
        <select class="form-control proveedorBit" id="proveedorBit" name="proveedorBit" style="width:100% !important">
        </select>
    </div>
    <div class="col-md-3">
        <label for="materialCtrl">Material</label>
        <br>
        <select class="form-control materialCtrl" id="materialCtrl" name="materialCtrl" style="width:100% !important">
        </select>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha de requerimiento:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="fechasBitacora">
            </div>
        </div>
        <input type="hidden" id="fIniB" value="-1">
        <input type="hidden" id="fFinB" value="-1">
        <input type="hidden" id="idProveedor" value="0">
        <input type="hidden" id="idMaterial" value="0">
        <br>
    </div>
    <div class="col-md-3">
        <br>
        <button id="descargabitacora" type="button" class="btn btn-success">Exportar a Excel</button>
    </div>
</div>
<div class="col-md-12 table-responsive">
    <table id="bitacoraConsultaTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio Req</th>
                <th>Requerida para</th>
                <th>Material</th>
                <th>Cantidad Faltante</th>
                <th>Piezas</th>
                <th>Cantidad Atendida</th>
                <th>Folio OC</th>
                <th>Fecha Creación</th>
                <th>Proveedor</th>
                <th>Comprador</th>
                <th>Fecha de ingreso</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        var idproveedor = $("#idProveedor").val();
        var idmaterial = $("#idMaterial").val();

        llenaMaterialesBit();
        llenaProveedoresBit();
        inicializaFechasB();
        loadDataTableBitacora(idproveedor, idmaterial);

        $("#proveedorBit").change(function() {
            var dataP = $('#proveedorBit').select2('data');

            if (dataP.length > 0) {
                idproveedor = dataP[0].id;
                $("#idProveedor").val(idproveedor);
                loadDataTableBitacora(idproveedor, idmaterial);
            }
            else {
                idproveedor = 0;
                $("#idProveedor").val(0)
                loadDataTableBitacora(idproveedor, idmaterial);
            }
        });

        $("#materialCtrl").change(function() {
            var dataM = $('#materialCtrl').select2('data');
            
            if (dataM.length > 0) {
                idmaterial = dataM[0].id;
                $("#idMaterial").val(idmaterial);
                loadDataTableBitacora(idproveedor, idmaterial);
            }
            else {
                idmaterial = 0;
                $("#idMaterial").val(0);
                loadDataTableBitacora(idproveedor, idmaterial);
            }
        });
    });
    
    function inicializaFechasB() {
        var fIni = moment().subtract(7, "days").format("YYYY-MM-DD");
        var fFin = moment().format("YYYY-MM-DD");
        $("#fIniB").val(fIni);
        $("#fFinB").val(fFin);

        $('#fechasBitacora').daterangepicker( {
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
            var fIni = start.format('YYYY-MM-DD');
            var fFin = end.format('YYYY-MM-DD');
            $("#fIniB").val(fIni);
            $("#fFinB").val(fFin);
            
            //loadDataTableBitacora($("#idProveedor").val(), $("#idMaterial").val());
            $('#bitacoraConsultaTable').DataTable().ajax.reload();
        });
        
        //$('#fechasBitacora').val('');
    }

    $("#descargabitacora").click(function() {
        Descargarbit();
    });
    
    function Descargarbit() {
        var fIni = $("#fIniB").val();
        var fFin = $("#fFinB").val();
        
        window.location.href = "./excel/reportes/reporteBitacoraMateriales.php?fIni="+ fIni +"&fFin="+ fFin +"&idProveedor="+ $("#idProveedor").val();
    }
</script>