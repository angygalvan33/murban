<?php
include_once '../../../clases/historicoPrecioMateriales.php';

if (!isset($_POST['searchTerm'])) {
    $historico = new HistoricoPrecioMateriales();
    echo $historico->getpreciosFilterKg(null, $_POST['IdProveedor']);
}
else {
    $search = $_POST['searchTerm'];
    $historico = new HistoricoPrecioMateriales();
    echo $historico->getpreciosFilterKg($search, $_POST['IdProveedor']);
}