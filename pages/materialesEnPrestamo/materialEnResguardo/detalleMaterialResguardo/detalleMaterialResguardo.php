<script src="pages/materialesEnPrestamo/materialEnResguardo/detalleMaterialResguardo/detalleMaterialResguardoScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="DetalleMaterialResguardoTabla" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad</th>
                <th>Material</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaDetalleMaterialResguardoTable();
        
        $('#DetalleMaterialResguardoTabla tbody').on('click', 'button', function () {
            var data = $("#DetalleMaterialResguardoTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "recibir":
                    $("#idDetalleR").val(data.IdDetalle);
                    $("#tipoR").val(2); //1 => resguardo
                    $("#idPersonalR").val($(".detalles").attr("id"));
                    $("#idMaterialR").val(data.IdMaterial);
                    $("#cantidadR").val(data.Cantidad);
                    $("#cantidadPR").val(data.Cantidad);
                    var hoy = moment().format("DD/MM/YYYY");
                    $('#fechaR').val(hoy);
                    $('#fechaHR').val(hoy);
                    $("#errorCantidadPR").css('display', 'none');
                    $("#recibirModal").modal("show");
                break;
            }
        });
    });
</script>