<?php
include_once '../../../../clases/conexion.php';
include_once '../../../../clases/dataTable.php';

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
                               "VistaRequisicionesConsulta",
                               $searchValue,
                               $columns);
    
    $status = $_POST['Status'];
    $wheres = "";
    
    if ($status === "0") {
        $wheres = " Estado IN ('PENDIENTE', 'PARCIALMENTE ATENDIDA', 'ATENDIDA', 'PARCIALMENTE CANCELADA', 'CANCELADA')";
    }
    else {
        $wheres = " Estado = '". $status ."'";
    }

    if ($_POST['IdProyecto'] !== "-2") {
        $dataTable->construyeConsulta(sprintf("SELECT * FROM %s WHERE IdObra = %d AND ". $wheres, $dataTable->nombreTabla, $_POST['IdProyecto']));
    }
    else {
        $dataTable->construyeConsulta(sprintf("SELECT * FROM %s WHERE ". $wheres, $dataTable->nombreTabla));
    }
    
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}