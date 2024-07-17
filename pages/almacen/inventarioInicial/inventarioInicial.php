<script src="pages/almacen/inventarioInicial/inventarioInicial.js" type="text/javascript"></script>

<div class="well" style="display: block;">
    <form id="form_inventarioInicial" role="form" >
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Material</label>
                            <br>
                            <select id="iimaterial" name="iimaterial" class="form-control iimaterial" required="" style="width:100% !important"></select>
                            <br>
                            <!--<div style="width:100%; text-align:right; margin-top: 5px">
                                <button type="button" class="btn btn-primary btn-sm" onclick="altaMaterialII()">Alta Material</button>
                            </div>-->
                        </div>
                        <div class="col-md-2">
                            <label>Cantidad</label>
                            <br>
                            <input id="iicantidad" name="iicantidad" class="form-control iicantidad" required="" type="text">
                        </div>
                        <div class="col-md-3">
                            <label>Precio unitario (MXN) sin IVA</label>
                            <br>
                            <input id="iiprecio" name="iiprecio" class="form-control iiprecio" required="" type="text">
                        </div>
                        <div class="col-md-3">
                            <br>
                            <button type="button" class="btn btn-bitbucket btn-block" onclick="agregarMaterialInventarioInicial()">Agregar a inventario</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12 table-responsive">
    <table id="inventarioInicialTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Descripción</th>
                <th>Medida</th>
                <th>Categoría</th>
                <th>Cantidad</th>
            </tr>
        </thead>
    </table>
</div>
<?php
    include_once '../../pages/material/nuevoMaterial.php';
?>
<div id="iieditartMaterialModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Material</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idMaterialII">
                <form id="formMatII" role="form">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="text" id="iicant" name="iicant" class="form-control iicantidad" required maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Precio unitario (MXN)</label>
                        <input type="text" id="iiprec" name="iiprec" class="form-control iiprecio" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarMaterialII($('#idMaterialII').val(), $('#iicant').val(), $('#iiprec').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaTablaInventarioInicial();
        autoCompleteMaterialesInventario();
        
        $("#form_inventarioInicial").validate( {
            rules: {
                iicantidad: { number: true },
                iiprecio: { number: true }
            }
	    });
        
        $(".iiprecio").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $(".iicantidad").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $('#inventarioInicialTabla').on( 'click', 'button', function () {
            var data = $("#inventarioInicialTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "iieditar":
                    loadEditarMaterialInventario(data);
                break;
                case "iieliminar":
                    $("#idRegistro").val(data.IdMaterial);
                    $("#tipo").val(1);
                    $("#warningModal").modal("show");
                break;
            }
        });
        
        $("#iimaterial").change(function() {
           var dataU = $('#iimaterial').select2('data');
           
           if (dataU.length > 0)
               getPrecioMaterialInventarioInicial(dataU[0].id);
           else
               $("#usuarioCUValue").val("-1");
           
           $('#usuarioCChTable').DataTable().ajax.reload();
	    });
    });
    
    function agregarMaterialInventarioInicial() {
        if ($("#form_inventarioInicial").valid()) {
            agregarAInventarioInicial();
        }
    }

    function altaMaterialII() {
        $("#nuevoMatModal").modal();
    }
    
    function editarMaterialII(idMaterial, cantidad, precio) {
        if ($("#formMatII").valid())
            editarMaterial_InventarioInicial(idMaterial, cantidad, precio);
        $('#iieditartMaterialModal').modal('hide');
    }
</script>