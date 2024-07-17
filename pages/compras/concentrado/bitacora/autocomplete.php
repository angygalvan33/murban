<?php
include_once '../../../../clases/material.php';
include_once '../../../../clases/proveedor.php';

$accion = $_POST['accion'];
if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if ($accion == 'materiales') {
    $material = new Material();
    $material->getMaterialesFilter($search);
}
else if ($accion == 'proveedores') {
    $proveedor = new Proveedor();
    $proveedor->getProveedorFilter($search);
}