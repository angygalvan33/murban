<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/proveedores/proveedoresScript.js" type="text/javascript"></script>
<link href="pages/proveedores/proveedoresStyles.css" rel="stylesheet" type="text/css"/>

<h3>PROVEEDORES</h3>
<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-2">
        <?php if ($permisos->acceso("4", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <!--<button type="button" class="btn btn-success btn-block" onclick="descargaCatalogoProveedores()"><i class="fa fa-download"></i>&nbsp;Descargar catálogo</button>-->
        <?php endif; ?>
    </div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <?php if ($permisos->acceso("4", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalMP()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        <?php endif; ?>
        <input type="hidden" id="rowidx">
	</div>
    <div class="col-md-12 table-responsive">
        <table id="provTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Representante</th>
                    <th>Correo electrónico</th>
                    <th></th>
					<th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevoProveedor.php';
?>
<script type="text/javascript">
    // stores the open rows (detailed view)
    var openRows = new Array();
    
    $( document ).ready( function() {
        var permisoAdministrar = <?php echo json_encode($permisos->acceso("4", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var permisoCotizar = <?php echo json_encode($permisos->acceso("8", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        
        loadDataTable(permisoAdministrar, permisoCotizar);
        
        $('#provTable tbody').on('click', 'button', function () {
            var data = $("#provTable").DataTable().row($(this).parents('tr')).data();
            
            switch($(this).attr("id")) {
                case "editar":
                    loadEditarProveedor(data);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdProveedor);
                    $("#warningModal").modal("show");
                break;
                case "precioMaterial":
                    loadEditarPrecioMateriales($(this));
                break;
				case "precioMaterialKg":
                    loadEditarPrecioMaterialesKg($(this));
                break;
            }
        });
        //Add event listener for opening and closing details
        $('#provTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#provTable').DataTable().row(tr);

            if (row.child.isShown()) {
                //This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function format (d) {
        //`d` is the original data object for the row
        var datos = "<div class='row detalles'>";
        datos += "<div class='col-md-3'><label class='control-label negritas'>RFC</label><p>"+ d.Rfc +"</p></div>";
        datos += "<div class='col-md-3'><label class='control-label negritas'>Dirección</label><p>"+ d.Direccion +"</p></div>";
        datos += "<div class='col-md-3'><label class='control-label negritas'>Días de crédito</label><p>"+ d.DiasCredito +"</p></div>";
        datos += "<div class='col-md-3'><label class='control-label negritas'>Límite de crédito</label><p> $"+ formatNumber(d.LimiteCredito) +"</p></div>";
        datos += "</div>";
        return datos;
    }
    
    function loadEditarPrecioMateriales(btn) {
        var tr = btn.closest('tr');
        var row = $('#provTable').DataTable().row(tr);

        if (row.child.isShown()) {
            //This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            btn.find(".openPrecio").remove();
        }
        else {
            closeOpenedRows($('#provTable').DataTable(), tr);
            row.child(edicionPrecios(row.data())).show();
            tr.addClass('shown');
            //store current selection
            openRows.push(tr);
            btn.append("<i class='fa fa-level-down openPrecio' aria-hidden='true'></i>");
        }
    }
    
	function loadEditarPrecioMaterialesKg(btn) {
        var tr = btn.closest('tr');
		$("#rowidx").val(tr.index());
        var row = $('#provTable').DataTable().row(tr);

        if (row.child.isShown()) {
            //This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            btn.find(".openPrecioKg").remove();
        }
        else {
            closeOpenedRowsKg($('#provTable').DataTable(), tr);
            row.child(edicionPreciosKg(row.data())).show();
            tr.addClass('shown');
            // store current selection
            openRows.push(tr);
            btn.append("<i class='fa fa-level-down openPrecioKg' aria-hidden='true'></i>");
        }
    }
	
	function loadEditarPrecioMatupdate(btn, arow) {
        var tr = arow;
		var row = $('#provTable').DataTable().row(tr);

		closeOpenedRowsKg($('#provTable').DataTable(), tr);
        openRows.push(tr);
        btn.append("<i class='fa fa-level-down openPrecioKg' aria-hidden='true'></i>");
    }
	
    function edicionPrecios(rowData) {
        var div = $('<div/>', { class:'row detalles', id:rowData.IdProveedor });
        div.load("pages/proveedores/listadoMateriales/listadoMateriales.php");
        return div;
    }
    
	function edicionPreciosKg(rowData) {
        var div = $('<div/>', { class:'row detalles', id:rowData.IdProveedor });
        div.load("pages/proveedores/listadoMaterialesKg/listadoMaterialesKg.php");
        return div;
    }
	
    function closeOpenedRows(table, selectedRow) {
        $(".precioDetail").html("<i class='fa fa-usd'></i>&nbsp;Precio");
        $.each (openRows, function (index, openRow) {
            // not the selected row!
            if ($.data(selectedRow) !== $.data(openRow)) {
                var rowToCollapse = table.row(openRow);
                rowToCollapse.child.hide();
                openRow.removeClass('shown');
                // remove from list
                var index = $.inArray(selectedRow, openRows);
                openRows.splice(index, 1);
            }
        });
    }
	
	function closeOpenedRowsKg(table, selectedRow) {
        $(".precioDetailKg").html("<i class='fa fa-usd'></i>&nbsp;PrecioxKilo");
        $.each (openRows, function (index, openRow) {
            // not the selected row!
            if ($.data(selectedRow) !== $.data(openRow)) {
                var rowToCollapse = table.row(openRow);
                rowToCollapse.child.hide();
                openRow.removeClass('shown');
                // remove from list
                var index = $.inArray(selectedRow, openRows);
                openRows.splice(index, 1);
            }
        });
    }

    function descargaCatalogoProveedores() {
        window.location.href = "./excel/reportes/catalogoProveedores.php";
    }
</script>