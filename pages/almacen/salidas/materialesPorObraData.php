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
                               "Inventario",
                               $searchValue,
                               $columns);
    
    $idMaterial = $_POST["idMaterial"];
    
    if ($idMaterial == -1) {
        $query = "SELECT Obra.Nombre AS NombreObra, Obra.IdObra, Inventario.Nombre, Inventario.PrecioUnitario, SUM(Cantidad) AS Cantidad FROM ". $dataTable->nombreTabla ." INNER JOIN Obra ON Inventario.IdObra = Obra.IdObra WHERE Cantidad > 0 AND IdMaterial = -1 AND Inventario.Nombre = '". $_POST['nombreMaterial'] ."' AND Inventario.PrecioUnitario = ". $_POST['precioUnitario'] ." AND Inventario.Eliminado IS NULL GROUP BY Inventario.IdObra, Inventario.Nombre, Inventario.PrecioUnitario";
    }
    else {
        $query = "SELECT Obra.Nombre AS NombreObra, Obra.IdObra, Inventario.Nombre, Inventario.PrecioUnitario, SUM(Cantidad) AS Cantidad FROM ". $dataTable->nombreTabla ." INNER JOIN Obra ON Inventario.IdObra = Obra.IdObra WHERE Cantidad > 0 AND IdMaterial = ". $idMaterial ." AND Inventario.Eliminado IS NULL GROUP BY Inventario.IdObra, IdMaterial";
    }
    
    $dataTable->construyeConsulta($query);
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}