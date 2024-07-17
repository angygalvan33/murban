<script src="pages/almacen/movimientos/movimientos.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label>Tipo de movimiento</label>
            <br>
            <select id="tipoMovimientoInv" name="tipoMovimientoInv" class="form-control" required="" >
                <option value="0" selected="selected">Todos</option>
                <option value="1">Entradas</option>
                <option value="2">Salidas</option>
                <option value="3">Traspaso</option>
                <option value="4">Ajuste</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="fechasFiltroInv">
            </div>
        </div>
        <input type="hidden" id="fIniInv" value="-1">
        <input type="hidden" id="fFinInv" value="-1">
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-group">
                <label>Personal</label>
                <br/>
                <select name="idPersonalInv" id="idPersonalInv" class="form-control" required="" style="width:100% !important">
                </select>
                <input type="hidden" name="idPersonalInvValue" id="idPersonalInvValue">
            </div>
        </div>
    </div>
    <div class="col-md-3"> 
        <div class="form-group">
            <div class="form-group">
                <label>Proyecto</label>
                <br/>
                <select name="idProyectoInv" id="idProyectoInv" class="form-control" required="" style="width:100% !important">
                </select>
                <input type="hidden" name="idProyectoInvValue" id="idProyectoInvValue">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-group">
                <label>Material</label>
                <br/>
                <select name="idMaterialInv" id="idMaterialInv" class="form-control" required="" style="width:100% !important">
                </select>
                <input type="hidden" name="idMaterialInvValue" id="idMaterialInvValue">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-group">
                <label>Categoría</label>
                <br/>
                <select name="idCategoriaInv" id="idCategoriaInv" class="form-control" required="" style="width:100% !important">
                </select>
                <input type="hidden" name="idCategoriaInvValue" id="idCategoriaInvValue">
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 table-responsive">
    <table id="movimientosInventarioTable" class="table table-hover">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Material</th>
                <th>Categoria</th>
                <th>Fecha</th>
                <th>Proyecto</th>
                <th>Personal</th>
                <th>Usuario</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        llenaPersonalInv();
        llenaProyectosInv();
        llenaMaterialesInv();
        llenaCategoriasInv();
        
        $("#idPersonalInvValue").val("-1");
        $("#idProyectoInvValue").val("-2");
        $("#idMaterialInvValue").val("-1");
        $("#idCategoriaInvValue").val("-1");
        
        inicializaFechasInv();
        inicializaTablaMovimientosInventario();
        
        $("#idPersonalInv").change(function() {
            var dataU = $('#idPersonalInv').select2('data');
           
            if (dataU.length > 0)
                $("#idPersonalInvValue").val($("#idPersonalInv").val());
            else
                $("#idPersonalInvValue").val("-1");
           
            $('#movimientosInventarioTable').DataTable().ajax.reload();
	    });
        
        $("#idProyectoInv").change(function() {
            var dataU = $('#idProyectoInv').select2('data');
           
            if (dataU.length > 0)
                $("#idProyectoInvValue").val($("#idProyectoInv").val());
            else
                $("#idProyectoInvValue").val("-2");
           
            $('#movimientosInventarioTable').DataTable().ajax.reload();
	    });
        
        $("#idMaterialInv").change(function() {
            var dataU = $('#idMaterialInv').select2('data');
           
            if (dataU.length > 0)
                $("#idMaterialInvValue").val($("#idMaterialInv").val());
            else
                $("#idMaterialInvValue").val("-1");
           
            $('#movimientosInventarioTable').DataTable().ajax.reload();
	    });
        
        $("#idCategoriaInv").change(function() {
            var dataU = $('#idCategoriaInv').select2('data');
           
            if (dataU.length > 0)
                $("#idCategoriaInvValue").val($("#idCategoriaInv").val());
            else
                $("#idCategoriaInvValue").val("-1");
           
            $('#movimientosInventarioTable').DataTable().ajax.reload();
	    });
        
        $("#tipoMovimientoInv").change(function() {
            $('#movimientosInventarioTable').DataTable().ajax.reload();
	    });
    });

    function inicializaFechasInv() {
        var fIni = moment().subtract(7, "days").format("YYYY-MM-DD");
        var fFin = moment().format("YYYY-MM-DD");
        $("#fIniInv").val(fIni);
        $("#fFinInv").val(fFin);

        $('#fechasFiltroInv').daterangepicker( {
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
            $("#fIniInv").val(fIni);
            $("#fFinInv").val(fFin);

            $('#movimientosInventarioTable').DataTable().ajax.reload();
        });
    }
</script>