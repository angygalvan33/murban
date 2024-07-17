<?php

include_once '../../clases/usuario.php';

$accion = $_POST['accion'];

if($accion == 'cambiarContrasena')
{
    $usr = new Usuario();
    $idUsuario = $usr->getIdFromUsername($_POST['username']);
    $usr->cambiarContrasena($idUsuario, $_POST['username'], $_POST['contraAnt'], $_POST['contraNueva'], $_POST['contraConf']);
}
