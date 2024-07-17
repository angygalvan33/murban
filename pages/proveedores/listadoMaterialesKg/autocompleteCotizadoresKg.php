<?php
include_once '../../../clases/historicoPrecioMateriales.php';

if (!isset($_POST['searchTerm'])) {
    $historico = new HistoricoPrecioMateriales();
    echo $historico->getCotizadoresFilterKg(null, $_POST['IdProveedor']);
}
else {
    $search = $_POST['searchTerm'];
    $historico = new HistoricoPrecioMateriales();
    echo $historico->getCotizadoresFilterKg($search,$_POST['IdProveedor']);
}