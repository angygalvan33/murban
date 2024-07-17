<?php
include_once '../../../../clases/articulo.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'nuevoMaterial') {
        $art = new Articulo();
        $art->agregarMaterialArticulo($_POST['IdArticulo'], $_POST['IdMaterial'], $_POST['Cantidad']);
    }
    else if ($_POST['accion'] == 'editarMaterial') {
        $art = new Articulo();
        $art->editarMaterialArticulo($_POST['IdArticuloDetalle'], $_POST['Cantidad']);
    }
    else if ($_POST['accion'] == 'eliminarMaterial') {
        $art = new Articulo();
        $art->eliminarMaterialArticulo($_POST['IdArticuloDetalle']);
    }
}