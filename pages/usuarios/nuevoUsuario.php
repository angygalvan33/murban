<script src="pages/usuarios/usuariosScript.js" type="text/javascript"></script>
<link href="pages/usuarios/usuariosStyles.css" rel="stylesheet" type="text/css"/>

<div id="nuevoUsuarioModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Usuario</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formUsuario" role="form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input type="text" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" required>
                    </div>
                    <br>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarFormularioUsuarios($('#accion').val(), $('#idRegistro').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#formUsuario").validate( {
            rules: {
                email: {
                    email: true
                },
                contrasena: {
                    minlength: 8
                }
            }
	    });
    });
    
    function validarFormularioUsuarios(accion, idRegistro) {
        if ($("#formUsuario").valid()) {
            guardarUsuario(accion, idRegistro);
            $('#nuevoUsuarioModal').modal('hide');
        }
    }
</script>