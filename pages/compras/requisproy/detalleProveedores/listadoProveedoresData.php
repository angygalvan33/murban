<?php
include_once '../../../../clases/conexion.php';
include_once '../../../../clases/dataTable.php';

if (!empty($_POST)) {
    $tipo = $_POST['Tipo'];
    $detallereq = $_POST['IdReqDetalle'];
    $piezas = $_POST['piezas'];
    $vista = '';
    $query = "SELECT * FROM %s WHERE IdRequisicionDetalle = %d ";

    if ($piezas == 0)
        $vista = 'VistaProveedoresxMaterial';
    else
        $vista = 'VistaProveedoresxMaterialxKilo';

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
    
    $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $_POST['IdReqDetalle']));
    echo json_encode($dataTable->registrosTabla());
} else {
    echo "NO POST Query from DataTable";
}