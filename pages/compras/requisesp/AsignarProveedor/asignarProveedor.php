<script src="pages/compras/requisesp/AsignarProveedor/asignarProveedorScript.js" type="text/javascript"></script>
<script src="pages/compras/requisesp/NuevoProveedor/listadoProveedoresScript.js" type="text/javascript"></script>

<div id="asignaProveedorModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Asignar proveedor</h4>
            </div>
            <div class="modal-body">
                <form id="formAsignaProveedor" role="form">
                    <input type="hidden" id='idReqDetalle_AP' value="0">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Material</label>
                                <input type="text" id="material_AP" name="material_AP" class="form-control materialAP" disabled="true">
                                <input type="hidden" id="idmaterial_AP" name="idmaterial_AP" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <br>
                            <button type="button" class="btn btn-success" onclick="openModalMat()"><i class="fa fa-plus"></i>&nbsp;Alta</button>
                        </div>
                        <p class="col-md-12" style="color:red" id="msjAltaMat"></p>
                        <div class="col-md-12">
                            <fieldset  class="scheduler-border" style="margin-bottom: 0px !important;" id="nuevoProvMat">
                                <legend class="scheduler-border">Proveedor de Material</legend>
                                <form id="formPbM">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label>Proveedor:</label>
                                                <select class="form-control ancho proveedor_AP" id="nproveedor" name="nproveedor" required style="width:100%"></select>
                                                <br>
                                                <div style="text-align: right">
                                                    <button type="button" class="btn btn-sm btn-success" onclick="openModalMP($('#nproveedor'))"><i class="fa fa-plus"></i>&nbsp;Alta</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group ancho">
                                                <label>¿Quién atendió?:</label>
                                                <br>
                                                <select class="form-control ancho ncotizador" id="ncotizador" name="ncotizador" required style="width:100%"></select>
                                            </div>
                                        </div>
                                        <p class="col-md-12" style="color:red" id="msjAltaProv"></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border">Precio</legend>
                                                    <input type="hidden" id="dolarActualMat" class="dolarActual">
                                                    <div class="form-group">
                                                        <div class="col-md-10 monedas">
                                                            <div class="col-md-6">
                                                                <label>
                                                                    <input type="radio" name="monedaMat" value="P" required class="minimal"> Pesos
                                                                </label>
                                                                <div style="width:100%">
                                                                    <input type="text" id="nprecioMat" name="nprecioMat" class="form-control" style="width:100%">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>
                                                                    <input type="radio" name="monedaMat" value="D" required class="minimal">
                                                                    <span id="labelDolaresMat" class="labelDolaresMat"></span>
                                                                </label>
                                                                <div style="width:100%">
                                                                    <input type="text" id="dolaresMat" name="dolaresMat" class="form-control" style="width:100%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group ancho">
                                                                <label>¿Incluye IVA?</label>
                                                                <br>
                                                                <input type="checkbox" class='icheckbox_flat-green' id="nivaMat" name="nivaMat">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioAP()" id="btnAsignar">Asignar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        autoCompleteProveedoresAP();
        $("#formAsignaProveedor").validate({ });
        getValorDolarMat();
        autocompleteCotizadores();
        
        $("#nproveedor").change( function() {
            $("#msjAltaProv").text("");
            if (($("#nproveedor").val() != "-1" && $("#nproveedor").val() != null) && $("#idmaterial_AP").val() != "-1")
                $("#btnAsignar").prop("disabled", false);
            else
                $("#btnAsignar").prop("disabled", true);
	    });

        $("#formPbM").validate( {
            rules: {
                nprecioMat: { number: true },
                monedaMat: {
                    required: function() {
                        var c = $('input[type=radio][name=monedaMat]').is(":checked");
                        
                        if (!c)
                            $(".monedas").css("border","1px solid red");
                        else
                            $(".monedas").css("border","1px solid white");
                        return c;
                    }
                },
                dolaresMat: {
                    number: true
                }
            }
        });

        $("#nprecioMat").inputmask (
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#dolaresMat").inputmask (
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        //iCheck for checkbox and radio inputs
        $('input[type="radio"].minimal').iCheck( {
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_square-blue'
        });
        
        $('input[type=radio][name=monedaMat]').on('ifChecked', function() {
            monedaRequeridaMat($(this).val(), 0);
            $('input[type=radio][name=monedaMat]').valid();
        });
        
        $("#nprecioMat").focus( function() {
            $('input[type=radio][name=monedaMat][value=P]').iCheck('check');
            monedaRequeridaMat('P', 0);
        });
        
        $("#dolaresMat").focus( function() {
            $('input[type=radio][name=monedaMat][value=D]').iCheck('check');
            monedaRequeridaMat('D', 0);
        });
        
        $("#dolaresMat").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');

                if ($.isNumeric(p))
                    $("#nprecioMat").val((parseFloat(p) * parseFloat($(".dolarActual").val().replace(/\,/g, ''))).toFixed(4));
                else
                    $("#nprecioMat").val("");
            }
        });

        $("#nprecioMat").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#dolaresMat").val((parseFloat(p) / parseFloat($(".dolarActual").val())).toFixed(4));
                else
                    $("#dolaresMat").val("");
            }
        });
    });

    function validarFormularioAP() {
        if ($("#formAsignaProveedor").valid()) {
            //guardar en BD
            var dataP = $('#nproveedor').select2('data');
            guardarProveedorPrecio($("#idReqDetalle_AP").val(), $("#idmaterial_AP").val(), $("#material_AP").val(), dataP[0].id,dataP[0].text);
            $('#asignaProveedorModal').modal('hide');
        }
    }
</script>