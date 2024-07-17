<?php
include_once "../../../clases/inventario.php";

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'salidaDeMaterial') {
        $inv = new Inventario();
        $inv->salidaMaterial($_POST['idObra'], $_POST['idMaterial'], $_POST['nombreMaterial'], $_POST['precioUnitario'], $_POST['cantidad'], $_POST['idPersonal'], $_POST['descripcion'], $_POST['idObraSalida']);
        $inv->requisicionMaterialInventario(-1, $_POST['idMaterial'], $_POST['idPersonal']);
    }
}