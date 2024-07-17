<script src="pages/usuarios/usuariosScript.js" type="text/javascript"></script>
<link href="pages/usuarios/usuariosStyles.css" rel="stylesheet" type="text/css"/>

<h3>USUARIOS</h3>
<div class="row">
    <div class="col-md-10"> </div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalUsuario()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="usuariosTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
             </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevoUsuario.php';
    include 'editarPermisos.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTableUsuarios();
        //USUARIOS
        $('#usuariosTable tbody').on('click', 'button', function () {
            var data = $("#usuariosTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarUsuario(data);
                break;
                case "restablecer":
                    restablecerPassword(data.IdUsuario);
                break;
                case "verPermisos":
                    loadEditarPermisos(data.IdUsuario, data.Permisos);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdUsuario);
                    $("#warningModal").modal();
                break;
            }
        });
        
        $('#usuariosTable tbody').on('click', 'input.edo', function(event, state) {
            var data = $("#usuariosTable").DataTable().row($(this).parents('tr')).data();
            cambioEdoUsuario(data.IdUsuario, $(this).prop('checked'));
        });
    });
</script>