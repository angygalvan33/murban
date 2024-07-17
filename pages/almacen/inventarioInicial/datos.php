<?php
include_once "../../../clases/inventario.php";

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'agregarAInventario') {
        $inv = new Inventario();
        $inv->llenaDatos(-1, -1, -1, -1, $_POST['idMaterial'], "", $_POST['cantidad'], $_POST['precioUnitario']);
        $inv->inserta();
    }
    else if ($_POST['accion'] == 'editarMaterial') {
        $inv = new Inventario();
        $inv->llenaDatos(-1, -1, -1, -1, $_POST['idMaterial'], "", $_POST['cantidad'], $_POST['precioUnitario']);
        $inv->editarInicial();
    }
    else if ($_POST['accion'] == 'eliminarMaterial') {
        $inv = new Inventario();
        $inv->bajaInicial($_POST['idMaterial']);
    }
    else if ($_POST['accion'] == 'getPrecioMaterial') {
        $inv = new Inventario();
        $inv->getPrecioMaterialInvInicial($_POST['idMaterial']);
    }
}