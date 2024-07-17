<?php
include_once '../../../clases/material.php';
include_once '../../../clases/personal.php';
include_once '../../../clases/obra.php';
include_once '../../../clases/categoria.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    //cambiar por proyectos de requisiciones
    if ($_POST['nombreAutocomplete'] == 'obra') {
        $obra = new Obra();
        echo $obra->getObraFilter($search);
    }
}