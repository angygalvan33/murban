<script src="pages/compras/requisiciones/detalleMateriales/listadoMaterialesScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <div class="table table-responsive">
        <table id="materialesRequisicionesTable" class="table table-hover">
            <thead class="encabezadoTabla">
                <tr>
                    <th></th>
                    <th>Cantidad</th>
                    <th>Proyecto</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var nombreUsuario = "";
        var idUsuario = $('.usuario-container').val();
        $('.usuario-container').each( function() {
            var container = $(this);
            nombreUsuario = container.data('nombre');
        });
        
        var habilitaCheckbox = $('#mostrarTodo').prop('checked') === true ? 0 : 1;
        loadDataTableMaterialesRequisiciones(habilitaCheckbox, tipoQuery, idProveedor);
        
        $('#materialesRequisicionesTable').on('click', 'button', function () {
            var data = $("#materialesRequisicionesTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "req_cancelar":
                    $("#idDetalleReq").val(data.IdRequisicionDetalle); //cambiar por IdDetalleReq
                    $("#motivoCancelacionReq").val("");
                    $("#cancelarReqModal").modal("show");
                break;
            }
        });
        
        $('#materialesRequisicionesTable').on('click', 'input.materialSeleccionado', function () {
            var data = $("#materialesRequisicionesTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "materialSeleccionado":
                    if ($(this).prop('checked')) {
                        agregaDetalleOC_Req(data.IdMaterial, data.Nombre, data.IdProyecto, data.Proyecto, data.CantidadParaSolicitar, data.Precio, idUsuario, nombreUsuario, data.IdRequisicionDetalle);
                        idsDetalleReq.push(data.IdRequisicionDetalle);
                        $(".reqCancelar"+ data.IdRequisicionDetalle).prop("disabled", true);
                    }
                    else {
                        actualRow = $("#matsEspecial").DataTable().row($(this).parents('tr'));
                        var removeItem = data.IdRequisicionDetalle;
                        idsDetalleReq.splice($.inArray(removeItem, idsDetalleReq), 1);
                        $(".reqCancelar"+ data.IdRequisicionDetalle).prop("disabled", false);
                        setTimeout( function() { eliminarOCEspecial_Req(actualRow); }, 3000);
                    }
                break;
                case "req_cancelar":
                    $("#idDetalleReq").val(data.IdRequisicionDetalle);
                    $("#motivoCancelacionReq").val("");
                    $("#cancelarReqModal").modal("show");
                break;
            }
        });
    });
</script>