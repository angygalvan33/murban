<?php
include_once '../../clases/conexion.php';
include_once '../../clases/usuario.php';
include_once '../../clases/correo.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $us = new Usuario();
    $us->llenaDatos(-1, $_POST['nombre'], $_POST['usuario'], $_POST['email']);
    $us->inserta();
}
//editar
else if ($accion == 'editar') {
    $us = new Usuario();
    $us->llenaDatos($_POST['id'], $_POST['nombre'], $_POST['usuario'], $_POST['email']);
    $us->editar();
}
//baja
else if ($accion == 'restablecerPassword') {
    $idUs = $_POST['idUsuario'];
    $email = $_POST['email'];
    $correo = new Correo($idUs);
    $correo->enviarCorreo();
}
else if ($accion == 'activarUsuario') {
    $us = new Usuario();
    $idUs = $_POST['idUsuario'];
    $us->activar($idUs);
}
else if ($accion == 'desactivarUsuario') {
    $us = new Usuario();
    $idUs = $_POST['idUsuario'];
    $us->desactivar($idUs);
}
else if ($accion == 'editarPermisos') {
    $us = new Usuario();
    $idUs = $_POST['idUsuario'];
    $numPermisos = $_POST['numPermisos'];
    $us->establecerPermisos($idUs, $numPermisos);
}
else if ($accion == 'obtenerNuevoID') {
    $con = new Conexion();
    $con->obtenerNuevoIdUsuario();
}
else if($accion == 'eliminarUsuario') {
    $us = new Usuario();
    $idUs = $_POST['idUsuario'];
    $us->baja($idUs);
}