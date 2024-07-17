<?php
include_once '../../../../clases/categoria.php';

$accion = $_POST['accion'];

if ($accion == 'autocompleteCategorias') {
    if (!isset($_POST['searchTerm']))
        $search = null;
    else
        $search = $_POST['searchTerm'];
    
    $categoria = new Categoria();
    $categoria->getCategoriaFilter($search);
}