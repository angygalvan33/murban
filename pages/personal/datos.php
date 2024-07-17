<?php
include_once '../../clases/personal.php';
include_once '../../clases/nomina.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $fechaNac = empty($_POST['fechanac']) == TRUE ? NULL : $_POST['fechanac'];
    $periodo = $_POST['periodo'] ? $_POST['periodo'] : 0;
    $sueldo = $_POST['sueldo'] ? $_POST['sueldo'] : 0;

    $pers = new Personal();
    $pers->llenaDatos(-1, $_POST['nombre'], null, $fechaNac, $_POST['nss'], $_POST['telefono']);
    $idPersonal = $pers->inserta()["result"];

    $nomina = new Nomina();
    $nomina->llenaDatos(-1, $idPersonal, $_POST['fechaing'], $_POST['depto'], $_POST['puesto'], $periodo, $sueldo);
    echo $nomina->inserta();
}
//baja
else if ($accion == 'baja') {
    $nomina = new Nomina();
    $nomina->baja($_POST['id']);

    $pers = new Personal();
    return $pers->baja($_POST['id']);
}
//editar
else if ($accion == 'editar') {
    $fechaNac = empty($_POST['fechanac']) == TRUE ? NULL : $_POST['fechanac'];
    $periodo = $_POST['periodo'] ? $_POST['periodo'] : 0;
    $sueldo = $_POST['sueldo'] ? $_POST['sueldo'] : 0;

    $pers = new Personal();
    $pers->llenaDatos($_POST['id'], $_POST['nombre'], null, $fechaNac, $_POST['nss'], $_POST['telefono']);
    $pers->editar();

    $nomina = new Nomina();
    $nomina->llenaDatos(-1, $_POST['id'], $_POST['fechaing'], $_POST['depto'], $_POST['puesto'], $periodo, $sueldo);
    echo $nomina->editar();
}
else if ($accion == 'desactivar') {
    $personal = new Personal();
    echo $personal->desactivaPersonal($_POST['IdPersonal'], $_POST['Estatus']);
}