<?php
include_once '../../../clases/material.php';
include_once '../../../clases/inventarioMaxMin.php';
include_once '../../../clases/requisicion.php';

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    if ($accion == 'autocompleteMateriales') {
        if (!isset($_POST['searchTerm']))
            $search = null;
        else
            $search = $_POST['searchTerm'];
        
        $material = new Material();
        echo $material->getMaterialesFilter($search);
    }
    else if ($accion == 'alta') {
        $maxmin = new InventarioMaxMin();
        $maxmin->llenaDatos(-1, $_POST['idMaterial'], $_POST['max'], $_POST['min'], $_POST['alerta']);
        $maxmin->inserta();
        $requis = new Requisicion();
        $requis->materialesPendientesRequisMaxMin();
    }
    else if ($accion == 'editar') {
        $maxmin = new InventarioMaxMin();
        $maxmin->llenaDatos($_POST['idRegistro'], $_POST['idMaterial'], $_POST['max'], $_POST['min'], $_POST['alerta']);
        $maxmin->editar();
        $requis = new Requisicion();
        $requis->materialesPendientesRequisMaxMin();
    }
    else if($accion == 'baja') {
        //id -> el del material
        $maxmin = new InventarioMaxMin();
        $maxmin->baja($_POST['id']);
    }
}