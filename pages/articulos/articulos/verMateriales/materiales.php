<script src="pages/articulos/articulos/verMateriales/materialesScript.js" type="text/javascript"></script>

<form id="verformNuevoMaterialArt" role="form">
    <div class="row">
        <div class="col-md-3">
            <label>Cantidad</label>
            <br>
            <input id="vercantidadMat" name="vercantidadMat" class="form-control" required="" type="text">
        </div>
        <div class="col-md-5">
            <label>Material</label>
            <br>
            <select id="vermaterialMat" name="vermaterialMat" class="form-control" required=""></select>
        </div>
        <div class="col-md-2">
            <br>
            <button type="button" onclick="veragregarMaterialArticulo()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</form>
<br>
<div class="row">
    <div class="col-md-12 table-responsive">
        <table class="table table-hover" id="vertablaMaterialesArt">
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

<div id="vereditarMaterial" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">EDITAR MATERIAL</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="veridMat">
                <label>Cantidad</label>
                <br>
                <input type="text" required="" id="vercantMatModal" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                <button id="vereditarMat" type="button" class="btn btn-primary" data-dismiss="modal" onclick="vereditarMaterialArt($('#veridMat').val(), $('#vercantMatModal').val())" disabled="true">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        verautoCompleteMaterialesArt();
        verinicializaTablaMaterialesArt();
        
        $("#verformNuevoMaterialArt").validate( {
            rules: {
                vercantidadMat: { number: true }
            }
	    });
        
        $("#vercantMatModal").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#vercantidadMat").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#vercantMatModal").on('keyup click', function (e) {
            if ($("#vercantMatModal") == undefined || $("#vercantMatModal").val() == "")
                $("#vereditarMat").prop("disabled", true);
            else
                $("#vereditarMat").prop("disabled", false);
        });

        $('#vertablaMaterialesArt tbody').on('click', 'button', function () {
            var datos = $("#vertablaMaterialesArt").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "eliminarMaterialArt":
                    vereliminarMaterialArt(datos.IdArticuloDetalle);
                break;
                case "editarMaterialArt":
                    $("#veridMat").val(datos.IdArticuloDetalle);
                    $("#vercantMatModal").val("");
                    $("#vereditarMat").prop("disabled", true);
                    $("#vereditarMaterial").modal();
                break;
            }
        });
    });
    
    function veragregarMaterialArticulo() {
        if ($("#verformNuevoMaterialArt").valid()) {
            var dataMateriales = $('#vermaterialMat').select2('data');
            veraddMaterialToArticulo($("#vercantidadMat").val(), dataMateriales[0].id);
        }
    }
</script>