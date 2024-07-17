<?php
include_once '../../clases/prestamoResguardo.php';
include_once '../../clases/inventario.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'guardarMaterial') {
        $fecha = $_POST['fecha'];
        $fecha = str_replace('/', '-', $fecha);
        $fecha = date('Y-m-d', strtotime($fecha));
        
        if ($_POST['tipoPrestamo'] == 'R')
            $_POST['diasPrestamo'] = -1;
        
        $var_pyr = new PrestamoResguardo();
        $var_pyr->llenaDatos(-1, $fecha, $_POST['idMaterial'], $_POST['cantidad'], $_POST['diasPrestamo'], $_POST['descripcion'], $_POST['tipoPrestamo'], $_POST['idPersonal'], -1, $_POST['nombreMaterial']);
        $var_pyr->inserta();
    }
    else if ($_POST['accion'] == 'editarMaterial') { }
    else if ($_POST['accion'] == 'recibir') {
        $fecha = $_POST['fecha'];
        $fecha = str_replace('/', '-', $fecha);
        $fecha = date('Y-m-d', strtotime($fecha));

        $var_pyr = new PrestamoResguardo();
        $var_pyr->recibirMaterialPrestamo($_POST['idDetalle'], $_POST['cantidad']);
    }
    else if ($_POST['accion'] == 'refrendar') {
        $var_pyr = new PrestamoResguardo();
        $var_pyr->refrendar($_POST['idPrestamoResguardo'], $_POST['diasExtraPrestamo']);
    }
    else if($_POST['accion'] == 'getCantidadMaterial') {
        $inventario = new Inventario();
        $inventario->getCantidadMaterialStock($_POST['idMaterial'], $_POST['NombreMaterial']);
    }
}