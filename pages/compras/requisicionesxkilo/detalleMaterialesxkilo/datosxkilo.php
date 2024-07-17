<?php
include_once '../../../../clases/requisicion.php';

$accion = $_POST['accion'];
//cancelar requisición
if ($accion == 'cancelarRequisicion') {
    $IdRequisicionDetalle = $_POST['idRequisicionDetalle'];
    $MotivoCancelacion = $_POST['motivo'];
    $req = new Requisicion();
    return $req->cancelarRequisicionDetalle($IdRequisicionDetalle, $MotivoCancelacion);
}
else if ($accion == 'asignarStock') {
    $IdRequisicionDetalle = $_POST['idRequisicionDetalle'];
    $IdMaterial = $_POST['idMaterial'];
    $IdProyecto = $_POST['idProyecto'];
    $Cantidad = $_POST['cantidad'];
    $idProveedor =  $_POST['idProveedor'];
    $tipoRequisicion = $_POST['tipoRequisicion'];

    $req = new Requisicion();
    return $req->reasignarMaterialDeStock($IdRequisicionDetalle, $IdProyecto, $IdMaterial, $Cantidad, $tipoRequisicion);
}
else if ($accion == 'comprarOC') {
    $IdRequisicionDetalle = $_POST['idRequisicionDetalle'];
    $IdMaterial = $_POST['idMaterial'];
    $IdProyecto = $_POST['idProyecto'];
    $Cantidad = $_POST['cantidad'];
    $idProveedor =  $_POST['idProveedor'];
    $TipoRequisicion = $_POST['tipoRequisicion'];
    $FechaProv = $_POST['fechaProv'];
    
    $req = new Requisicion();
    return $req->comprarMaterial($IdRequisicionDetalle, $Cantidad, $IdMaterial, $idProveedor, $TipoRequisicion, $FechaProv);
}
else if ($accion == 'eliminarOCReq') {
    $IdRequisicionAtendida = $_POST['id'];
    $req = new Requisicion();
    return $req->eliminarRequisicionOC($IdRequisicionAtendida);
}