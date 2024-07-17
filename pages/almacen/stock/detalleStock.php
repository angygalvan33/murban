<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../../config.php";
    include_once '../../../clases/permisos.php';
    include_once '../../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<style type="text/css">
    .select2 {
        width: 100% !important;
    }
</style>

<table id="MatStockTabla" class="table table-hover">
    <thead class="encabezadoTabla">
        <tr>
            <th>Proyecto</th>
            <th>Cantidad</th>
            <th></th>
        </tr>
    </thead>
</table>

<div id="asignarMaterialModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Asignar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="asignarMaterial_cantidad">
                <input type="hidden" id="asignarMaterial_nombreMaterial">
                <form id="formAsignarMaterial" role="form">
                    <label> Material:</label>
                    <span id="materialAsignarActual"></span>
                    <br>
                    <label> Cantidad actual:</label>
                    <span id="cantidadAsignarActual"></span>
                    <hr style="margin:0px 0px 10px 0px">
                    <div class="input-group" style="width: 100%">
                        <label>Cantidad</label>
                        <input type="text" id="cantidadAsignar" name="cantidadAsignar" class="form-control" required>
                        <label id="errorcantidadAsignar" style="display:none; color:red">La cantidad sobrepasa la existente.</label>
                    </div>
                    <div class="input-group" style="width: 100%">
                        <label>Proyecto</label>
                        <br>
                        <select name="obraAsignar" id="obraAsignar" class="form-control obraAsignar" required></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="asignarMaterial($('#asignarMaterial_cantidad').val(), $('#asignarMaterial_nombreMaterial').val());">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div id="reducirModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reducir</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cantActual">
                <input type="hidden" id="idObraReducir">
                <input type="hidden" id="nombreMaterialReducir">
                <form id="formReducir" role="form">
                    <label> Material:</label>
                    <span id="materialReducirActual"></span>
                    <br>
                    <label> Cantidad actual:</label>
                    <span id="cantidadReducirActual"></span>
                    <hr style="margin:0px 0px 10px 0px">
                    <div class="form-group">
                        <label>Cantidad a reducir</label>
                        <input type="text" id="cantidadReducir" name="cantidadReducir" class="form-control" required="">
                        <label id="errorcantidadReducir" style="display:none; color:red">La cantidad sobrepasa la existente.</label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                        <input class='icheckbox_flat-green' type='checkbox' id="reponerRequisicionReducir" name="reponerRequisicionReducir">
                        <label>Reponer con requisición</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarReducir()">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!--warning modal-->
<div id="warningModal2" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas eliminar el registro?</p>
                <input type="hidden" id="idRegistro">
                <input type="hidden" id="tipo">
                <input type="hidden" id="cantidadEliminar">
                <input type="hidden" id="materialEliminar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                <button id="eliminarRegistro" type="button" class="btn btn-outline" data-dismiss="modal" onclick="eliminarRegistro2($('#idRegistro').val(), $('#tipo').val())">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoReducir = <?php echo json_encode( $permisos->acceso("8192", $usuario->obtenerPermisos( $_SESSION['username'] ) ) ) ?>;
        var permisoAsignar = <?php echo json_encode( $permisos->acceso("8192", $usuario->obtenerPermisos( $_SESSION['username'] ) ) ) ?>;
        inicializaMaterialesStockTabla(permisoReducir, permisoAsignar);
        autoCompleteObrasStock();
        
        $('#MatStockTabla').on('click', 'button', function () {
            var data = $("#MatStockTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "asignarMatxObra":
                    $("#asignarMaterial_cantidad").val(data.Cantidad);
                    $("#asignarMaterial_nombreMaterial").val(data.Nombre);
                    $("#materialAsignarActual").text(data.Nombre);
                    $("#cantidadAsignarActual").text(data.Cantidad);
                    resetAsignarModal();
                    $("#asignarMaterialModal").modal("show");
                break;
                case "reducirMatxObra":
                    $("#cantidadReducir").val("");
                    $("#cantActual").val(data.Cantidad);
                    $("#idObraReducir").val(data.IdObra);
                    $("#nombreMaterialReducir").val(data.Nombre);
                    $("#materialReducirActual").text(data.Nombre);
                    $("#cantidadReducirActual").text(data.Cantidad);
                    $("#errorcantidadReducir").css("display","none");
                    $('#reponerRequisicionReducir').prop( "checked", false);
                    $("#reducirModal").modal("show");
                break;
                case "eliminarMatxObra":
                    $("#idRegistro").val(data.IdObra);
                    $("#tipo").val(1);
                    $("#cantidadEliminar").val(data.Cantidad);
                    $("#materialEliminar").val(data.Nombre);
                    $("#warningModal2").modal("show");
                break;
            }
        });
        
        $("#cantidadAsignar").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $("#cantidadReducir").inputmask(
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
    
    function asignarMaterial(cantidadExistente, nombreMaterial) {
        if ($("#formAsignarMaterial").valid()) {
            //la cantidad desalida no debe ser mayor a la existente
            if (parseFloat($("#cantidadAsignar").val()) > parseFloat(cantidadExistente))
                $("#errorcantidadAsignar").css("display", "block");
            else {
                var dataObra = $('#obraAsignar').select2('data');
                var idObra = dataObra[0].id;
                
                asignacionDeMaterial(idObra, $(".detalles").attr("id"), $("#cantidadAsignar").val(), null, nombreMaterial);
                $("#asignarMaterialModal").modal("hide");
            }
        }
    }
    
    function validarReducir() {
        if ($("#formReducir").valid()) {
            //la cantidad desalida no debe ser mayor a la existente
            if (parseFloat($("#cantidadReducir").val()) > parseFloat($("#cantActual").val()))
                $("#errorcantidadReducir").css("display", "block");
            else {
                $("#errorcantidadReducir").css("display", "none");
                reducirMaterial($(".detalles").attr("id"), $("#nombreMaterialReducir").val(), $("#cantidadReducir").val(), $("#idObraReducir").val(), $('#reponerRequisicionReducir').prop('checked'));
                $("#reducirModal").modal("hide");
            }
        }
    }
</script>