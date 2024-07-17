<div id="nuevaCompraModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Compra</h4>
            </div>
            <div class="modal-body">
                <form id="formCompra" role="form">
                    <div class="form-group">
                        <label>Proyecto</label>
                        <br>
                        <select id="obraAut" name="obraAut" class="form-control obraAut" required></select>
                    </div>
                    <div class="form-group">
                        <label>Proveedor</label>
                        <br>
                        <select id="proveedorAut" name="proveedorAut" class="form-control proveedorAut" required></select>
                    </div>
                    <div class="form-group">
                        <label>Material</label>
                        <br>
                        <select id="materialAut" name="materialAut" class="form-control materialAut" required></select>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class='icheckbox_flat-green' type='checkbox' id="cfacturada" name="cfacturada">
                                <label>¿Es facturable?</label>
                            </div>
                        </div>
                        <div class="col-md-6 cfolio" style="display:none">
                            <div class="form-group">
                                <input class='icheckbox_flat-green' type='checkbox' id="cfolioPendiente" name="cfolioPendiente">
                                <label>Folio pendiente</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group cfolio" style="display:none">
                        <label>Folio</label>
                        <input type="text" id="cfolioFactura" name="cfolioFactura" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Total (MXN)</label>
                        <input type="text" id="ctotalFactura" name="ctotalFactura" class="form-control numeros" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioCompra()">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        autoCompleteObrasCU();
        autoCompleteProveedoresCU();
        autoCompleteMaterialesCU();
        
        $("#cfacturada").change(function() {
            mostrarOcultarFolio();
        });
        
        $("#cfolioPendiente").change(function() {
            if ($(this).prop('checked')) {
                $("#cfolioFactura").val("PENDIENTE");
                $("#cfolioFactura").prop("disabled", true);
            }
            else {
                $("#cfolioFactura").val("");
                $("#cfolioFactura").prop("disabled", false);
            }
        });
        
        $("#formCompra").validate( {
            rules: {
                ctotalFactura: { number: true }
            }
	    });
        
        $(".numeros").inputmask(
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
    
    function validarFormularioCompra() {
        if ($("#formCompra").valid()) {
            guardarCompra();
            $('#nuevaCompraModal').modal('hide');
        }
    }
    
    function mostrarOcultarFolio() {
        if ($('#cfacturada').prop('checked'))
            $(".cfolio").css("display", "block");
        else
            $(".cfolio").css("display", "none");
    }
</script>