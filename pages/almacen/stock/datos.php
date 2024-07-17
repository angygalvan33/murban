<?php
include_once "../../../clases/inventario.php";
include_once "../../../clases/obra.php";
include_once "../../../clases/requisicion.php";

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'asignarMaterial') {
        $obra = new Obra();
        $obra->sumarGastoObra($_POST['idObra'], $_POST['idMaterial'], $_POST['cantidad'], $_POST['nombreMaterial']);
        
        $inventario = new Inventario();
        $inventario->reasignarMaterialDeStock($_POST['idObra'], $_POST['idMaterial'], $_POST['cantidad'], $_POST['nombreMaterial']);
    }
    else if ($_POST['accion'] == 'reducirMaterial') {
        $obra = new Obra();
        $obra->restarGastoObra($_POST['idObra'], $_POST['idMaterial'], $_POST['cantidad'], $_POST['nombreMaterial']);
        
        $inventario = new Inventario();
        $inventario->reducirMaterial($_POST['idObra'], $_POST['idMaterial'], $_POST['cantidad'], $_POST['nombreMaterial'], $_POST['reponerRequisicion']);
        //echo "*******".$_POST['reponerRequisicion']."*******";
        $requisicion = new Requisicion();
        $requisicion->crearRequiReducida($_POST['idMaterial'], $_POST['idObra'], $_POST['cantidad'], $_POST['nombreMaterial'], $_POST['reponerRequisicion']);
    }
    else if ($_POST['accion'] == 'eliminarMaterial') {
        $inventario = new Inventario();
        $inventario->reducirMaterial($_POST['idObra'], $_POST['idMaterial'], $_POST['cantidad'], $_POST['nombreMaterial']);
    }
}