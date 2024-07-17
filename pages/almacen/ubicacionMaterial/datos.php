<?php
include_once '../../../clases/material.php';
include_once '../../../clases/ubicacion.php';

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    if ($accion == 'autocompleteMateriales') {
        if (!isset($_POST['searchTerm']))
            $search = null;
        else
            $search = $_POST['searchTerm'];
        
        $material = new Material();
        echo $material->getMaterialesFilter($search);
    }
    else if ( $accion == 'autocompleteUbicaciones') {
        if (!isset($_POST['searchTerm']))
            $search = null;
        else
            $search = $_POST['searchTerm'];
        
        $ubicacion = new Ubicacion();
        echo $ubicacion->getUbicacionesFilter($search);
    }
    else if ($accion == 'alta') {
        $ubicacion = new Ubicacion();
        $ubicacion->insertaUbicacionMaterial($_POST['idUbicacion'], $_POST['idMaterial']);
    }
    else if ($accion == 'editar') {
        $ubicacion = new Ubicacion();
        $ubicacion->editaUbicacionMaterial($_POST['idUbicaciona'], $_POST['idUbicacionn'], $_POST['cantidadnva'], $_POST['idMaterialUbicEdit'], $_POST['nombreMaterial']);
    }
    else if ($accion == 'baja') {
        $ubicacion = new Ubicacion();
        $ubicacion->bajaUbicacionMaterial($_POST['id']);
    }
}