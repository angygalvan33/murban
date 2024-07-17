<script src="pages/material/listadoProveedores/listadoProveedoresScript.js" type="text/javascript"></script>
<link href="pages/material/listadoProveedores/listadoProveedoresStyles.css" rel="stylesheet" type="text/css"/>

<style type="text/css">
    .ancho {
        width:100% !important;
    }
</style>

<div style="margin:10px 10px" id="listaProv">
    <fieldset class="scheduler-border" style="margin-bottom: 0px !important;" id="nuevoProvMat">
        <legend class="scheduler-border">Proveedor de Material</legend>
        <form id="formPbM">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group ancho">
                        <label>Proveedor:</label>
                        <select class="form-control ancho nproveedor" id="nproveedor" name="nproveedor" required>
                            </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group ancho">
                        <label>¿Quién atendió?:</label>
                        <select class="form-control ancho ncotizador" id="ncotizador" name="ncotizador" required> </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <div class="form-group">
                        <button type="button" class="btn btn-bitbucket btn-sm" onclick="guardaNuevoProveedorByMaterial()"><i class='fa fa-plus'></i>&nbsp;Agregar</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Precio</legend>
                            <input type="hidden" id="dolarActualMat" class="dolarActual">
                            <div class="form-group">
                                <div class="col-md-10 monedas">
                                    <div class="col-md-6">
                                        <label>
                                            <input type="radio" name="monedaMat" value="P" required class="minimal">
                                            Pesos
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
    <br>
    <table id="listadoProveedoresTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Proveedor</th>
                <th>Precio</th>
                <th>¿Capturado con IVA?</th>
                <th>Atendió</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<?php
    include './precioProveedorMaterialModal.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        var editarPrecio = false;
        
        if ($("#listaProv").parents().hasClass("listaProveedoresMaterial")) {
            $("#nuevoProvMat").css("display", "block");
            editarPrecio = true;
        }
        else
            $("#nuevoProvMat").css("display", "none");
        
        loadListadoProveedoresDataTable(editarPrecio);
        getValorDolarMat();
        autocompleteProveedores();
        autocompleteCotizadores();
        
        $("#formPbM").validate( {
            rules: {
                nprecioMat: { number: true },
                monedaMat: {
                    required: function() {
                        var c = $('input[type=radio][name=monedaMat]').is(":checked");

                        if (!c)
                            $(".monedas").css("border", "1px solid red");
                        else
                            $(".monedas").css("border", "1px solid white");
                        return c;
                    }
                },
                dolaresMat: {
                    number: true
                }
            }
	    });
        
        $("#nprecioMat").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#dolaresMat").inputmask(
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
        
        $('#listadoProveedoresTable tbody').on('click', 'button', function () {
            var data = $("#listadoProveedoresTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editarPrecioMat":
                    $("#precioProveedorMaterialModal #idMaterial").val($(".detalles").attr("id"));
                    $("#precioProveedorMaterialModal #idProveedor").val(data.IdProveedor);
                    openPrecioProveedoresMatModal(data);
                break;
            }
        });
    });
    
    function guardaNuevoProveedorByMaterial() {
        if ($("#formPbM").valid())
            nuevoProveedorByMaterial($('.detalles').attr('id'));
    }
</script>