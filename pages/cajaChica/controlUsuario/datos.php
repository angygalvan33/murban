<?php
include_once '../../../clases/cajaChica.php';
$accion = $_POST['accion'];
//obtiene cantidad gastada de caja chica del usuario
if ($accion == 'getGastadoDeUsuario') {
    if ($_POST["idUsuario"] != "-2") {
        $cc = new CajaChica();
        echo $cc->obtenerPresupuestoUsuarioCajaChica($_POST["idUsuario"]); //cambiar por metodo que regrese cantidad gastada
    }
}