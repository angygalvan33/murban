<?php
$data = array(
    array('Cantidad'=>3, 'Nombre'=>'Material 1', 'IdMaterial'=>1, 'Precio'=>145.50),
    array('Cantidad'=>1, 'Nombre'=>'Material 2', 'IdMaterial'=>2, 'Precio'=>59.90),
    array('Cantidad'=>4, 'Nombre'=>'Material 3', 'IdMaterial'=>3, 'Precio'=>1900),
    array('Cantidad'=>6, 'Nombre'=>'Material 4', 'IdMaterial'=>4, 'Precio'=>13.59),
    array('Cantidad'=>2, 'Nombre'=>'Material 5', 'IdMaterial'=>5, 'Precio'=>54.25),
);

$results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

echo json_encode($results);