<?php
include_once '../../../clases/cajaChica.php';

$accion = $_POST['accion'];
//facturar
if ($accion == 'facturar') {
    $cajaChica = new CajaChica();
    $cajaChica->Facturar($_POST['id'], $_POST['numFact'], $_POST['fecha'] .' 00:00:00');
}