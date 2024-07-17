<?php
include_once '../../clases/categoria.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $cat = new Categoria();
    $cat->llenaDatos(-1, $_POST['nombre']);
    $cat->inserta();
}
//baja
else if ($accion == 'baja') {
    $cat = new Categoria();
    return $cat->baja($_POST['id']);
}
//editar
else if($accion == 'editar') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $cat = new Categoria();
    $cat->llenaDatos($_POST['id'], $_POST['nombre']);
    $cat->editar();
}