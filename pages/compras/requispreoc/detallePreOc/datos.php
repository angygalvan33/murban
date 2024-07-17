<?php
include_once '../../../../clases/requisicion.php';

$accion = $_POST['accion'];

if ($accion == 'comprarOC') {
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
else if ($accion == 'regresarRequisicion') {
    $IdRequisicionDetalle = $_POST['idRequisicionDetalle'];
    $req = new Requisicion();
    return $req->regresarDetalleRequisicion($IdRequisicionDetalle);
}
else if($accion == 'eliminarOCReq') {
    $IdRequisicionAtendida = $_POST['id'];
    $req = new Requisicion();
    return $req->eliminarRequisicionOC($IdRequisicionAtendida);
}
else if ($accion == 'regresarOCReq') {
    $IdRequisicionDetalle = $_POST['IdReqDetalle'];
    $req = new Requisicion();
    return $req->regresarDetalleRequisicion($IdRequisicionDetalle);
}
else if ($accion == 'seleccionarMaterial') {
    $IdRequisicionAtendida = $_POST['IdRequisicionAtendida'];
    $valor = $_POST['valor'];
    $req = new Requisicion();
    return $req->cambiarSeleccionada($IdRequisicionAtendida, $valor);
}