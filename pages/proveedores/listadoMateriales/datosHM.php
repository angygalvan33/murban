<?php
include_once '../../../clases/historicoPrecioMateriales.php';

$historico = new HistoricoPrecioMateriales();
return $historico->getHistoricoMateriales($_POST['IdProveedor'], $_POST['IdMaterial']);