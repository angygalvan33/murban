<?php
include_once '../../../clases/proveedor.php';

if (!isset($_POST['searchTerm'])) {
    $proveedor = new Proveedor();
    echo $proveedor->getCotizadoresFilter(null, $_POST['IdMaterial']);
}
else {
    $search = $_POST['searchTerm'];
    $proveedor = new Proveedor();
    echo $proveedor->getCotizadoresFilter($search, $_POST['IdMaterial']);
}