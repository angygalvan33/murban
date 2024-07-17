<?php
    include_once 'pagosPendientes/nuevaCompra.php';
?>
<script src="pages/cajaChica/pagosPendientes/pagosPendientes.js" type="text/javascript"></script>
<link href="pages/cajaChica/cajaChicaStyles.css" rel="stylesheet" type="text/css"/>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Usuario</label>
            <br>
            <select id="ppusuarioCU" name="ppusuarioCU" class="form-control ppusuarioCU" required="">
                <?php if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                    <option value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" selected="selected"><?php echo $usuario->getNameFromUsername($_SESSION['username']) ?></option>
                <?php endif; ?>
            </select>
            <input type="hidden" name="ppusuarioCUValue" id="ppusuarioCUValue">
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
    <div class="col-md-10"></div>
    <?php if ($permisos->acceso("137438953472", $usuario->obtenerPermisos($_SESSION['username']))): ?>
    <div class="col-md-2" style="margin-bottom: 10px !important; margin-top: 10px !important;">
        <button id="nuevaComprausuario" type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalNuevaCompra()"><i class="fa fa-plus"></i>&nbsp;Nueva compra</button>
    </div>
    <?php endif; ?>
    <table id="ppusuarioCChTable" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Proyecto</th>
                <th>Material</th>
                <th>Folio de factura</th>
                <th>Cantidad</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $("#ppusuarioCUValue").val("-2");
        $("#nuevaComprausuario").prop("disabled", true);
        autoCompleteUsuarios($('.ppusuarioCU'), 'IN');
        
        <?php if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            $("#ppusuarioCUValue").val( <?php echo $usuario->getIdFromUsername($_SESSION['username']) ?> );
            $("#ppusuarioCU").prop("disabled", true);
            $("#nuevaComprausuario").prop("disabled", false);
        <?php endif; ?>
        
        getPresupuestoDeUsuario($("#ppusuarioCUValue").val());
        loadDataTablePagosPendientes();
        
        $("#ppusuarioCU").change(function() {
            var dataU = $('#ppusuarioCU').select2('data');
            
            if (dataU.length > 0) {
                $("#ppusuarioCUValue").val($("#ppusuarioCU").val());
                getPresupuestoDeUsuario($("#ppusuarioCUValue").val());
                $("#nuevaComprausuario").prop("disabled", false);
            }
            else
                $("#ppusuarioCUValue").val("-1");
            
            $('#ppusuarioCChTable').DataTable().ajax.reload();
	    });
    });
</script>