<script src="pages/compras/requisiciones/detalleMateriales/listadoMaterialesScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <div class="table table-responsive">
        <table id="materialesRequisicionesTable" class="table table-hover">
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
    $( document ).ready( function() {
        var habilitaCheckbox = $('#mostrarTodo').prop('checked') === true ? 0 : 1;
        loadDataTableMaterialesRequisiciones(habilitaCheckbox, tipoQuery, idProveedor, cuentaCantidad);
        
        $('#materialesRequisicionesTable').on('click', 'button', function () {
            var data = $("#materialesRequisicionesTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "req_cancelar":
                    $("#idDetalleReq").val(data.IdRequisicionDetalle);
                    $("#motivoCancelacionReq").val("");
                    $("#cancelarReqModal").modal("show");
                break;
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
                    $("#asignarComprarModal").modal();
                break;
                case "req_comprar":
                    $('#tipoReqAsignar').val(0); //0 Manual, 1 especial
                    $("#tipoAsignar").val(1); //0 asignar a stock, 1 asignar a OC
                    $("#errorcantidadAsignar").css("display", "none");
                    $("#disponibleAsignar").text("Cantidad requisitada:");
                    $("#cantidadDisponible").text(data.Cantidad);
                    $("#materialAsignarActual").text(data.Material);
                    $("#existenciaStockAsignar").val("");
                    $("#idReqDetalleAsignar").val(data.IdRequisicionDetalle);
                    $("#idMaterialAsignar").val(data.IdMaterial);
                    $("#idProyectoAsignar").val(data.IdProyecto);
                    $("#idProveedorAsignar").val(data.IdProveedor);
                    $("#cantidadAsignar").val("");
                    $("#obraAsignar").val(data.Proyecto);
					$("#unidadAsignar").text("Unidad:");
                    $("#unidadRequerida").text(data.UnidadReq);
					$("#asignarComprarModal").modal();
                break;
            }
        });
    });
</script>