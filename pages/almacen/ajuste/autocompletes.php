<?php
include_once '../../../clases/material.php';
include_once "../../../clases/inventario.php";

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'material') {
        $material = new Material();
        echo $material->getMaterialesProyectosFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'proyectos') {
        $materialprov = new Inventario();
        echo $materialprov->getProyectosMaterial($_POST['idMaterial'], $search);
    }
}