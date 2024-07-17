<?php
include_once '../../../../clases/proveedor.php';
include_once '../../../../clases/ordenCompra.php';

$accion = $_POST['accion'];

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if ($accion == 'proveedor') {
    $proveedor = new Proveedor();
    return $proveedor->getProveedorFilter($search);
}
else if ($accion == 'saldo') {
    $ordenCompra = new OrdenCompra();
    
    if ($_POST['fechaIni'] == '')
        $_POST['fechaIni'] = -1;

    echo $ordenCompra->getSaldoProveedor($_POST['fechaIni'], $_POST['fechaFin'], $_POST['idProveedor'], $_POST['idEstado']);
}