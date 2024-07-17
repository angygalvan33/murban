<script src="pages/compras/requisicionesEspeciales/requisicionesScript.js" type="text/javascript"></script>

<input type="hidden" value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" class="usuario-container" data-nombre="<?php echo $usuario->getNameFromUsername($_SESSION['username']);?>">
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">
            <input class='icheckbox_flat-green' type='checkbox' id="mostrarTodoEspecial" name="mostrarTodoEspecial" checked>
            <label>Mostrar todo</label>
        </div>
        <div class="col-md-4">
            <label>Proveedor</label>
            <select id='provSeleccionadosEspecial' class='form-control' disabled="true" style="width:100%"></select>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-primary btn-sm btn-block" id="solicitaNuevaOCEspecial" onclick="solicitarNuevaOC_Req(2)" disabled="true">Solicita OC</button>
        </div>
    </div>
    <br>
    <table id="requisicionesEspecialesTable" class="table table-hover table-responsive">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Cantidad PreOC</th>
                <th>Material</th>
                <th>Precio</th>
                <th>Proyecto</th>
                <th>Cantidad Atendida</th>
                <th>Única ocasión</th>
                <th>Proveedor</th>
                <th>Fecha en que se requiere</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<?php
    include 'AsignarProveedor/asignarProveedor.php';
    include 'AsignarProveedor/asignarProveedorUO.php';
    include 'NuevoProveedor/nuevoProveedor.php';
    include 'NuevoMaterial/nuevoMaterial.php';
?>
<script type="text/javascript">
    var tablaRequisicionesEspecial = null;
    var tablaDetalleRequisiciones = null;
    var tipoQueryEspecial = 0;
    var idProveedorEspecial = 0;
    idsDetalleReqEspecial = [];
    var actualRowEspecial = null;
    
    $( document ).ready( function() {
        var habilitaCheckboxEspecial = $('#mostrarTodoEspecial').prop('checked') === true ? 1 : 0;
        loadDataTableRequisicionesEspecial(habilitaCheckboxEspecial, tipoQueryEspecial, 1);
        autoCompleteProveedoresRequisicionesEspecial();
        
        $('#mostrarTodoEspecial').iCheck( {
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_square',
            increaseArea: '20%' //optional
        });
        
        $('#mostrarTodoEspecial').on('ifChecked', function (event) {
            $("#provSeleccionadosEspecial").prop("disabled", true);
            $("#provSeleccionadosEspecial").empty();
            $("#solicitaNuevaOCEspecial").prop("disabled", true);
            tipoQueryEspecial = 0;
            idProveedorEspecial = 0;
            
            loadDataTableRequisicionesEspecial(1, tipoQueryEspecial, idProveedorEspecial);
            resetRequisiciones(1);
        });

        $('#mostrarTodoEspecial').on('ifUnchecked', function (event) {
           $("#provSeleccionadosEspecial").prop("disabled", false);
           $("#solicitaNuevaOCEspecial").prop("disabled", true);
           tipoQueryEspecial = 1;
           idProveedorEspecial = 0;
           loadDataTableRequisicionesEspecial(0, tipoQueryEspecial, idProveedorEspecial);
           resetRequisiciones(1);
        });
        
        $("#provSeleccionadosEspecial").change( function() {
            idsDetalleReqEspecial = [];
            resetValuesEspecial();
            var dataP = $('#provSeleccionadosEspecial').select2('data');
            tipoQueryEspecial = 1;
            resetRequisiciones(1);
            
            if (dataP.length > 0) {
                $("#solicitaNuevaOCEspecial").prop("disabled", false);
                idProveedorEspecial = dataP[0].id;
                $("#proveedorOCReq").append(new Option(dataP[0].text, dataP[0].id));
                $("#valIdProvOCReq").val(idProveedorEspecial);
                loadDataTableRequisicionesEspecial(0, tipoQueryEspecial, idProveedorEspecial);
            }
            else {
                $("#proveedorOCReq").empty();
                $("#valIdProvOCReq").val("");
                $("#solicitaNuevaOCEspecial").prop("disabled", true);
                idProveedorEspecial = 0;
                loadDataTableRequisicionesEspecial(0, tipoQueryEspecial, idProveedorEspecial);
            }
	    });
        
        $('#requisicionesEspecialesTable tbody').on('click', 'button', function () {
            var data = $("#requisicionesEspecialesTable").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "asignaProveedor":
                    $(".proveedor_AP").empty();

                    if (data.UnicaOcasion === "1") {
                        $("#formAsignaProveedorUO").validate().resetForm();
                        $("#formAsignaProveedorUO :input").removeClass('error');
                        
                        $("#idReqDetalle_APUO").val(data.IdRequisicionDetalle);
                        $("#material_APUO").val(data.Material);
                        $("#idmaterial_APUO").val(data.IdMaterial);
                        $("#precio_APUO").val("");
                        $("#proveedor_APUO").empty();
                        
                        $("#btnAsignarUO").prop("disabled",true);
                        $('#asignaProveedorModalUO').modal('show');
                    }
                    else {
                        $("#formAsignaProveedor").validate().resetForm();
                        $("#formAsignaProveedor :input").removeClass('error');
                        $("#idReqDetalle_AP").val(data.IdRequisicionDetalle);
                        $("#material_AP").val(data.Material);
                        $("#idmaterial_AP").val(data.IdMaterial);
                        $("#nproveedor").empty();
                        $("#ncotizador").empty();
                        $("#nprecioMat").val("");
                        $("#dolaresMat").val("");
                        $('#nivaMat').prop('checked', false);
                        $('input[type=radio][name=monedaMat][value=P]').iCheck('uncheck');
                        $('input[type=radio][name=monedaMat][value=D]').iCheck('uncheck');
                        $("input[name$='nprecioMat']").rules('remove', 'required');
                        $("input[name$='dolaresMat']").rules('remove', 'required');
                        $("#nprecioMat").removeAttr('disabled', 'disabled');
                        $("#dolaresMat").removeAttr('disabled', 'disabled');
                        $(".radio").css("border","1px solid white");
                        $("#msjAltaMat").text("");
                        $("#btnAsignar").prop("disabled",true);
                        $('#asignaProveedorModal').modal('show');
                    }
                break;
                case "req_cancelar":
                    $("#idDetalleReq").val(data.IdRequisicionDetalle);
                    $("#motivoCancelacionReq").val("");
                    $("#cancelarReqModal").modal("show");
                break;
                case "req_comprar":
                    $('#tipoReqAsignar').val(1); //0 Manual, 1 especial
                    $("#tipoAsignar").val(3); //3 asignar a OCEspecial
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
                    $("#asignarComprarModal").modal();
                break;
            }
        });
    });
</script>