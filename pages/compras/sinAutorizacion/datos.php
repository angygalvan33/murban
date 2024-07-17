<?php
include_once '../../../clases/ordenCompra.php';

$accion = $_POST['accion'];

if ($accion == 'cancelar') {
    //Para regresar las requis cuando se cancela una OC
    //$requi = new Requisicion();
    //$requi->resetRequisicion($_POST['id']);

    $oC = new OrdenCompra();
    return $oC->cambiarEstadoOC($_POST['id'], 5);
}
else if ($accion == 'autorizar') {
    //tipo autorizar = 0, autorizar y pagar = 1
    $oC = new OrdenCompra();
    $oC->setIdUsuarioAutoriza($_POST['id'], $_POST['idUsuarioAutoriza'], $_POST['tipo']);
    return $oC->cambiarEstadoOC($_POST['id'], 2);
}