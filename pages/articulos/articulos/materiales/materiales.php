<script src="pages/articulos/articulos/materiales/materialesScript.js" type="text/javascript"></script>

<form id="formNuevoMaterialArt" role="form">
    <div class="row">
        <div class="col-md-3">
            <label>Cantidad</label>
            <br>
            <input id="cantidadMat" name="cantidadMat" class="form-control cantidadMat" required="" type="text">
        </div>
        <div class="col-md-5">
            <label>Material</label>
            <br>
            <select id="materialMat" name="materialMat" class="form-control materialMat" required=""></select>
        </div>
        <div class="col-md-2">
            <br>
            <button type="button" onclick="agregarMaterialArticulo()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</form>
<br>
<div class="row">
    <div class="col-md-12 table-responsive materialesT">
        <table class="table table-hover" id="tablaMaterialesArt">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Material</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="editarMaterial" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">EDITAR MATERIAL</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idMat">
                <input type="text" required="" id="cantMatModal" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                <button id="editarMat" type="button" class="btn btn-primary" data-dismiss="modal" onclick="editarMaterialArt($('#idMat').val(), $('#cantMatModal').val())" disabled="true">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        autoCompleteMaterialesArt();
        inicializaTablaMaterialesArt();
        
        $("#formNuevoMaterialArt").validate( {
            rules: {
                cantidadMat: { number: true }
            }
	    });
        
        $("#cantMatModal").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#cantidadMat").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#cantMatModal").on('keyup click', function (e) {
            if ($("#cantMatModal") == undefined || $("#cantMatModal").val() == "")
                $("#editarMat").prop("disabled", true);
            else
                $("#editarMat").prop("disabled", false);
        });

        $('#tablaMaterialesArt tbody').on('click', 'button', function () {
            switch ($(this).attr("id")) {
                case "eliminarMaterialArt":
                    actualRow = $("#tablaMaterialesArt").DataTable().row($(this).parents('tr'));
                    eliminarMaterialArt(actualRow);
                break;
                case "editarMaterialArt":
                    var datos = $("#tablaMaterialesArt").DataTable().row($(this).parents('tr')).data();
                    $("#idMat").val(datos.IdMaterial);
                    $("#cantMatModal").val("");
                    $("#editarMat").prop("disabled", true);
                    $("#editarMaterial").modal();
                break;
            }
        });
    });
    
    function agregarMaterialArticulo() {
        if ($("#formNuevoMaterialArt").valid()) {
            var dataMateriales = $('.materialMat').select2('data');
            addMaterialToArticulo($(".cantidadMat").val(), dataMateriales[0].id, dataMateriales[0].text);
        }
    }
</script>