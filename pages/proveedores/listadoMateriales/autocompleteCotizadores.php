<?php
include_once '../../../clases/material.php';

if (!isset($_POST['searchTerm'])) {
    $material = new Material();
    echo $material->getCotizadoresFilter(null, $_POST['IdProveedor']);
}
else {
    $search = $_POST['searchTerm'];
    $material = new Material();
    echo $material->getCotizadoresFilter($search, $_POST['IdProveedor']);
}