<?php
include_once '../../clases/conexion.php';
include_once '../../clases/dataTable.php';

if (!empty($_POST)) {
    $fechaIni = $_POST['fechaIni'];
    $fechaFin = $_POST['fechaFin'];
    $orderByColumnIndex = $_POST['order'][0]['column'];
    $searchValue = $_POST['search']['value'];
    $columns = $_POST['columns'];
    
    $dataTable = new dataTable($_POST["draw"],
                               $orderByColumnIndex,
                               $columns[$orderByColumnIndex]['data'],
                               $_POST['order'][0]['dir'],
                               $_POST["start"],
                               $_POST['length'],
                               "VistaObraTipoObra",
                               $searchValue,
                               $columns);
    
    if ($searchValue === '')
        $query = " AND Creado BETWEEN '$fechaIni' AND '$fechaFin'";
    else
        $query = "";

    $dataTable->construyeConsulta(sprintf("SELECT * FROM %s WHERE Eliminado is null AND IdObra > 0 ". $query, $dataTable->nombreTabla));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}