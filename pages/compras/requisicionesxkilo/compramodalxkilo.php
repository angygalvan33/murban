<div id="asignarComprarModalxkilo" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Material Requisición</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="tipoReqAsignarxkilo">
                <input type="hidden" id="tipoAsignarxkilo">
                <input type="hidden" id="existenciaStockAsignarxkilo">
                <input type="hidden" id="idReqDetalleAsignarxkilo">
                <input type="hidden" id="idMaterialAsignarxkilo">
                <input type="hidden" id="idProyectoAsignarxkilo">
                <input type="hidden" id="idProveedorAsignarxkilo">
                <form id="formAsignarMaterialxkilo" role="form">
                    <label> Material:</label><span id="materialAsignarActualxkilo"></span>
                    <br>
                    <label id="disponibleAsignarxkilo"></label><span id="cantidadDisponiblexkilo"></span>
					<br>
					<label id="unidadAsignarxkilo"> </label> <span id="unidadRequerida"></span>
                    <hr style="margin:0px 0px 10px 0px">
                    <div class="input-group" style="width: 100%">
                        <label>Cantidad</label>
                        <input type="text" id="cantidadAsignarxkilo" name="cantidadAsignarxkilo" class="form-control" required>
                        <label id="errorcantidadAsignarxkilo" style="display:none; color:red">La cantidad sobrepasa la existente.</label>
                    </div>
                    <div class="form-group">
                        <label>Fecha del proveedor</label>
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        <input data-date-format="yyyy-mm-dd" type="text" class="form-control pull-right" id="fechaProvxKilo" required=""/>
                        <input type="hidden" id="fprovxkilo" value="-1">
                    </div>
                    <div class="input-group" style="width: 100%">
                        <label>Proyecto</label>
                        <input type="text" id="obraAsignarxkilo" name="obraAsignarxkilo" class="form-control" disabled="disabled">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="asignarMaterialxkilo($('#tipoReqAsignarxkilo').val(), $('#tipoAsignarxkilo').val(), $('#idReqDetalleAsignarxkilo').val(), $('#idMaterialAsignarxkilo').val(), $('#idProyectoAsignarxkilo').val(), $('#cantidadAsignarxkilo').val(), $('#idProveedorAsignarxkilo').val(), $('#fechaProvxKilo').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaFechas();
    });

    function inicializaFechas() {
        $('#fechaProvxKilo').datepicker( {
            "setDate": new Date(),
            "autoclose": true,
            "inmediateUpdates": true,
            "todayBtn": true,
            "todayHighlight": true,
        }).datepicker("setStartDate", "0");
    }
</script>