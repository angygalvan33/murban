<div id="editprecioMaterialModalKg" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar Precio de material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editidProveedorKg">
                <input type="hidden" id="editMoneda">
				<input type="hidden" id="editIva">
				<input type="hidden" id="editidPrecioxkilo">
                <form id="editformPrecioKg" role="form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Precio</legend>
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <div class="col-md-6">
                                                <div style="width:100%">
                                                    <input type="text" id="editprecioMKg" name="editprecioMKg" class="form-control" style="width:100%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editvalidarFormularioKg($('#editidPrecioxkilo').val(), $('#editidProveedorKg').val(), $('#editMoneda').val(), $('#editIva').val(), $('#editprecioMKg').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#editprecioMKg").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );
    });
    
    function editvalidarFormularioKg(idPrecioxkilo, idProveedor, Moneda, Iva, PrecioxKilo) {
        if ($("#editformPrecioKg").valid()) {
            editPrecioxKilo(idPrecioxkilo, idProveedor, Moneda, Iva, PrecioxKilo);
			$('#editprecioMaterialModalKg').modal('hide');
			var ridx = $('#rowidx').val();
			var arow = $('#provTable').find('tr').eq(ridx);
			var abtn = arow.find('#precioMaterialKg');
			if (abtn != null)
				loadEditarPrecioMatupdate(abtn, arow);
        }
    }
</script>