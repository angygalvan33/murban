<?php
include_once '../../../clases/historicoPrecioMateriales.php';
include_once '../../../clases/banxico.php';

$accion = $_POST['accion'];

if ($accion == 'baja') {
    $historico = new HistoricoPrecioMateriales();
    return $historico->bajaPrecioxKilo($_POST['idProveedor'], $_POST['idMaterial']);
}
else if ($accion == 'historico' || $accion == 'primeraVezHistorico') {
    if ($_POST['moneda'] == 'P')
        $_POST['dolares'] = 0;

    $historico = new HistoricoPrecioMateriales();
    echo json_encode($historico->addPrecioxKilo($_POST['precio'], $_POST['iva'], $_POST['cotizador'], $_POST['moneda'], $_POST['dolares'], $_POST['IdProveedor']));
}
else if ($accion == 'asignarmat') {
    $historico = new HistoricoPrecioMateriales();
    echo json_encode($historico->asignaPrecioxKilo($_POST['idProveedor'], $_POST['idMaterial'], $_POST['idPrecio']));
}
else if ($accion == 'getDolar') {
    $dolar = new Banxico();
    $precio = $dolar->getExRate();
    echo $precio;
}
else if ($_POST['accion'] == 'getPrecioById') {
    $historico = new HistoricoPrecioMateriales();
    $historico->getPrecio_ByIdPrecioProv($_POST['idPrecioxkilo'], $_POST['IdProveedor']);
}
else if ($accion == 'editarprecio') {
    $historico = new HistoricoPrecioMateriales();
    echo json_encode($historico->EditPrecioxKilo($_POST['idPrecio'], $_POST['idProveedor'], $_POST['iva'], $_POST['moneda'], $_POST['precioxkilo']));
}