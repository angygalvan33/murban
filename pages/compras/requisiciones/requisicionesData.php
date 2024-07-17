<?php
include_once '../../../clases/conexion.php';
include_once '../../../clases/dataTable.php';

if (!empty($_POST)) {
    $tipo = $_POST['Tipo'];
    $prov = $_POST['IdProveedor'];
    $vista = '';
    $query = "SELECT * FROM %s ";
    
    if ($tipo == 0) {
        $vista = 'VistaRequisConcentrado WHERE 1 = 1 ';
    }
    else {
        $query = $query ."WHERE IdProveedor = ". $prov;
        $vista = 'VistaRequisConcentradoProv';
    }
    
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

    $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}