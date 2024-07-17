<script src="pages/compras/requisesp/requisespScript.js" type="text/javascript"></script>

<input type="hidden" value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" class="usuario-container" data-nombre="<?php echo $usuario->getNameFromUsername($_SESSION['username']);?>">
<div class="col-md-12">
    <div class="row">
        <div class="col-md-4">
            <label>Proyecto</label>
            <select id='proySeleccionadosEspecial' class='form-control' style="width:100%"></select>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <table id="requisicionesEspecialesTable" class="table table-hover table-responsive">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Cantidad PreOC</th>
                <th>Folio</th>
                <th>Material</th>
                <th>Precio</th>
                <th>Proyecto</th>
                <th>Cantidad Atendida</th>
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
    var idProyEspecial = 0;
    idsDetalleReqEspecial = [];
    var actualRowEspecial = null;

    $( document ).ready( function() {
        var habilitaCheckboxEspecial = $('#mostrarTodoEspecial').prop('checked') === true ? 1 : 0;
        loadDataTableRequisicionesEspecial(habilitaCheckboxEspecial, tipoQueryEspecial, 1);
        autoCompleteProyectosRequisicionesEsp();

        $('#mostrarTodoEspecial').iCheck( {
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_square',
            increaseArea: '20%' //optional
        });
        
        $('#mostrarTodoEspecial').on('ifChecked', function (event) {
            $("#solicitaNuevaOCEspecial").prop("disabled", true);
            tipoQueryEspecial = 0;
            idProyEspecial = 0;
            loadDataTableRequisicionesEspecial(1, tipoQueryEspecial, idProyEspecial);
            //resetRequisiciones(1);
        });

        $('#mostrarTodoEspecial').on('ifUnchecked', function (event) {
            $("#solicitaNuevaOCEspecial").prop("disabled", true);
            tipoQueryEspecial = 1;
            idProyEspecial = 0;
            loadDataTableRequisicionesEspecial(0, tipoQueryEspecial, idProyEspecial);
            //resetRequisiciones(1);
        });
        
        $("#proySeleccionadosEspecial").change(function() {
            idsDetalleReqEspecial = [];
            resetValuesEspecial();
            var dataP = $('#proySeleccionadosEspecial').select2('data');
            tipoQueryEspecial = 1;
            //resetRequisiciones(1);
            
            if (dataP.length > 0) {
                $("#solicitaNuevaOCEspecial").prop("disabled", false);
                idProyEspecial = dataP[0].id;
                $("#proveedorOCReq").append(new Option(dataP[0].text, dataP[0].id));
                $("#valIdProvOCReq").val(idProyEspecial);
                loadDataTableRequisicionesEspecial(0, tipoQueryEspecial, idProyEspecial);
            }
            else {
                $("#proveedorOCReq").empty();
                $("#valIdProvOCReq").val("");
                $("#solicitaNuevaOCEspecial").prop("disabled", true);
                idProyEspecial = 0;
                loadDataTableRequisicionesEspecial(0, tipoQueryEspecial, idProyEspecial);
            }
        });

        $('#requisicionesEspecialesTable tbody').on('click', 'button', function () {
            var data = $("#requisicionesEspecialesTable").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "asignaProveedor":
                    $(".proveedor_AP").empty();
                    $("#formAsignaProveedorUO").validate().resetForm();
                    $("#formAsignaProveedorUO :input").removeClass('error');
                    $("#idReqDetalle_APUO").val(data.IdRequisicionDetalle);
                    $("#material_APUO").val(data.Material);
                    $("#idmaterial_APUO").val(data.IdMaterial);
                    $("#precio_APUO").val("");
                    $("#proveedor_APUO").empty();
                    $("#btnAsignarUO").prop("disabled", true);
                    $('#asignaProveedorModalUO').modal('show');
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