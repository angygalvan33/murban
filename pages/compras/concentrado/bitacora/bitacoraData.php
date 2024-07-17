<?php
include_once '../../../../clases/conexion.php';
include_once '../../../../clases/dataTable.php';

if (!empty($_POST)) {
    $fechaIni = $_POST['FechaIni'];
    $fechaFin = $_POST['FechaFin'];
    //echo $fechaIni ."****";
    $idProveedor = $_POST['idProveedor'];
    $idMaterial = $_POST['idMaterial'];
    $vista = '';
    $query = "SELECT * FROM %s ";
    $vista = 'VistaBitacoraMateriales WHERE 1 = 1';

    if ($idProveedor != 0)
        $query = $query ." AND IdProveedor = ". $idProveedor;

    if ($idMaterial != 0)
        $query = $query ." AND IdMaterial = ". $idMaterial;
    
    if ($fechaIni !== '-1')
        $query = $query ." AND (CAST(Fecha as Date) >= '%s' AND CAST(Fecha as Date) <= '%s')";

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
    
    $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $fechaIni, $fechaFin));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}