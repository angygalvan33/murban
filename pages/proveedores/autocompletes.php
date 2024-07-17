<?php
include_once '../../clases/proveedor.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'proveedor') {
        $material = new Proveedor();
        echo $material->getProveedorFilter($search);
    }
}