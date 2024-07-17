<?php
include_once '../../clases/departamento.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'departamentos') {
        $material = new Departamento();
        echo $material->getDepartamentosFilter($search);
    }
}