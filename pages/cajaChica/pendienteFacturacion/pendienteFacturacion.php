<?php
    include_once 'pagosPendientes/nuevaCompra.php';
?>
<script src="pages/cajaChica/pendienteFacturacion/pendienteFacturacion.js" type="text/javascript"></script>
<link href="pages/cajaChica/cajaChicaStyles.css" rel="stylesheet" type="text/css"/>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Usuario</label>
            <br>
            <select id="ppusuarioPf" name="ppusuarioPf" class="form-control ppusuarioPf" required="">
                <?php if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                    <option value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" selected="selected"><?php echo $usuario->getNameFromUsername($_SESSION['username']) ?></option>
                <?php endif; ?>
            </select>
            <input type="hidden" name="ppusuarioPfValue" id="ppusuarioPfValue">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-9">
    </div>
    <div class="col-md-3">
        <div id="ppCCh" style="text-align: left"></div>
    </div>
</div>

<div class="col-md-12 table-responsive">
    <table id="ppusuarioPfCChTable" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Proyecto</th>
                <th>Material</th>
                <th>Folio de factura</th>
                <th>Fecha de factura</th>
                <th>Cantidad</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<?php
    include 'modalFacturar.php';
?>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#ppusuarioPfValue").val("-2");
        autoCompleteUsuarios($('.ppusuarioPf'), 'IN');

        <?php if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            $("#ppusuarioPfValue").val( <?php echo $usuario->getIdFromUsername($_SESSION['username']) ?> );
            $("#ppusuarioPf").prop("disabled", true);
        <?php endif; ?>
        
        loadDataTablePendientesFacturacion();
        
        $("#ppusuarioPf").change(function() {
            var dataU = $('#ppusuarioPf').select2('data');

            if (dataU.length > 0)
                $("#ppusuarioPfValue").val($("#ppusuarioPf").val());
            else
                $("#ppusuarioPf").val("-1");

            $('#ppusuarioPfCChTable').DataTable().ajax.reload();
	    });
        
        $('#ppusuarioPfCChTable').on('click', 'button', function () {
            var data = $("#ppusuarioPfCChTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "ch_facturar":
                    var date = new Date(data.FechaFactura);
                    $('#idRegistroEsperaFacturacion').val(data.IdCajaChicaDetalle);
                    $("#factura").val("");
                    $("#valor_factura").val(data.Total);
                    $("#valor_factura").prop("disabled", true);
                    $("#fecha_factura").datepicker({
                        dateFormat: 'mm-dd-yy'
                    }).datepicker('setDate', date)
                    $('#facturarModal').modal('show');
                break;
            }
        });
    });
</script>