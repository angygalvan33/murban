<?php
include_once '../../../../clases/conexion.php';
include_once '../../../../clases/dataTable.php';

if (!empty($_POST)) {
    $fechaIni = $_POST['fechaIni'];
    $fechaFin = $_POST['fechaFin'];
    $vista = '';
    $query = "SELECT * FROM %s ";
    $vista = 'VistaRequisicionesxFecha WHERE 1 = 1 ';
    
    $orderByColumnIndex = $_POST['order'][0]['column'];
    $searchValue = $_POST['search']['value'];
    $columns = $_POST['columns'];
    
    $dataTable = new dataTable($_POST["draw"],
                               $orderByColumnIndex,
                               $columns[$orderByColumnIndex]['data'],
                               $_POST['order'][0]['dir'],
                               $_POST["start"],
                               $_POST['length'],
                               $vista,
                               $searchValue,
                               $columns);
    
    if ($fechaIni !== "")
        $query = $query ." AND (CAST(Fecha as Date) >= '%s' AND CAST(Fecha as Date) <= '%s')";
    
    $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $fechaIni, $fechaFin));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}