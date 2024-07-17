<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/personal/personalScript.js" type="text/javascript"></script>
<link href="pages/personal/personalStyles.css" rel="stylesheet" type="text/css"/>

<h3>PERSONAL</h3>
<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <?php if ($permisos->acceso("8589934592", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalPersonal()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        <?php endif; ?>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="personalTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha de nacimiento</th>
                    <th>NSS</th>
                    <th>Teléfono</th>
                    <th>Fecha de baja</th>
                    <th>Activo</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php 
    include 'nuevoPersonal.php';
?>
<script type="text/javascript">
    //stores the open rows (detailed view)
    var openRows = new Array();
    
    $( document ).ready( function() {
        var permisoAdministrar = <?php echo json_encode($permisos->acceso("8589934592", $usuario->obtenerPermisos($_SESSION['username']))); ?>;

        loadDataTable(permisoAdministrar);
        
        $('#personalTable tbody').on('click', 'button', function () {
            var data = $("#personalTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarPersonal(data);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdPersonal);
                    $("#warningModal").modal("show");
                break;
            }
        });

        $('#personalTable tbody').on('click', 'td.details-control', function () {
            if (permisoAdministrar) {
                var tr = $(this).closest('tr');
                var row = $('#personalTable').DataTable().row(tr);
                if (row.child.isShown()) {
                    //This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( formatDetalleNomina(row.data())).show();
                    tr.addClass('shown');
                }
            }
        });
        /*Formatting function forl row details - modify as you need*/
        function formatDetalleNomina (d) {
            var datos = "<div class='row detalles'>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Ingreso</label><p>"+ d.FechaIngreso +"</p></div>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Departamento</label><p>"+ d.Depto +"</p></div>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Puesto</label><p>"+ d.Puesto +"</p></div>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Periodo</label><p>"+ d.Periodo +"</p></div>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Sueldo</label><p> $"+ formatNumber( d.Sueldo ) +"</p></div>";
            datos += "</div>";
            return datos;
        }

        $('#personalTable tbody').on('click', 'input.edo', function(event, state) {
            var data = $("#personalTable").DataTable().row($(this).parents('tr')).data();
            
            var pregunta = "";

            if ($(this).prop('checked')) {
                pregunta = "¿Estás seguro de que deseas TERMINAR el proyecto?";
                desactivaPersonal(data.IdPersonal, 1);
            }
            else {
                pregunta = "¿Estás seguro de que deseas ACTIVAR el proyecto?";
                desactivaPersonal(data.IdPersonal, 0);
            }
        });
    });
</script>