<div id="precioMaterialModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Precio de material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idMaterial">
                <input type="hidden" id="idProveedor">
                <form id="formPrecio" role="form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" style="width:100% !important">
                                <label>¿Quién cotiza?</label>
                                <br/>
                                <select class="form-control ancho cotizador" id="cotizador" name="cotizador" required style="width:100% !important"></select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Precio</legend>
                                    <input type="hidden" id="dolarActualM" class=".dolarActual">
                                    <div class="form-group">
                                        <div class="col-md-10 monedasM">
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="radio" name="monedaM" value="P" required class="minimal">
                                                    Pesos
                                                </label> 
                                                <div style="width:100%">
                                                    <input type="text" id="precioM" name="precioM" class="form-control" style="width:100%">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="radio" name="monedaM" value="D" required class="minimal">
                                                    <span id="labelDolaresM" class="labelDolares"></span>
                                                </label>
                                                <div style="width:100%">
                                                    <input type="text" id="dolaresM" name="dolaresM" class="form-control" style="width:100%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group ancho">
                                               <label>¿Incluye IVA?</label>
                                               <br>
                                               <input type="checkbox" class='icheckbox_flat-green' id="ivaM" name="ivaM">
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
                <button type="button" class="btn btn-primary" onclick="validarFormulario($('#idMaterial').val(), $('#idProveedor').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#formPrecio").validate( {
           rules: {
                precioM: { number: true },
                monedaM: {
                    required: function() {
                        var c = $('input[type=radio][name=monedaM]').is(":checked");
                        
                        if (!c)
                            $(".monedasM").css("border","1px solid red");
                        else
                            $(".monedasM").css("border","1px solid white");
                        return c;
                    }
                },
                dolaresM:{
                    number: true
                }
            }
	    });
        
        $("#precioM").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#dolaresM").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $('.cotizador').select2( {
            tags: true,
            ajax: {
                url: './pages/proveedores/listadoMateriales/autocompleteCotizadores.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, //search term
                        IdProveedor: $("#idProveedor").val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });
        
        $("#dolaresM").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#precioM").val((parseFloat(p) * parseFloat($(".dolarActual").val().replace(/\,/g, ''))).toFixed(4));
                else
                    $("#precioM").val("");
            }
        });
        
        $("#precioM").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#dolaresM").val((parseFloat(p) / parseFloat($(".dolarActual").val())).toFixed(4));
                else
                    $("#dolaresM").val("");
            }
        });
        
        $('input[type=radio][name=monedaM]').on('ifChecked', function() {
            monedaRequerida($(this).val(), 1);
            $('input[type=radio][name=monedaM]').valid();
        });
        
        $("#precioM").focus( function() {
            $('input[type=radio][name=monedaM][value=P]').iCheck('check');
            monedaRequerida('P', 1);
        });
        
        $("#dolaresM").focus( function() {
            $('input[type=radio][name=monedaM][value=D]').iCheck('check');
            monedaRequerida('D', 1);
        });
    });
    
    function validarFormulario(idMaterial, idProveedor) {
        if ($("#formPrecio").valid()) {
            guardarPrecioMaterial(idMaterial, idProveedor);
            $('#precioMaterialModal').modal('hide');
        }
    }
</script>