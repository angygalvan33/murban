<script src="pages/compras/requisproy/detalleProveedores/listadoProveedoresScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <div class="table table-responsive">
        <table id="proveedoresRequisicionesTable" class="table table-hover">
            <thead class="encabezadoTabla">
                <tr>
                    <th>Cantidad PreOC</th>
					<th>Proveedor</th>
                    <th>Precio</th>
                    <th>Fecha Precio</th>
                    <th>Unidad Material</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var habilitaCheckbox = $('#mostrarTodoProy').prop('checked') === true ? 0 : 1;
        loadDataTableProveedoresRequisiciones(habilitaCheckbox, tipoQuery, idProyecto, cuentaCantidad);
        
        $('#proveedoresRequisicionesTable').on('click', 'button', function () {
            var data = $("#proveedoresRequisicionesTable").DataTable().row($(this).parents('tr')).data();
            switch ($(this).attr("id")) {
                case "req_comprar":
                    $('#tipoReqAsignar').val(0); //0 Manual, 1 especial
                    $("#tipoAsignar").val(1); //0 asignar a stock, 1 asignar a OC
                    $("#errorcantidadAsignar").css("display", "none");
                    $("#disponibleAsignar").text("Cantidad requisitada:");
                    $("#cantidadDisponible").text(data.CantidadSolicitada);
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