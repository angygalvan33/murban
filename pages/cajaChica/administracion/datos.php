<?php
include_once '../../../clases/cajaChica.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $cc = new CajaChica();
    $cc->llenaDatos(-1,  $_POST['usuario'], $_POST['presupuesto']);
    $cc->inserta();
}
//editar
else if ($accion == 'editar') { /*SIN USO POR EL MOMENTO*/
    //id  -> de la cajaChica
    //presupuesto
}
//reembolsar
else if ($accion == 'reembolsar') {
    $cc = new CajaChica();
    $cc->reembolsar($_POST['IdCajaChica'], $_POST['Total'], $_POST['Descripcion']);
}
//reembolsar
else if ($accion == 'cambiarEdoC') {
    //Falta indicar que todas las facturas no recondeadas se van a perder
    $cc = new CajaChica();
    $cc->cambiarEdoCajaChica($_POST['IdCajaChica'], $_POST['Edo']);
}