<script src="pages/compras/requisicionesxkilo/requisicionesScriptxkilo.js" type="text/javascript"></script>

<input type="hidden" value="<?php echo $usuario->getIdFromUsername($_SESSION['username']) ?>" class="usuario-container" data-nombre="<?php echo $usuario->getNameFromUsername($_SESSION['username']);?>">
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">
            <input class='icheckbox_flat-green' type='checkbox' id="mostrarTodoxkilo" name="mostrarTodoxkilo" checked>
            <label>Mostrar todo</label>
        </div>
        <div class="col-md-4">
            <label>Proveedor</label>
            <select id='provSeleccionadosxkilo' class='form-control' disabled="true" style="width:100%"></select>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-primary btn-sm btn-block" id="solicitaNuevaOCxkilo" onclick="solicitarNuevaOC_Reqxkilo(1)" disabled="true">Solicita OC</button>
        </div>
    </div>
    <br>
    <table id="requisicionesTablexkilo" class="table table-hover table-responsive">
        <thead>
            <tr>
                <th>Cantidad</th>
				<th>Material</th>
                <th>Observaciones</th>
                <th>Medida</th>
                <th>Precio</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var tablaRequisicionesxkilo = null;
    var tablaDetalleRequisicionesxkilo = null;
    var tipoQueryxkilo = 0;
    var idProveedorxkilo = 0;
    idsDetalleReq = [];
    
    $( document ).ready(function() {
        loadDataTableRequisicionesxkilo(tipoQueryxkilo, 0);
        autoCompleteProveedoresRequisicionesxkilo();

        $('#mostrarTodoxkilo').iCheck( {
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_square',
            increaseArea: '20%' //optional
        });
        
        $('#mostrarTodoxkilo').on('ifChecked', function (event) {
            $("#provSeleccionadosxkilo").prop("disabled", true);
            $("#provSeleccionadosxkilo").empty();
            $("#solicitaNuevaOC").prop("disabled", true);
            tipoQuery = 0;
            idProveedor = 0;
            loadDataTableRequisicionesxkilo(tipoQuery, idProveedor);
            resetRequisicionesxkilo(0);
		});

        $('#mostrarTodoxkilo').on('ifUnchecked', function (event) {
            $("#provSeleccionadosxkilo").prop("disabled", false);
            $("#solicitaNuevaOC").prop("disabled", true);
            tipoQuery = 1;
            idProveedor = 0;
            loadDataTableRequisicionesxkilo(tipoQuery, idProveedor);
            resetRequisicionesxkilo(0);
        });
        
        $("#provSeleccionadosxkilo").change( function() {
            idsDetalleReq = [];
            resetValuesOCReq();
            var dataP = $('#provSeleccionadosxkilo').select2('data');
            tipoQuery = 1;
            resetRequisicionesxkilo(0);

            if (dataP.length > 0) {
                $("#solicitaNuevaOCxkilo").prop("disabled", false);
                idProveedor = dataP[0].id;
                $("#valIdProvOCReq").val(idProveedor);
                $("#proveedorOCReq").append(new Option(dataP[0].text, dataP[0].id));
                loadDataTableRequisicionesxkilo(tipoQuery, idProveedor);
            }
            else {
                $("#valIdProvOCReq").val("");
                $("#proveedorOCReq").empty();
                $("#solicitaNuevaOCxkilo").prop("disabled", true);
                idProveedor = 0;
                loadDataTableRequisicionesxkilo(tipoQuery, idProveedor);
            }
	    });
        
        $('#requisicionesTablexkilo tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requisicionesTablexkilo').DataTable().row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requisicionesTablexkilo').DataTable().row('.shown').length) {
                    $('.details-control', $('#requisicionesTablexkilo').DataTable().row('.shown').node()).click();
                }
                // Open this row
                row.child(formatDetalleMaterialesxkilo(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    
    function formatDetalleMaterialesxkilo (d) {
		var div = $('<div/>', { class:'row detallesMatRequisicionesxkilo', id:d.IdMaterial });
        div.load("pages/compras/requisicionesxkilo/detalleMaterialesxkilo/listadoMaterialesxkilo.php");
        return div;
    }
</script>