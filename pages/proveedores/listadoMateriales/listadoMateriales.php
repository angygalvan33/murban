<script src="pages/proveedores/listadoMateriales/listadoMaterialesScript.js" type="text/javascript"></script>
<link href="pages/proveedores/listadoMateriales/listadoMaterialesStyles.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .ancho {
        width:100% !important;
    }
</style>

<div style="margin:10px 10px">
    <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
        <legend class="scheduler-border">Material de Proveedor</legend>
        <form id="formMbP">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group ancho">
                        <label>Material:</label>
                        <select class="form-control ancho nmaterial" id="nmaterial" name="nmaterial" required>
                            </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group ancho">
                        <label>¿Quién atendió?:</label>
                        <select class="form-control ancho ncotizador" id="ncotizador" name="ncotizador" required></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <div class="form-group">
                        <button type="button" class="btn btn-bitbucket btn-sm" onclick="guardaNuevoMaterialByProveedor()"><i class='fa fa-plus'></i>&nbsp;Agregar</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Precio</legend>
                            <input type="hidden" id="dolarActual" class="dolarActual">
                            <div class="form-group">
                                <div class="col-md-10 monedas">
                                    <div class="col-md-6">
                                        <label>
                                            <input type="radio" name="moneda" value="P" required class="minimal">Pesos
                                        </label>
                                        <div style="width:100%">
                                            <input type="text" id="nprecio" name="nprecio" class="form-control" style="width:100%">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>
                                            <input type="radio" name="moneda" value="D" required class="minimal">
                                            <span id="labelDolares" class="labelDolares"></span>
                                        </label>
                                        <div style="width:100%">
                                            <input type="text" id="dolares" name="dolares" class="form-control" style="width:100%">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group ancho">
                                        <label>¿Incluye IVA?</label>
                                        <br>
                                        <input type="checkbox" class='icheckbox_flat-green' id="niva" name="niva">
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
    <table id="listadoMaterialesTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Material</th>
                <th>Medida</th>
                <th>Precio</th>
                <th>Fecha de cotización</th>
                <th>Atendió</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<!--eliminar material de proveedor modal-->
<div id="eliminarMatModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas eliminar el registro?</p>
                <input type="hidden" id="idProveedorM">
                <input type="hidden" id="idMaterialM">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                <button id="eliminarRegistro" type="button" class="btn btn-outline" data-dismiss="modal" onclick="eliminarMaterial($('#idProveedorM').val(),$('#idMaterialM').val())">Eliminar</button>
              </div>
        </div>
    </div>
</div>
<?php
    include './precioMaterialModal.php';
    include './historicoMaterialesModal.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        getValorDolar();
        loadListadoMaterialesDataTable();
        $('.nmaterial').select2( {
            ajax: {
                url: './pages/proveedores/listadoMateriales/autocompleteMateriales.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term //search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });
        
        $('.ncotizador').select2( {
            tags: true,
            ajax: {
                url: './pages/proveedores/listadoMateriales/autocompleteCotizadores.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, //search term
                        IdProveedor: $(".detalles").attr("id")
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });

        $('#listadoMaterialesTable tbody').on('click', 'button', function () {
            var data = $("#listadoMaterialesTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editarPrecio":
                    $("#precioMaterialModal #idProveedor").val($(".detalles").attr("id"));
                    $("#precioMaterialModal #idMaterial").val(data.IdMaterial);
                    openPrecioMaterialModal(data);
                break;
                case "historico":
                    $("#historicoMaterialesModal #idProveedor").val($(".detalles").attr("id"));
                    $("#historicoMaterialesModal #idMaterial").val(data.IdMaterial);
                    $("#historicoMaterialesModal #nombreMaterial").html("<p><strong>Material:&nbsp;</strong>"+ data.Material +"</p>");
                    $("#historicoMaterialesModal #nombreProveedor").html("<p><strong>Proveedor:&nbsp;</strong>"+ data.Proveedor +"</p>");
                    historicoReload();
                    $('#historicoMaterialesModal').modal('show');
                break;
                case "eliminarPrecios":
                    $("#idProveedorM").val(data.IdProveedor);
                    $("#idMaterialM").val(data.IdMaterial);
                    $("#eliminarMatModal").modal("show");
                break;
            }
        });
        
        $("#formMbP").validate( {
            rules: {
                nprecio: { number: true },
                moneda: {
                    required: function() {
                        var c = $('input[type=radio][name=moneda]').is(":checked");
                        
                        if (!c)
                            $(".monedas").css("border","1px solid red");
                        else
                            $(".monedas").css("border","1px solid white");
                        return c;
                    }
                },
                dolares: {
                    number: true
                }
            }
	    });
        
        $("#nprecio").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 4,
                autoGroup: true
            }
        );

        $("#dolares").inputmask(
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
            radioClass   : 'iradio_square-blue'
        });
        
        $('input[type=radio][name=moneda]').on('ifChecked', function() {
            monedaRequerida($(this).val(), 0);
            $('input[type=radio][name=moneda]').valid();
        });
        
        $("#nprecio").focus( function() {
            $('input[type=radio][name=moneda][value=P]').iCheck('check');
            monedaRequerida('P', 0);
        });
        
        $("#dolares").focus( function() {
            $('input[type=radio][name=moneda][value=D]').iCheck('check');
            monedaRequerida('D', 0);
        });
        
        $("#dolares").keyup( function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#nprecio").val((parseFloat(p) * parseFloat($(".dolarActual").val().replace(/\,/g, ''))).toFixed(4));
                else
                    $("#nprecio").val("");
            }
        });
        
        $("#nprecio").keyup(function() {
            if ($(this).val() != "" && $(this).val() != "undefined" && $(".dolarActual").val() != "" && $(".dolarActual").val() != "undefined") {
                var p = $(this).val().replace(/\,/g, '');
                
                if ($.isNumeric(p))
                    $("#dolares").val((parseFloat(p) / parseFloat($(".dolarActual").val())).toFixed(4));
                else
                    $("#dolares").val("");
            }
        });
    });
    
    function guardaNuevoMaterialByProveedor() {
        if ($("#formMbP").valid())
            nuevoMaterialByProveedor($('.detalles').attr('id'));
    }
    
    function historicoReload() {
        $('#historicoTable').DataTable().ajax.reload();
    }
</script>