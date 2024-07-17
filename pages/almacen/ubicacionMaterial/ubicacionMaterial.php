<script src="pages/almacen/ubicacionMaterial/ubicacionMaterialScript.js" type="text/javascript"></script>

<div class="col-md-10"></div>
<div class="col-md-2" style="margin-bottom: 10px !important">
    <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalUbicacionMaterial()"><i class="fa fa-plus"></i>&nbsp;Nueva</button>
</div>

<div class="col-md-12 table-responsive">
    <table id="ubicacionMaterialTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Ubicación</th>
                <th>Cantidad</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<?php
    include 'nuevaUbicacionMaterial.php';
    include 'editaUbicacionMaterial.php';
?>
<script type="text/javascript">
    $( document ).ready(function() {
        inicializaTablaUbicacionMaterial();

        $('#ubicacionMaterialTabla tbody').on('click', 'button', function () {
            var data = $("#ubicacionMaterialTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    resetValuesNuevaUbicacionMaterial();
                    $("#ubMat_cantidadact_edit").prop('disabled', true);
                    //$("#ubMat_nvo").css('visibility', 'visible');
                    //$("#cantidadnvabox").css('visibility', 'visible');
                    loadEditarUbicacionMaterial(data);
                break;
                case "eliminar":
                    $("#tipo").val(1);
                    $("#idRegistro").val(data.IdInventario);
                    $("#warningModal").modal("show");
                break;
            }
        });
    });

    function openModalUbicacionMaterial() {
        resetValuesUbicacionMaterial();
        $("#ubMat_material").prop('disabled', false);
        $("#ubMat_cantidadact").prop('disabled', false);
        $("#ubMat_nvo").css('visibility', 'hidden');
        //$("#cantidadnvabox").css('visibility', 'hidden');
        $("#accion").val(0);
        $("#idRegistro").val(0);
        llenaUbicaciones_ub($('#ubMat_ubicaciona'));
        $("#nuevaUbicacionMaterialModal").modal("show");
    }
</script>