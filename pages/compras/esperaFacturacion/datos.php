<?php
include_once '../../../clases/ordenCompra.php';

$accion = $_POST['accion'];

if ($accion == 'facturar') {
    $oC = new OrdenCompra();
    $oC->setNumeroFactura($_POST['id'], $_POST['numFact'], $_POST['valorFact']);
    $oC->insertaFechaFacturaOC($_POST['id'], $_POST['fecha'] .' 00:00:00');
    
    if ($_POST['tipoFactura'] == "1")
        $oC->cambiarEstadoOC($_POST['id'], 4);
    else {
        $result = array();
        $result['error'] = 0;
        $result['result'] = "Completo";
        echo json_encode($result);
    }
}