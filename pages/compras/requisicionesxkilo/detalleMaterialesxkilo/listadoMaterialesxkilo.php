<script src="pages/compras/requisicionesxkilo/detalleMaterialesxkilo/listadoMaterialesScriptxkilo.js" type="text/javascript"></script>

<div class="col-md-12">
    <div class="table table-responsive">
        <table id="materialesRequisicionesTablexkilo" class="table table-hover">
            <thead class="encabezadoTabla">
                <tr>
                    <th>Cantidad</th>
					<th>Unidad</th>
                    <th>Cantidad PreOC</th>
                    <th>Proyecto</th>
                    <th>Cantidad Atendida</th>
                    <th>Existencia en Stock</th>
                    <th>Solicita</th>
                    <th>Se requiere para:</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        var habilitaCheckbox = $('#mostrarTodoxkilo').prop('checked') === true ? 0 : 1;
        loadDataTableMaterialesRequisicionesxkilo(habilitaCheckbox, tipoQuery, idProveedor, cuentaCantidad);

        $('#materialesRequisicionesTablexkilo').on('click', 'button', function () {
            var data = $("#materialesRequisicionesTablexkilo").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "req_cancelarxkilo":
                    $("#idDetalleReq").val(data.IdRequisicionDetalle);
                    $("#motivoCancelacionReq").val("");
                    $("#cancelarReqModal").modal("show");
                break;
                case "req_asignarxkilo":
                    $("#tipoAsignarxkilo").val(0); //0 asignar a stock, 1 asignar a OC
                    $("#errorcantidadAsignarxkilo").css("display", "none");
                    $("#disponibleAsignarxkilo").text("Cantidad en Stock:");
                    $("#cantidadDisponiblexkilo").text(data.ExistenciaStock);
                    $("#materialAsignarActualxkilo").text(data.Material);
                    $("#existenciaStockAsignarxkilo").val(data.ExistenciaStock);
                    $("#idReqDetalleAsignarxkilo").val(data.IdRequisicionDetalle);
                    $("#idMaterialAsignarxkilo").val(data.IdMaterial);
                    $("#idProyectoAsignarxkilo").val(data.IdProyecto);
                    $("#idProveedorAsignarxkilo").val(data.IdProveedor);
                    $("#cantidadAsignarxkilo").val("");
                    $("#obraAsignarxkilo").val(data.Proyecto);
                    $("#asignarComprarModalxkilo").modal();
                break;
                case "req_comprarxkilo":
                    $('#tipoReqAsignarxkilo').val(0); //0 Manual, 1 especial
                    $("#tipoAsignarxkilo").val(1); //0 asignar a stock, 1 asignar a OC
                    $("#errorcantidadAsignarxkilo").css("display", "none");
                    $("#disponibleAsignarxkilo").text("Cantidad requisitada:");
                    $("#cantidadDisponiblexkilo").text(data.Cantidad);
                    $("#materialAsignarActualxkilo").text(data.Material);
                    $("#existenciaStockAsignarxkilo").val("");
                    $("#idReqDetalleAsignarxkilo").val(data.IdRequisicionDetalle);
                    $("#idMaterialAsignarxkilo").val(data.IdMaterial);
                    $("#idProyectoAsignarxkilo").val(data.IdProyecto);
                    $("#idProveedorAsignarxkilo").val(data.IdProveedor);
                    $("#cantidadAsignarxkilo").val("");
                    $("#obraAsignarxkilo").val(data.Proyecto);
					$("#unidadAsignarxkilo").text("Unidad:");
                    $("#unidadRequeridaxkilo").text(data.UnidadReq);
					$("#asignarComprarModalxkilo").modal();
                break;
            }
        });
    });
</script>