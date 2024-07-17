<?php
include_once '../../../../clases/conexion.php';
include_once '../../../../clases/dataTable.php';

if (!empty($_POST)) {
    $cat = $_POST['IdCategoria'];
    $tipo = $_POST['tipo'];
    $fechaIni = $_POST['fechaIni'];
    $fechaFin = $_POST['fechaFin'];
    $vista = '';
    $query = "SELECT * FROM %s ";
    
    if ($tipo == 0) {
        $vista = 'VistaGastosxCategoria WHERE 1 = 1 ';
    }
    else {
        $query = $query ."WHERE IdCategoria = ". $cat;
        $vista = 'VistaGastosxCategoria';
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
    
    if ($fechaIni !== "")
        $query = $query ." AND ( CAST(Fecha as Date) >= '%s' AND CAST(Fecha as Date) <= '%s')";
    
    $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $fechaIni, $fechaFin));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}