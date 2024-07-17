<?php

var_dump($_POST);
$accion = $_POST['accion'];

if($accion == 'cambiarImagen')
{
    var_dump (file_get_contents($_POST['archivo']));
    $result = file_put_contents("./pbas/pruebaImagen/logo_empresa.png", file_get_contents($_POST['archivo']));

    return $result;
}