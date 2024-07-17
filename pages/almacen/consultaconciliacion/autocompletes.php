<?php
include_once "../../../clases/inventario.php";

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'eventos') {
        $eventos = new Inventario();
        echo $eventos->getEventosFilter($search);
    }
}