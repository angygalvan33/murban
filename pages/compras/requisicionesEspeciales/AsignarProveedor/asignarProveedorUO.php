<script src="pages/compras/requisicionesEspeciales/AsignarProveedor/asignarProveedorScript.js" type="text/javascript"></script>

<div id="asignaProveedorModalUO" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Asignar proveedor</h4>
            </div>
            <div class="modal-body">
                <form id="formAsignaProveedorUO" role="form">
                    <input type="hidden" id='idReqDetalle_APUO' value="0">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Material</label>
                                <input type="text" id="material_APUO" name="material_APUO" class="form-control materialAP" disabled="true">
                                <input type="hidden" id="idmaterial_APUO" name="idmaterial_APUO" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Precio (sin iva)</label>
                                <input type="text" id="precio_APUO" name="precio_AP" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Proveedor</label>
                                <br>
                                <select id="proveedor_APUO" name="proveedor_APUO" class="form-control proveedor_AP" required="" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <br>
                            <button id='altaPUO' type="button" class="btn btn-success" onclick="openModalMP($('#proveedor_APUO'))"><i class="fa fa-plus"></i>&nbsp;Alta</button>
                        </div>
                         <p class="col-md-12" style="color:red" id="msjAltaProvUO"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioAPUO()" id="btnAsignarUO">Asignar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        autoCompleteProveedoresAP();
        $("#formAsignaProveedorUO").validate({ });
        
        $("#precio_APUO").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $("#proveedor_APUO").change(function() {
            $("#msjAltaProvUO").text("");
            if ($("#proveedor_APUO").val() != "-1" && $("#proveedor_APUO").val() != null)
                $("#btnAsignarUO").prop("disabled", false);
            else
                $("#btnAsignarUO").prop("disabled", true);
        });
    });
    
    function validarFormularioAPUO() {
        if ($("#formAsignaProveedorUO").valid()) {
            var dataP = $('#proveedor_APUO').select2('data');
            guardarProveedorPrecioUO($("#idReqDetalle_APUO").val(), $("#idmaterial_APUO").val(), $("#material_APUO").val(), $("#precio_APUO").val(), dataP[0].id, dataP[0].text);
            //guardar en BD
            $('#asignaProveedorModalUO').modal('hide');
        }
    }
</script>