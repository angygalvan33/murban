<script src="pages/compras/requisiciones/requisicionesScript.js" type="text/javascript"></script>

<input type="hidden" value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" class="usuario-container" data-nombre="<?php echo $usuario->getNameFromUsername($_SESSION['username']);?>">
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">
            <input class='icheckbox_flat-green' type='checkbox' id="mostrarTodo" name="mostrarTodo" checked>
            <label>Mostrar todo</label>
        </div>
        <div class="col-md-4">
            <label>Proveedor</label>
            <select id='provSeleccionados' class='form-control' disabled="true" style="width:100%"></select>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-primary btn-sm btn-block" id="solicitaNuevaOC" onclick="solicitarNuevaOC_Req(1)" disabled="true">Solicita OC</button>    
        </div>
    </div>
    <br>
    <table id="requisicionesTable" class="table table-hover table-responsive">
        <thead>
            <tr>
                <th>Cantidad(Pzas)</th>
				<th>Material</th>
                <th>Observaciones</th>
                <th>Medida</th>
                <th>Precio</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var tablaRequisiciones = null;
    var tablaDetalleRequisiciones = null;
    var tipoQuery = 0;
    var idProveedor = 0;
    idsDetalleReq = [];
    
    $( document ).ready( function() {
        loadDataTableRequisiciones(tipoQuery, 0);
        autoCompleteProveedoresRequisiciones();
        
        $('#mostrarTodo').iCheck( {
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_square',
            increaseArea: '20%' //optional
        });
        
        $('#mostrarTodo').on('ifChecked', function (event) {
            $("#provSeleccionados").prop("disabled", true);
            $("#provSeleccionados").empty();
            $("#solicitaNuevaOC").prop("disabled", true);
            tipoQuery = 0;
            idProveedor = 0;
            loadDataTableRequisiciones(tipoQuery, idProveedor);
            resetRequisiciones(0);
        });

        $('#mostrarTodo').on('ifUnchecked', function (event) {
            $("#provSeleccionados").prop("disabled", false);
            $("#solicitaNuevaOC").prop("disabled", true);
            tipoQuery = 1;
            idProveedor = 0;
            loadDataTableRequisiciones(tipoQuery, idProveedor);
            resetRequisiciones(0);
        });
        
        $("#provSeleccionados").change(function() {
            idsDetalleReq = [];
            resetValuesOCReq();
            var dataP = $('#provSeleccionados').select2('data');
            tipoQuery = 1;
            resetRequisiciones(0);

            if (dataP.length > 0) {
                $("#solicitaNuevaOC").prop("disabled", false);
                idProveedor = dataP[0].id;
                $("#valIdProvOCReq").val(idProveedor);
                $("#proveedorOCReq").append(new Option(dataP[0].text, dataP[0].id));
                loadDataTableRequisiciones(tipoQuery, idProveedor);
            }
            else {
                $("#valIdProvOCReq").val("");
                $("#proveedorOCReq").empty();
                $("#solicitaNuevaOC").prop("disabled", true);
                idProveedor = 0;
                loadDataTableRequisiciones(tipoQuery, idProveedor);
            }
	    });
        
        $('#requisicionesTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requisicionesTable').DataTable().row(tr);

            if (row.child.isShown()) {
                //This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requisicionesTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#requisicionesTable').DataTable().row('.shown').node()).click();
                }
                //Open this row
                row.child(formatDetalleMateriales(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    
    function formatDetalleMateriales (d) {
        var div = $('<div/>', { class:'row detallesMatRequisiciones', id:d.IdMaterial });
        div.load("pages/compras/requisiciones/detalleMateriales/listadoMateriales.php");
        return div;
    }
</script>