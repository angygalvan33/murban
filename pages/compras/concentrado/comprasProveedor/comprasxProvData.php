<?php
include_once '../../../../clases/conexion.php';
include_once '../../../../clases/dataTable.php';

if (!empty($_POST)) {
    $orderByColumnIndex = $_POST['order'][0]['column'];
    $searchValue = $_POST['search']['value'];
    $columns = $_POST['columns'];
    $idEstado = $_POST['idEstado'];
    $fechaIni = $_POST['fechaIni'];
    $fechaFin = $_POST['fechaFin'];
    $idProveedor = $_POST['idProveedor'];
    $query = "";

    if ($fechaIni !== "")
        $query = $query ." AND ( CAST(Creado as Date) >= '". $fechaIni ."' AND CAST(Creado as Date) <= '". $fechaFin ."')";
    if ($idProveedor != 0)
        $query = $query ." AND IdProveedor = ". $idProveedor;
    if ($idEstado == 1)
        $query = $query ." AND IdEstadoOC != 5";
    else if ($idEstado == 5)
        $query = $query ." AND IdEstadoOC = ". $idEstado;

    $dataTable = new dataTable($_POST["draw"],
                               $orderByColumnIndex,
                               $columns[$orderByColumnIndex]['data'],
                               $_POST['order'][0]['dir'],
                               $_POST["start"],
                               $_POST['length'],
                               "VistaHistoricoPagosOc",
                               $searchValue,
                               $columns);
    
    $dataTable->construyeConsulta(sprintf("SELECT * FROM %s WHERE 1 = 1 %s", $dataTable->nombreTabla, $query));
    echo json_encode($dataTable->registrosTabla());
} else {
    echo "NO POST Query from DataTable";
}