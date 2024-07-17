<?php
include_once '../../clases/metodoPago.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $_POST['referencia'] = empty($_POST['referencia']) == TRUE ? '' : $_POST['referencia'];
    
    $mP = new MetodoPago();
    $mP->llenaDatos(-1, $_POST['nombre'], $_POST['referencia']);
    $mP->inserta();
}
//baja
else if($accion == 'baja') {
    $mP = new MetodoPago();
    return $mP->baja($_POST['id']);
}
//editar
else if($accion == 'editar') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $_POST['referencia'] = empty($_POST['referencia']) == TRUE ? '' : $_POST['referencia'];
    
    $mP = new MetodoPago();
    $mP->llenaDatos($_POST['id'], $_POST['nombre'], $_POST['referencia']);
    $mP->editar();
}