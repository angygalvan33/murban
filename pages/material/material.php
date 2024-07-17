<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/material/materialScript.js" type="text/javascript"></script>
<link href="pages/material/materialStyles.css" rel="stylesheet" type="text/css"/>

<h3>MATERIALES</h3>
<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-2">
        <?php if ($permisos->acceso("128", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <!--<button type="button" class="btn btn-success btn-block" onclick="descargaCatalogoMateriales()"><i class="fa fa-download"></i>&nbsp;Descargar catálogo</button>-->
        <?php endif; ?>
    </div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <?php if ($permisos->acceso("128", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalMat()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        <?php endif; ?>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="matTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>SKU</th>
                    <th>Descripción</th>
                    <th>Medida</th>
                    <th>Categoría</th>
                    <th>Actualiza</th>
					<th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevoMaterial.php';
?>
<script type="text/javascript">
    var openRows = new Array();
    $( document ).ready( function() {
        var permisoAdministrar = <?php echo json_encode($permisos->acceso("128", $usuario->obtenerPermisos($_SESSION['username']))); ?>;

        loadDataTable(permisoAdministrar);
        
        $('#matTable tbody').on('click', 'button', function () {
            var data = $("#matTable").DataTable().row($(this).parents('tr')).data();
            
            switch($(this).attr("id")) {
                case "editar":
                    loadEditarMaterial(data);
                break;
                case "eliminar":
                    $("#tipo").val(1);
                    $("#idRegistro").val(data.IdMaterial);
                    $("#warningModal").modal("show");
                break;
                case "precioMatProveedor":
                    loadDetalleMateriales($(this), 0);
                break;
            }
        });
        //Add event listener for opening and closing details
        $('#matTable tbody').on('click', 'td.details-control', function () {
            loadDetalleMateriales($(this), 1);
        });
    });
    
    function loadDetalleMateriales(registro, tipo) {
        var tr = registro.closest('tr');
        var row = $('#matTable').DataTable().row(tr);
        
        if (row.child.isShown()) {
            //This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            //Open this row
            if ($('#matTable').DataTable().row('.shown').length) {
                $('.details-control', $('#matTable').DataTable().row('.shown').node()).click();
            }
            row.child(format(row.data(),tipo)).show();
            tr.addClass('shown');
        }
    }
    /*Formatting function forl row details - modify as you need*/
    function format (rowData, tipo) {
        var clases = "row detalles listaProveedoresMaterial";
        
        if (tipo === 1)
            clases = "row detalles";
        
        var div = $('<div/>', { class:clases, id:rowData.IdMaterial });
        div.load("pages/material/listadoProveedores/listadoProveedores.php");
        return div;
    }

    function descargaCatalogoMateriales() {
        window.location.href = "./excel/reportes/catalogoMateriales.php";
    }
</script>