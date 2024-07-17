<?php
include_once '../../../clases/material.php';
include_once '../../../clases/linea.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'linea') {
        $lin = new Linea();
        $lin->getLineasFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'material') {
        $material = new Material();
        echo $material->getMaterialesFilter($search);
    }
}