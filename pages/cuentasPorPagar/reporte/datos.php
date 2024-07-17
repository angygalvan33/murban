<?php
include_once '../../../clases/metodoPago.php';
include_once '../../../clases/metodoCobro.php';

$accion = $_POST['accion'];

if ($accion == 'getMetodosPago') {
    $metodoPago = new MetodoPago();
    $metodoPago->listadoMetodoPago();
}
else if ($accion == 'getMetodosCobro') {
    $metodoCobro = new MetodoCobro();
    $metodoCobro->listadoMetodoCobro();
}