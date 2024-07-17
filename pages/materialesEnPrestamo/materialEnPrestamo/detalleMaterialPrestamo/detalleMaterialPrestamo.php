<style type="text/css">
    .alertaPrestamo {
        background-color: #ffb9b9;
    }
    
    .disponiblePrestamo {
        background-color: #c5f5c5;
    }
</style>

<script src="pages/materialesEnPrestamo/materialEnPrestamo/detalleMaterialPrestamo/detalleMaterialPrestamoScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="DetalleMaterialPrestamoPTabla" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad</th>
                <th>Personal</th>
                <th>Descripción</th>
                <th>Fecha de préstamo</th>
                <th>Días de préstamo</th>
                <th>Días restantes</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<div id="refrendarModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Refrendar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="refrendar_idPrestamoResguardo">
                <form id="formRefrendar" role="form">
                    <div class="form-group">
                        <label>¿Por cuántos días mas?</label>
                        <input type="text" id="diasExtraPrestamo" name="diasExtraPrestamo" class="form-control" required="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="refrendarModal($('#refrendar_idPrestamoResguardo').val(), $('#diasExtraPrestamo').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!--warning modal-->
<div id="cobrarModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cobrar</h4>
            </div>
            <div class="modal-body">
                <p>Se cobrará la cantidad de material seleccionada.</p>
                <input type="hidden" id="idRegistro">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                <button id="cobrarMaterial" type="button" class="btn btn-outline" data-dismiss="modal" onclick="cobrarMaterial($('#idRegistro').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaDetalleMaterialPrestamoTable();

        $("#diasExtraPrestamo").inputmask(
            "integer", {
                allowMinus: false,
                allowPlus: false,
                groupSeparator: ",",
                digits: 0,
                autoGroup: true
            }
        );
        
        $('#DetalleMaterialPrestamoPTabla tbody').on('click', 'button', function () {
            var data = $("#DetalleMaterialPrestamoPTabla").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarMaterialPrestamo(data);
                break;
                case "recibir":
                    $("#idDetalleR").val(data.IdDetalle);
                    $("#tipoR").val(1); //1 => prestamo
                    $("#idPersonalR").val(data.IdPersonal);
                    $("#idMaterialR").val(data.IdMaterial);
                    $("#cantidadR").val(data.Cantidad);
                    $("#cantidadPR").val(data.Cantidad);
                    var hoy = moment().format("DD/MM/YYYY");
                    $('#fechaR').val(hoy);
                    $('#fechaHR').val(hoy);
                    $("#errorCantidadPR").css('display','none');
                    $("#recibirModal").modal("show");
                break;
                case "refrendar":
                    $('#refrendar_idPrestamoResguardo').val(data.IdDetalle);
                    $("#diasExtraPrestamo").val("");
                    $("#refrendarModal").modal("show");
                break;
                case "cobrar":
                    $("#idRegistro").val(data.IdDetalle);
                    $("#cobrarModal").modal("show");
                break;
            }
        });
    });

    function refrendarModal(idPrestamoResguardo, diasExtraPrestamo) {
        if ($("#formRefrendar").valid()) {
            refrendarPrestamo(idPrestamoResguardo, diasExtraPrestamo);
            $("#refrendarModal").modal("hide");
        }
    }
</script>