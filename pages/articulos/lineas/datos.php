<?php
include_once '../../../clases/linea.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $lin = new Linea();
    $lin->llenaDatos(-1, $_POST['nombre']);
    $lin->inserta();
}
//baja
else if($accion == 'baja') {
    $lin = new Linea();
    return $lin->baja($_POST['id']);
}
//editar
else if($accion == 'editar') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $lin = new Linea();
    $lin->llenaDatos($_POST['id'], $_POST['nombre']);
    $lin->editar();
}