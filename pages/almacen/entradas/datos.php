<?php
include_once "../../../clases/inventario.php";

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'recibirMaterial') {
        $inv = new Inventario();
        $inv->llenaDatos(-1, $_POST["idOC"], $_POST["idProveedor"], $_POST["idObra"], $_POST['idMaterial'], $_POST["nombreMaterial"], $_POST['cantidad'], $_POST['precioUnitario']);
        $inv->insertaEntrada();
        $inv->incrementaRecibido($_POST["idDetalleOC"], $_POST['cantidad']);
    }
}