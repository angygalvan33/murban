<?php
set_include_path(get_include_path(). PATH_SEPARATOR .'../../../phpseclib');
include_once "Net/SSH2.php";
include "../../../config.php";
include_once '../../../clases/permisos.php';
include_once '../../../clases/usuario.php';

$permisos = new Permisos();
$usuario = new Usuario();

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (!$permisos->acceso("1073741824", $usuario->obtenerPermisos($_SESSION['username']))) {
    $search = $_SESSION['username'];
}

$conCaja = $_POST['conCaja'];
$usuario->getUsuariosCajaChica($search, $conCaja);