<?php
include_once '../../../clases/ubicacion.php';

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    
    if ($accion == 'alta') {
        $ubicacion = new Ubicacion();
        $ubicacion->llenaDatos(-1, $_POST['nombre'], $_POST['descripcion']);
        echo $ubicacion->inserta();
    }
    else if ($accion == 'editar') {
        $ubicacion = new Ubicacion();
        $ubicacion->llenaDatos($_POST['id'], $_POST['nombre'], $_POST['descripcion']);
        $ubicacion->editar();
    }
    else if ($accion == 'baja') {
        $ubicacion = new Ubicacion();
        $ubicacion->baja($_POST['id']);
    }
}