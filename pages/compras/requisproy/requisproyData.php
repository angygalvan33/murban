<?php
include_once '../../../clases/conexion.php';
include_once '../../../clases/dataTable.php';

if (!empty($_POST)) {
    $proy = $_POST['IdProyecto'];
    $tipo = $_POST['tipo'];
    $piezas = $_POST['piezas'];
    $vista = '';
    $query = "SELECT * FROM %s ";
    
    if ($piezas == 0) {
        if ($proy == -2)
            $vista = 'VistaRequisicionesxProyecto WHERE 1 = 1 ';
        else {
            $query = $query ."WHERE IdProyecto = ". $proy;
            $vista = 'VistaRequisicionesxProyecto';
        }
    }
    else {
        if ($proy == -2)
            $vista = 'VistaRequisicionesxProyectoxKilo WHERE 1 = 1 ';
        else {
            $query = $query ."WHERE IdProyecto = ". $proy;
            $vista = 'VistaRequisicionesxProyectoxKilo';
        }
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
} else {
    echo "NO POST Query from DataTable";
}