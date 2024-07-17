<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/categoria/categoriaScript.js" type="text/javascript"></script>
<link href="pages/categoria/categoriaStyles.css" rel="stylesheet" type="text/css"/>

<h3>CATEGORÍAS</h3>
<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <?php if ($permisos->acceso("512", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalCat()"><i class="fa fa-plus"></i>&nbsp;Nueva</button>
        <?php endif; ?>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="catTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Gasto aproximado</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevaCategoria.php';
?>
<script type="text/javascript">
    $( document ).ready(function() {
        var permisoAdministrar = <?php echo json_encode($permisos->acceso("512", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        loadDataTable(permisoAdministrar);
        
        $('#catTable tbody').on('click', 'button', function () {
            var data = $("#catTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarCategoria(data);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdCategoria);
                    $("#warningModal").modal("show");
                break;
            }
        });
        
        $('#catTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#catTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#catTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#catTable').DataTable().row('.shown').node()).click();
                }
                row.child( formatCat(row.data()) ).show();
                tr.addClass('shown');
            }
        });
    });
    
    function formatCat (rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdCategoria });
        divDetalles.load("pages/categoria/detalleCategoria/detalleCategoria.php");
        return divDetalles;
    }
</script>