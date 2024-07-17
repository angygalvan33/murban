<?php
include_once '../../../clases/cajaChica.php';
include_once '../../../clases/proveedor.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'guardar') {
    $cc = new CajaChica();
    $_POST['FolioFactura'] = $_POST['Facturada'] == 0 ? "" : $_POST['FolioFactura'];
    $presupuestoDisponible = $cc->obtenerPresupuestoUsuarioCajaChica($_POST['IdUsuario']);

    if (($presupuestoDisponible - $_POST['Total']) >= 0) {
        $cc->insertaCajaChicaDetalle($_POST['IdUsuario'], $_POST['IdObra'], $_POST['IdMaterial'], $_POST['Material'], $_POST['IdProveedor'], $_POST['Proveedor'], $_POST['Facturada'], $_POST['FolioFactura'], $_POST['Total']);
    }
    else {
        $result['error'] = 1;
        $result['result'] = "LA SALIDA SOBREPASA EL PRESUPUESTO ACTUAL DE LA CAJA CHICA";
        echo json_encode($result);
    }
}
//obtiene el presupuesto de caja chica del usuario
else if ($accion == 'getPresupuestoDeUsuario') {
    if ($_POST["idUsuario"] != "-2") {
        $cc = new CajaChica();
        echo $cc->obtenerPresupuestoUsuarioCajaChica($_POST["idUsuario"]);
    }
}
else if ($accion == 'proveedorAutocomplete') {
    if (!isset($_POST['searchTerm']))
        $search = null;
    else
        $search = $_POST['searchTerm'];
    
    $proveedor = new Proveedor();
    $proveedor->getProveedorFilter($search);
}