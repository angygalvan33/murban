<?php
include_once '../../../clases/conexion.php';
include_once '../../../clases/dataTable.php';

if (!empty($_POST)) {
    $IdUsuario = $_POST['IdUsuario'];
    $orderByColumnIndex = $_POST['order'][0]['column'];
    $searchValue = $_POST['search']['value'];
    $columns = $_POST['columns'];
    
    $dataTable = new dataTable($_POST["draw"],
                               $orderByColumnIndex,
                               $columns[$orderByColumnIndex]['data'],
                               $_POST['order'][0]['dir'],
                               $_POST["start"],
                               $_POST['length'],
                               "VistaCajaChicaUsuario",
                               $searchValue,
                               $columns);
    
    $dataTable->construyeConsulta(sprintf("SELECT * FROM %s WHERE Pagada = 0 AND IdUsuario = %d AND Eliminado IS NULL", $dataTable->nombreTabla, $IdUsuario));
    echo json_encode($dataTable->registrosTabla());
} else {
    echo "NO POST Query from DataTable";
}