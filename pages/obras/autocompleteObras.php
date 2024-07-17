<?php
include_once '../../clases/cliente.php';
include_once '../../clases/articulo.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'clientes') {
        $cliente = new cliente();
        echo $cliente->getClientesFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'productos') {
        $productos = new Articulo();
        echo $productos->getArticulosFilter($search);
    }
}