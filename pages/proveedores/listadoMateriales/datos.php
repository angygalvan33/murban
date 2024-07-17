<?php
include_once '../../../clases/historicoPrecioMateriales.php';
include_once '../../../clases/banxico.php';

$accion = $_POST['accion'];

if ($accion == 'baja') {
    $historico = new HistoricoPrecioMateriales();
    return $historico->bajaMaterialByProveedor($_POST['idProveedor'], $_POST['idMaterial']);
}
else if ($accion == 'historico' || $accion == 'primeraVezHistorico') {
    if ($_POST['moneda'] == 'P')
        $_POST['dolares'] = 0;
    //echo $_POST['precio']."******";
    $historico = new HistoricoPrecioMateriales();
    $historico->llenaDatos(-1, $_POST['idMaterial'], $_POST['idProveedor'], $_POST['precio'], $_POST['iva'], $_POST['cotizador'], $_POST['moneda'], $_POST['dolares']);
    $historico->guardarPrecio($accion);
}
else if ($accion == 'getDolar') {
    $dolar = new Banxico();
    $precio =  $dolar->getExRate();
    echo $precio;
}