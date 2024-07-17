<?php
include_once '../../../clases/ordenCompra.php';
include_once '../../../clases/proveedor.php';

$accion = $_POST['accion'];

if ($accion == 'pagar') {
    $cant = str_replace(',', '', $_POST['Cantidad']);
    $cantFact = str_replace(',', '', $_POST['CantidadFact']);

    $oc = new OrdenCompra();
    $oc->PagarOrdenCompra($_POST['IdOC'], $_POST['IdMetodoPago'], 'Abono', $cant, $cantFact, $_POST['Concepto'], $_POST['Deuda'], $_POST['Fecha']);
}
else if ($accion == 'proponer') {
    $oc = new OrdenCompra();
    $result = $oc->cambiarEstadoPropuesta($_POST['IdOrdenCompra'], $_POST['edo']);

    if ($result['error'] == 0) {
        $prov = new Proveedor();
        $prov->actualizaTotalProponer($_POST['idProveedor'], $_POST['edo'], $_POST['ValorFactura']);
    }
    else {
        echo json_encode($result);
    }
}
else if ($accion == 'autorizar') {
    $oc = new OrdenCompra();
    $result = $oc->cambiarEstadoAutorizada($_POST['IdOrdenCompra'], $_POST['edo']);

    if ($result['error'] == 0) {
        $prov = new Proveedor();
        $prov->actualizaTotalAutorizar($_POST['idProveedor'], $_POST['edo'], $_POST['ValorFactura']);
    }
    else {
        echo json_encode($result);
    }
}
else if ($accion == 'modalPagar') {
    $idOC = $_POST['IdOrdenCompra'];

    $oc = new OrdenCompra();
    $oc->listarPagosOC($idOC);
}
else if ($accion == 'obtenerDeudaTotal') {
    $oc = new OrdenCompra();
    $oc->getDeudaTotal();
}
else if ($accion == 'obtenerDeudaPropuesta') {
    $oc = new OrdenCompra();
    $oc->getDeudaPropuesta();
}
else if ($accion == 'obtenerDeudaAutorizada') {
    $oc = new OrdenCompra();
    $oc->getDeudaAutorizada();
}
else if ($accion == 'cancelarOC') {
    $oc = new OrdenCompra();
    $oc->cancelaCXP($_POST['IdOrdenCompra'], $_POST['Motivo']);
}