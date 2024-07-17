<?php
include_once '../../../clases/ordenCompra.php';

$accion = $_POST['accion'];
//cambio de recibido
if ($accion == 'recibirOC') {
    $idOC = $_POST['idOC'];
    $idDetalle = $_POST['idDetalleOC'];
    $recibido = $_POST['recibido'];
    
    $oc = new OrdenCompra();
    return $oc->cambiaRecibido($idOC, $idDetalle, $recibido);
}
else if ($accion == 'validarOrdenCompleta') {
    $idOC = $_POST['idOC'];

    $oc = new OrdenCompra();
    return $oc->comprobarCompleta($idOC);
}
else if ($accion == 'cambiarEstadoOC') {
    $idOC = $_POST['idOC'];
    $edo = $_POST['edo'];
    
    $oc = new OrdenCompra();
    return $oc->cambiarEstadoOC($idOC, $edo);
}
else if ($accion == 'descargarArchivo') {
    $idDetalleOC = $_POST['idDetalleOC'];
    
    include 'descargarArchivo.php';
    descargar($idDetalleOC);
}