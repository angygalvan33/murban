<?php
set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
include_once "Net/SSH2.php";
include_once '../../clases/menu.php';
include_once '../../clases/usuario.php';

$accion = $_POST['accion'];

if ($accion == 'JSON') {
    $menu = new Menu();
    $menu->construirJSON();
}
else if ($accion == 'nodes') {
    $usuario = new Usuario();
    echo json_encode($usuario->obtenerNombresPermisos($_POST['permisos']));
}