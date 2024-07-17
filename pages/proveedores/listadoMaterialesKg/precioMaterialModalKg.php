<div id="precioMaterialModalKg" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Precio de material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idMaterialKg">
                <input type="hidden" id="idProveedorKg">
                <form id="formPrecioKg" role="form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" style="width:100% !important">
                                <label>¿Quién cotiza?</label>
                                <br/>
                                <select class="form-control ancho cotizadorKg" id="cotizadorMKg" name="cotizadorMKg" required style="width:100% !important"></select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Precio</legend>
                                    <input type="hidden" id="dolarActualMKg" class="dolarActualKg">
                                    <div class="form-group">
                                        <div class="col-md-10 monedasMKg">
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="radio" name="monedaMKg" value="P" required class="minimal">
                                                    Pesos
                                                </label>
                                                <div style="width:100%">
                                                    <input type="text" id="precioMKg" name="precioMKg" class="form-control" style="width:100%">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>
												    <input type="radio" name="monedaMKg" value="D" required class="minimal">
                                                    Dolares
													<span id="labelDolaresMKg" class="labelDolares"></span>
                                                </label>
                                                <div style="width:100%">
                                                    <input type="text" id="dolaresMKg" name="dolaresMKg" class="form-control" style="width:100%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group ancho">
                                               <label>¿Incluye IVA?</label>
                                               <br>
                                               <input type="checkbox" class='icheckbox_flat-green' id="ivaMKg" name="ivaMKg">
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
                <button type="button" class="btn btn-primary" onclick="validarFormularioKg($('#idMaterialKg').val(), $('#idProveedorKg').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#formPrecioKg").validate( {
            rules: {
                precioMKg: { number: true },
                monedaMKg: {
                    required: function() {
                        var c = $('input[type=radio][name=monedaMKg]').is(":checked");
                        
                        if (!c)
                            $(".monedasMKg").css("border", "1px solid red");
                        else
                            $(".monedasMKg").css("border", "1px solid white");
                        return c;
                    }
                },
                dolaresM: {
                    number: true
                }
            }
	    });
        
        $("#precioMKg").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#dolaresMKg").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $('.cotizadorKg').select2( {
            tags: true,
            ajax: {
                url: './pages/proveedores/listadoMaterialesKg/autocompleteCotizadoresKg.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, //search term
                        IdProveedor: $("#idProveedorKg").val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });
        
        $("#dolaresMKg").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActualKg").val() != "" && $(".dolarActualKg").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#precioMKg").val((parseFloat(p) * parseFloat($(".dolarActualKg").val().replace(/\,/g, ''))).toFixed(4));
                else
                    $("#precioMKg").val("");
            }
        });
        
        $("#precioMKg").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActualKg").val() != "" && $(".dolarActualKg").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#dolaresMKg").val((parseFloat(p) / parseFloat($(".dolarActualKg").val())).toFixed(4));
                else
                    $("#dolaresMKg").val("");
            }
        });
        
        $('input[type=radio][name=monedaMKg]').on('ifChecked', function() {
            monedaRequerida($(this).val(), 1);
            $('input[type=radio][name=monedaMKg]').valid();
        });
        
        $("#precioMKg").focus( function() {
            $('input[type=radio][name=monedaMKg][value=P]').iCheck('check');
            monedaRequerida('P', 1);
        });
        
        $("#dolaresMKg").focus( function() {
            $('input[type=radio][name=monedaMKg][value=D]').iCheck('check');
            monedaRequerida('D', 1);
        });
    });
    
    function validarFormularioKg(idMaterial, idProveedor) {
        if ($("#formPrecioKg").valid()) {
            nuevoPrecioxKilo();
			$('#precioMaterialModalKg').modal('hide');
        }
    }
</script>