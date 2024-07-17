<?php
include_once '../../../clases/historicoPrecioMateriales.php';

$historico = new HistoricoPrecioMateriales();
return $historico->getPrecioxKiloMateriales($_POST['IdProveedor'], $_POST['IdMaterial']);