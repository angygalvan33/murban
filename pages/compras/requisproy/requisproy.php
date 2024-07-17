<script src="pages/compras/requisproy/requisproyScript.js" type="text/javascript"></script>

<input type="hidden" value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" class="usuario-container" data-nombre="<?php echo $usuario->getNameFromUsername($_SESSION['username']);?>">

<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">
            <input class='icheckbox_flat-green' type='checkbox' id="mostrarTodoProy" name="mostrarTodoProy" checked>
            <label>Mostrar en piezas</label>
        </div>
        <div class="col-md-4">
            <label>Proyecto</label>
            <select id="proyectosRequi" class="form-control" style="width:100%"></select>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <table id="requisProyTable" class="table table-hover table-responsive">
        <thead>
            <tr>
                <th>Cant.</th>
                <th>Unidad</th>
                <th>Folio</th>
				<th>Material</th>
                <th>Atendido</th>
                <th>Stock</th>
                <th>Observaciones</th>
                <th>Proyecto</th>
                <th>Requerido</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var tablaRequisiciones = null;
    var tablaDetalleRequisiciones = null;
    var idProyecto = -2;
    var tipoQuery = 0;
    idsDetalleReq = [];
    var habilitaCheckbox = $('#mostrarTodoProy').prop('checked') === true ? 0 : 1;
    var permisosAsignar = <?php echo json_encode($permisos->acceso("8192", $usuario->obtenerPermisos($_SESSION['username']))); ?>;

    $( document ).ready( function() {
        loadDataTableRequisProy(habilitaCheckbox, tipoQuery, idProyecto, permisosAsignar);
        autoCompleteProyectosRequisiciones(habilitaCheckbox);

        $('#mostrarTodoProy').iCheck( {
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_square',
            increaseArea: '20%' //optional
        });

        $('#mostrarTodoProy').on('ifChecked', function (event) {
            $("#proyectosRequi").empty();
            tipoQuery = 0;
            idProyecto = -2;
            habilitaCheckbox = $('#mostrarTodoProy').prop('checked') === true ? 0 : 1;
            loadDataTableRequisProy(habilitaCheckbox, tipoQuery, idProyecto, permisosAsignar);
            autoCompleteProyectosRequisiciones(habilitaCheckbox);
        });

        $('#mostrarTodoProy').on('ifUnchecked', function (event) {
            $("#proyectosRequi").empty();
            tipoQuery = 1;
            idProyecto = -2;
            habilitaCheckbox = $('#mostrarTodoProy').prop('checked') === true ? 0 : 1;
            loadDataTableRequisProy(habilitaCheckbox, tipoQuery, idProyecto, permisosAsignar);
            autoCompleteProyectosRequisiciones(habilitaCheckbox);
        });

        $("#proyectosRequi").change(function() {
            idsDetalleReq = [];
            var dataP = $('#proyectosRequi').select2('data');
            tipoQuery = 1;
            if(dataP.length > 0) {
                idProyecto = dataP[0].id;
                $("#proyectosRequi").append(new Option(dataP[0].text, dataP[0].id));
                habilitaCheckbox = $('#mostrarTodoProy').prop('checked') === true ? 0 : 1;
                loadDataTableRequisProy(habilitaCheckbox, tipoQuery, idProyecto, permisosAsignar);
            }
            else {
                $("#proyectosRequi").empty();
                idProyecto = -2;
                habilitaCheckbox = $('#mostrarTodoProy').prop('checked') === true ? 0 : 1;
                loadDataTableRequisProy(habilitaCheckbox, tipoQuery, idProyecto, permisosAsignar);
            }
	    });

        $('#requisProyTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requisProyTable').DataTable().row(tr);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requisProyTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#requisProyTable').DataTable().row('.shown').node()).click();
                }
                // Open this row
                row.child(formatDetalleProveedores(row.data())).show();
                tr.addClass('shown');
            }
        });

        $('#requisProyTable tbody').on( 'click', 'button', function () {
            var data = $("#requisProyTable").DataTable().row($(this).parents('tr')).data();
            switch ($(this).attr("id")) {
                case "req_asignar":
                    $("#tipoAsignar").val(0); //0 asignar a stock, 1 asignar a OC
                    $("#errorcantidadAsignar").css("display", "none");
                    $("#disponibleAsignar").text("Cantidad en Stock:");
                    $("#cantidadDisponible").text(data.ExistenciaStock);
                    $("#materialAsignarActual").text(data.Material);
                    $("#existenciaStockAsignar").val(data.ExistenciaStock);
                    $("#idReqDetalleAsignar").val(data.IdRequisicionDetalle);
                    $("#idMaterialAsignar").val(data.IdMaterial);
                    $("#idProyectoAsignar").val(data.IdProyecto);
                    $("#idProveedorAsignar").val(data.IdProveedor);
                    $("#cantidadAsignar").val("");
                    $("#obraAsignar").val(data.Proyecto);
                    $('#requispreoc_Table').DataTable().ajax.reload();
                    $("#asignarComprarModal").modal();
                break;
                case "req_cancelar":
                    $("#idDetalleReq").val(data.IdRequisicionDetalle);
                    $("#motivoCancelacionReq").val("");
                    $("#cancelarReqModal").modal("show");
                break;
            }
        });
    });
    
    function formatDetalleProveedores (d) {
        var div = $('<div/>', { class:'row detallesProvRequisiciones', id:d.IdRequisicionDetalle });
        div.load("pages/compras/requisproy/detalleProveedores/listadoProveedores.php");
        return div;
    }
</script>