<?php
include_once "../../../clases/inventario.php";

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'getCantidadMaterial') {
        $inv = new Inventario();
        $inv->getCantidadMaterial($_POST['idMaterial'], $_POST['idProyecto']);
    }
    else if ($_POST['accion'] == 'agregarAjuste') {
        $ajuste = $_POST["conteo"] - $_POST["cantidad"];
        $inventario = new Inventario();
        $idInvMov = $inventario->ajustarMaterialProyecto($_POST['idProyecto'], $_POST['idMaterial'], $ajuste, $_POST["conteo"], $_POST['nota']);
        echo $inventario->registrarAjuste($idInvMov, $_POST['idProyecto'], $_POST['idMaterial'], $_POST['cantidad'], $ajuste, $_POST["conteo"], $_POST['nota']);
    }
    else if ($_POST['accion'] == 'registrarEvento') {
        $inventario = new Inventario();
        echo $inventario->registrarEvento($_POST['evento']);
    }
    else if ($_POST['accion'] == 'acancelar') {
        $inventario = new Inventario();
        echo $inventario->cancelarAjuste($_POST['id']);
    }
}