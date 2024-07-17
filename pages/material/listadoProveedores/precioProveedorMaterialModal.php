<script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>

<div id="precioProveedorMaterialModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Precio de material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idMaterial">
                <input type="hidden" id="idProveedor">
                <form id="formPrecioMat" role="form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" style="width:100% !important">
                                <label>¿Quién cotiza?</label>
                                <br/>
                                <select class="form-control ancho cotizadorMat" id="cotizadorMat" name="cotizadorMat" required style="width:100% !important"> </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Precio</legend>
                                    <input type="hidden" id="dolarActualMatM" class=".dolarActual">
                                    <div class="form-group">
                                        <div class="col-md-10 monedasMatM">
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="radio" name="monedaMatM" value="P" required class="minimal">
                                                    Pesos
                                                </label>
                                                <div style="width:100%">
                                                    <input type="text" id="precioMatM" name="precioMatM" class="form-control" style="width:100%">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="radio" name="monedaMatM" value="D" required class="minimal">
                                                    <span id="labelDolaresMatM" class="labelDolaresMat"></span>
                                                </label>
                                                <div style="width:100%">
                                                    <input type="text" id="dolaresMatM" name="dolaresMatM" class="form-control" style="width:100%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group ancho">
                                                <label>¿Incluye IVA?</label>
                                                <br>
                                                <input type="checkbox" class='icheckbox_flat-green' id="ivaMatM" name="ivaMatM">
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
                <button type="button" class="btn btn-primary" onclick="validarFormularioMat($('#idMaterial').val(),$('#idProveedor').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $("#formPrecioMat").validate( {
            rules: {
                precioMatM: { number: true },
                monedaMatM: {
                    required: function() {
                        var c = $('input[type=radio][name=monedaMatM]').is(":checked");
                        
                        if (!c)
                            $(".monedasMatM").css("border","1px solid red");
                        else
                            $(".monedasMatM").css("border","1px solid white");
                        return c;
                    }
                },
                dolaresMatM: {
                    number: true
                }
            }
	    });
        
        $("#precioMatM").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#dolaresMatM").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $('.cotizadorMat').select2( {
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
        
        $("#dolaresMatM").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#precioMatM").val((parseFloat(p) * parseFloat($(".dolarActual").val().replace(/\,/g, ''))).toFixed(4));
                else
                    $("#precioMatM").val("");
            }
        });
        
        $("#precioMatM").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#dolaresMatM").val((parseFloat(p) / parseFloat($(".dolarActual").val())).toFixed(4));
                else
                    $("#dolaresMatM").val("");
            }
        });
        
        $('input[type=radio][name=monedaMatM]').on('ifChecked', function() {
            monedaRequeridaMat($(this).val(), 1);
            $('input[type=radio][name=monedaMatM]').valid();
        });
        
        $("#precioMatM").focus( function() {
            $('input[type=radio][name=monedaMatM][value=P]').iCheck('check');
            monedaRequeridaMat('P', 1);
        });
        
        $("#dolaresMatM").focus( function() {
            $('input[type=radio][name=monedaMatM][value=D]').iCheck('check');
            monedaRequeridaMat('D', 1);
        });
    });
    
    function validarFormularioMat(idMaterial, idProveedor) {
        if ($("#formPrecioMat").valid()) {
            guardarPrecioProveedorMat(idMaterial, idProveedor);
            $('#precioProveedorMaterialModal').modal('hide');
        }
    }
</script>