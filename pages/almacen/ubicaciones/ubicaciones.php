<script src="pages/almacen/ubicaciones/ubicacionesScript.js" type="text/javascript"></script>

<div class="col-md-10"></div>
<div class="col-md-2" style="margin-bottom: 10px !important">
    <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalUbicaciones()"><i class="fa fa-plus"></i>&nbsp;Nueva</button>
</div>

<div class="col-md-12 table-responsive">
    <table id="ubicacionesTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<?php 
    include 'nuevaUbicacion.php'; 
?>
<script type="text/javascript">
    $( document ).ready(function() {
        inicializaTablaUbicaciones();
        
        $('#ubicacionesTabla tbody').on('click', 'button', function () {
            var data = $("#ubicacionesTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "ubeditar":
                    loadEditarUbicacion(data);
                break;
                case "ubeliminar":
                    $("#tipo").val(0);
                    $("#idRegistro").val(data.IdUbicacion);
                    $("#warningModal").modal("show");
                break;
            }
        });
    });

    function openModalUbicaciones() {
        resetValuesUbicacion();
        $("#accion").val(0);
        $("#idRegistro").val(0);
        $("#nuevaUbicacionModal").modal("show");
    }
</script>