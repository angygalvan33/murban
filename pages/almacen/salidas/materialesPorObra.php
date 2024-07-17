<style type="text/css">
    .select2 {
        width: 100% !important;
    }
</style>

<table id="salidaMatxObraTabla" class="table table-hover">
    <thead class="encabezadoTabla">
        <tr>
            <th>Proyecto</th>
            <th>Cantidad</th>
            <th></th>
        </tr>
    </thead>
</table>

<div id="salidaMaterialModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Salida</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="salidaMatxObra_idObra">
                <input type="hidden" id="salidaMatxObra_cantidad">
                <input type="hidden" id="salidaMatxObra_nombreMaterial">
                <input type="hidden" id="salidaMatxObra_precioUnitario">
                <form id="formSalidaMatxObra" role="form">
                    <label>Material:</label>&nbsp;<span id="nombreMaterialSalida"></span>
                    <br>
                    <label>Cantidad disponible:</label>&nbsp;<span id="cantidadMaterialSalida"></span>
                    <hr style="margin:0px 0px 10px 0px">
                    <div class="input-group" style="width: 100%">
                        <label>Cantidad</label>
                        <input type="text" id="cantidadDeSalida" name="cantidadDeSalida" class="form-control" required>
                        <label id="errorCantidadDeSalida" style="display:none; color:red">La cantidad sobrepasa la existente.</label>
                    </div>
                    <div class="input-group" style="width: 100%">
                        <label>Personal</label>
                        <br>
                        <select name="personalSalida" id="personalSalida" class="form-control personalSalida" required></select>
                    </div>
                    <div class="input-group" style="width: 100%">
                        <label>Proyecto</label>
                        <br>
                        <select name="obraSalida" id="obraSalida" class="form-control obraSalida" required></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="salidaMatxObra($('#salidaMatxObra_idObra').val(), $('#salidaMatxObra_cantidad').val(), $('#salidaMatxObra_nombreMaterial').val(), $('#salidaMatxObra_precioUnitario').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaSalidaMaterialesPorObraTabla();
        autoCompletePersonal();
        autoCompleteObras();
        
        $('#salidaMatxObraTabla').on('click', 'button', function () {
            var data = $("#salidaMatxObraTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "salidaMatxObra":
                    $("#nombreMaterialSalida").text(data.Nombre);
                    $("#cantidadMaterialSalida").text(data.Cantidad);
                    $("#errorCantidadDeSalida").css("display","none");
                    $("#salidaMatxObra_idObra").val(data.IdObra);
                    $("#salidaMatxObra_cantidad").val(data.Cantidad);
                    $("#salidaMatxObra_nombreMaterial").val(data.Nombre);
                    $("#salidaMatxObra_precioUnitario").val(data.PrecioUnitario);
                    $("#cantidadDeSalida").val("");
                    $("#personalSalida").empty();
                    $("#descripcionSalida").val("");
                    //cargar por default la obra
                    if (parseInt(data.IdObra) != -1) {
                        var option = new Option(data.NombreObra, data.IdObra, true, true);
                        $(".obraSalida").append(option).trigger('change');
                        $(".obraSalida").prop("disabled", true);
                    }
                    $("#salidaMaterialModal").modal("show");
                break;
            }
        });
        
        $("#cantidadDeSalida").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );
    });
    
    function salidaMatxObra(idObra, cantidadExistente, nombreMaterial, precioUnitario) {
        if ($("#formSalidaMatxObra").valid()) {
            //la cantidad desalida no debe ser mayor a la existente
            if (parseFloat($("#cantidadDeSalida").val()) > parseFloat(cantidadExistente))
                $("#errorCantidadDeSalida").css("display","block");
            else {
                var dataPersonal = $('#personalSalida').select2('data');
                var idPersonal = dataPersonal[0].id;
                var dataObra = $('#obraSalida').select2('data');
                var idObraSalida = dataObra[0].id;
                salidaDeMaterial(idObra, $(".detalles").attr("id"), $("#cantidadDeSalida").val(), idPersonal, nombreMaterial, precioUnitario, idObraSalida);
                $("#salidaMaterialModal").modal("hide");
            }
        }
    }
</script>