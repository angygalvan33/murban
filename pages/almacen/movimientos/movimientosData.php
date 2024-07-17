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
                               "VistaInventarioMovimientos",
                               $searchValue,
                               $columns);
    //    Filtros
    $idUsuario = $_POST['IdPersonal']; //-1 si  no se seleccionó nada
    $idProyecto = $_POST['IdProyecto']; //-2 si  no se seleccionó nada
    $idMaterial = $_POST['IdMaterial']; //-1 si  no se seleccionó nada
    $idCategoria = $_POST['IdCategoria']; //-1 si  no se seleccionó nada
    $fechaIni = $_POST['FechaIni'];
    $fechaFin = $_POST['FechaFin'];
    $tipoMovimiento = $_POST['TipoMovimiento']; //0->Todos, 1->Entradas, 2->Salidas
    $query = "";
    $queryUsuario = " AND IdPersonal = %d";
    $wheres = "";
    
    if ($tipoMovimiento == 0) { //Todos
        $wheres = " TipoMovimiento IN('ENTRADA', 'SALIDA', 'TRASPASO', 'PRESTAMO-RESGUARDO-ENTRADA', 'PRESTAMO-RESGUARDO-SALIDA', 'AJUSTE') AND ";
    }
    else if ($tipoMovimiento == 1) { //entradas
        $wheres = " TipoMovimiento = 'ENTRADA' AND ";
    }
    else if ($tipoMovimiento == 2) { //salidas
        $wheres = " TipoMovimiento = 'SALIDA' AND ";
    }
    else if ($tipoMovimiento == 3) { //traspaso
        $wheres = " TipoMovimiento = 'TRASPASO' AND ";
    }
    else if ($tipoMovimiento == 4) { //ajuste
        $wheres = " TipoMovimiento = 'AJUSTE' AND ";
    }
    //-----------------------------------------------
    if ($idUsuario != "-1") //usuario en particular
        $wheres = $wheres ."IdPersonal = ". $idUsuario ." AND ";

    if ($idProyecto != "-2") //obra en particular
        $wheres = $wheres ."IdObra = ". $idProyecto ." AND ";
    
    if ($idMaterial != "-1") //material en particular
        $wheres = $wheres ."IdMaterial = ". $idMaterial ." AND ";
    
    if ($idCategoria != "-1") //categoria en particular
        $wheres = $wheres ."IdCategoria = ". $idCategoria ." AND ";
    //-----------------------------------------------
    if ($fechaIni === "-1") //ver todas  las compras
        $query = "SELECT * FROM %s WHERE". $wheres ." Eliminado IS NULL";
    else
        $query = "SELECT * FROM %s WHERE". $wheres ." Eliminado IS NULL AND (CAST(Fecha as Date) >= '%s' AND CAST(Fecha as Date) <= '%s')";
    
    $dataTable->construyeConsulta(sprintf($query, $dataTable->nombreTabla, $fechaIni, $fechaFin));
    echo json_encode($dataTable->registrosTabla());
}
else {
    echo "NO POST Query from DataTable";
}