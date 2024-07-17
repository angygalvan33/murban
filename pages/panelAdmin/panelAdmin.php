<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<link href="plugins/iCheck/all.css" rel="stylesheet" type="text/css"/>
<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="pages/panelAdmin/panelAdminScript.js" type="text/javascript"></script>
<link href="pages/panelAdmin/panelAdminStyles.css" rel="stylesheet" type="text/css"/>

<h3>PANEL ADMINISTRATIVO</h3>
<br>
<form id="datosEmpresaForm">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Nombre de la Empresa</label>
                    <input type="text" id="nombreEmpresa" name="nombreEmpresa" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>RFC</label>
                    <input type="text" id="rfcEmpresa" name="rfcEmpresa" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="text" id="emailEmpresa" name="emailEmpresa" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" id="direccionEmpresa" name="direccionEmpresa" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Municipio</label>
                    <input type="text" id="municipioEmpresa" name="municipioEmpresa" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Estado</label>
                    <input type="text" id="edoEmpresa" name="edoEmpresa" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Representante</label>
                    <input type="text" id="representanteEmpresa" name="representanteEmpresa" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" id="telefonoEmpresa" name="telefonoEmpresa" class="form-control" required data-inputmask='"mask": "(999) 999-9999"' data-mask>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Valor máximo OC sin autorización</label>
                    <input type="text" id="maximoSinAutorizacionEmpresa" name="maximoSinAutorizacionEmpresa" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-warning btn-block" onclick="editarDatosEmpresa()" type="button">Editar</button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-success btn-block" onclick="guardarDatosEmpresa()" type="button">Guardar</button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <label>Logotipo</label>
                <br>
                <img id="logo" src="" alt="logo_empresa" height="160px" width="200px">
                <br>
                <br>
                <input type='file' accept="image/png, image/jpeg" id="fileLogo"/>
                <br>
                <button class="btn btn-primary" onclick="cambiarLogo()">Subir logo</button>
            </div>
        </div>
        <br>
    </div>
</form>
<div class="col-md-12" style="padding-top: 15px !important">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Políticas de compras</label>
                <textarea id="politicasCompras" rows="3" class="form-control" style="resize: none"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-warning btn-block" onclick="editarPoliticasCompras()" type="button">Editar</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success btn-block" onclick="guardarPoliticasCompras()" type="button">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        leerDatosEmpresa();
        cargarLogo();
        $("#datosEmpresaForm input[type='text']").prop("disabled", true);
        $("#politicasCompras").prop("disabled", true);
        
        $("#datosEmpresaForm").validate({
            rules: {
                emailEmpresa: { email: true }
            }
	    });
        
        $('[data-mask]').inputmask();
        
        $('#subirLogo').click( function () {
            cambiarLogo();
        });
        
        $("#maximoSinAutorizacionEmpresa").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );
    });
</script>