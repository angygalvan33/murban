<?php
include_once '../../clases/material.php';
include_once '../../clases/personal.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'personal') {
        $obra = new Personal();
        echo $obra->getPersonalActivoFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'material') {
        $material = new Material();
        echo $material->getMaterialesStockFilter($search);
    }
}