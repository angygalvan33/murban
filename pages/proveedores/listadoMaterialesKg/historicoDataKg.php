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
                               'DESC',
                               $_POST["start"],
                               $_POST['length'],
                               "VistaPrecioxKilo",
                               $searchValue,
                               $columns);
    
    $dataTable->construyeConsulta(sprintf("SELECT Creado, Precio, Iva, Cotizador FROM %s WHERE IdMaterial = %d and IdProveedor = %d and Eliminado IS NULL", $dataTable->nombreTabla, $_POST['IdMaterial'], $_POST['IdProveedor']));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}