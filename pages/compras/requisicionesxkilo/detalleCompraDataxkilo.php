<?php
include_once '../../../clases/conexion.php';
include_once '../../../clases/dataTable.php';

if (!empty($_POST)) {
    $tipo = $_POST['TipoDetalle']; //1 Requisicio, 2 requisicion especial
    $query = "SELECT * FROM %s WHERE IdProveedor = %d";
    $orderByColumnIndex = $_POST['order'][0]['column'];
    $searchValue = $_POST['search']['value'];
    $columns = $_POST['columns'];
    
    $dataTable = new dataTable($_POST["draw"],
                               $orderByColumnIndex,
                               $columns[$orderByColumnIndex]['data'],
                               $_POST['order'][0]['dir'],
                               $_POST["start"],
                               $_POST['length'],
                               'VistaRequisicionSolicitarOCNxkilo',
                               $searchValue,
                               $columns);
    
    $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $_POST['IdProveedor']));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}