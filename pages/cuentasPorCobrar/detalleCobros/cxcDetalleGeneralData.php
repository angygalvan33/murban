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
                               "VistaCuentasCobrarDetallePagosGeneral",
                               $searchValue,
                               $columns);
    
    $fechaIni = $_POST['FechaIni'];
    $fechaFin = $_POST['FechaFin'];
    $query = "";
    
    if ($fechaIni==="-1") { //ver todas  las compras
        $query = "SELECT * FROM %s ";
        $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla));
    }
    else {
        $query = "SELECT * FROM %s WHERE (FechaCobro BETWEEN '%s' AND '%s')";
        $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $fechaIni, $fechaFin));
    }
    
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}