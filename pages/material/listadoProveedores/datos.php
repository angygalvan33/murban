<?php
include_once '../../../clases/historicoPrecioMateriales.php';

$accion = $_POST['accion'];

if ($accion == 'nuevoProveedorByMaterial') {
    if ($_POST['moneda'] == 'P')
        $_POST['dolares'] = 0;
    
    $historico = new HistoricoPrecioMateriales();
    $historico->llenaDatos(-1, $_POST['idMaterial'], $_POST['idProveedor'], $_POST['precio'], $_POST['iva'], $_POST['cotizador'], $_POST['moneda'], $_POST['dolares']);
    $historico->guardarPrecio('primeraVezHistorico');
}
else if ($accion == 'editarPrecio') {
    if ($_POST['moneda'] == 'P')
        $_POST['dolares'] = 0;

    $historico = new HistoricoPrecioMateriales();
    $historico->llenaDatos(-1, $_POST['idMaterial'], $_POST['idProveedor'], $_POST['precio'], $_POST['iva'], $_POST['cotizador'], $_POST['moneda'], $_POST['dolares']);
    $historico->guardarPrecio('historico');
}