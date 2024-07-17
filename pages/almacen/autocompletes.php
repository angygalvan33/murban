<?php
include_once '../../clases/material.php';
include_once '../../clases/personal.php';
include_once '../../clases/obra.php';
include_once '../../clases/ubicacion.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'material') {
        $material = new Material();
        echo $material->getMaterialesFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'personal') {
        $pers = new Personal();
        $pers->getPersonalActivoFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'obra') {
        $ob = new Obra();
        $ob->getObraFilterSalida($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'ubicacion') {
        $ubicacion = new Ubicacion();
        echo $ubicacion->getUbicacionesFilter($search);
    }
}