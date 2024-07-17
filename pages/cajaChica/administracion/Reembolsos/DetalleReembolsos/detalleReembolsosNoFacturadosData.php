<?php
include_once '../../../../../clases/conexion.php';
include_once '../../../../../clases/dataTable.php';

$idCajaChica = $_POST['IdCajaChica'];
$fecha = $_POST['Fecha'];

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
                               "VistaCajaChicaCortes",
                               $searchValue,
                               $columns);
    
    $dataTable->construyeConsulta(sprintf("SELECT * FROM %s WHERE IdCajaChica = %d AND FolioFactura IS NULL AND FechaRegistroCorte = '%s' AND Eliminado IS NULL", $dataTable->nombreTabla, $idCajaChica, $fecha));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}