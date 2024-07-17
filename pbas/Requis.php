<?php
include_once '../clases/requisicion.php';

$requis = new Requisicion();
//$requis->comprarMaterial($idRequisicionDetalle, $cantidadPedida, $idMateral, $idProveedor)
//$requis->comprarMaterial(55, 1, 716, 20);

//$requis->comprarMaterial(61, 3, 479, 6);
//$requis->solicitarOC(3);
$requis->materialesPendientesRequisMaxMin();

