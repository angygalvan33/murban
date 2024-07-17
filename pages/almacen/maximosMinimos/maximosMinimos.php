<script src="pages/almacen/maximosMinimos/maximosMinimosScript.js" type="text/javascript"></script>

<div class="col-md-10"></div>
<div class="col-md-2" style="margin-bottom: 10px !important">
    <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalMaxMin()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
</div>

<div class="col-md-12 table-responsive">
    <table id="maximosMinimosTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Cantidad mínima</th>
                <th>Cantidad máxima</th>
                <th>Alerta</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<?php
    include 'nuevoMaxMin.php';
?>
<script type="text/javascript">
    $( document ).ready(function() {
        inicializaTablaMaximosMinimos();
        
        $('#maximosMinimosTabla tbody').on('click', 'button', function () {
            var data = $("#maximosMinimosTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "mmeditar":
                    $("#accionMaxMin").val(1);
                    loadEditarMinMax(data);
                break;
                case "mmeliminar":
                    $("#tipo").val(2);
                    $("#idRegistro").val(data.IdMaterial);
                    $("#warningModal").modal("show");
                break;
            }
        });
    });

    function openModalMaxMin() {
        resetValuesMaxMin();
        $("#idMaxMin").val(-1);
        $("#accionMaxMin").val(0);
        $("#nuevaMaxMinModal").modal("show");
    }
</script>