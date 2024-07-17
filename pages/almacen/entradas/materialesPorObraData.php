<?php
include_once '../../../clases/conexion.php';
include_once '../../../clases/dataTable.php';

if (!empty($_POST)) {
    $orderByColumnIndex = $_POST['order'][0]['column'];
    $searchValue = $_POST['search']['value'];
    $columns = $_POST['columns'];
    
    $dataTable = new dataTable($_POST["draw"],
                               $orderByColumnIndex,
                               $columns[$orderByColumnIndex]['data'],
                               $_POST['order'][0]['dir'],
                               $_POST["start"],
                               $_POST['length'],
                               "VistaDetalleMaterial",
                               $searchValue,
                               $columns);
    
    $query = "";
    
    if ($_POST['idMaterial'] != -1) {
        $query = "SELECT * FROM %s WHERE IdOrdenCompra = %d AND IdMaterial = %d";
        $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $_POST['idOC'], $_POST['idMaterial']));
    }
    else {
        $query = "SELECT * FROM %s WHERE IdOrdenCompra = %d AND Nombre = '%s' AND PrecioUnitario = %d";
        $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $_POST['idOC'], $_POST['nombreMaterial'], $_POST['precioUnitario']));
    }
    
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}