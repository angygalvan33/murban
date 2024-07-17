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
    if ($_POST['nombreAutocomplete'] == 'personal') {
        $obra = new Personal();
        echo $obra->getPersonalActivoFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'material') {
        $material = new Material();
        echo $material->getMaterialesFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'obra') {
        $obra = new Obra();
        echo $obra->getObraFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'categoria') {
        $categoria = new Categoria();
        echo $categoria->getCategoriaFilter($search);
    }
}