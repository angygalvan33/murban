<?php
include_once '../../../../clases/cajaChica.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'pagarFacturadas') {
    $cc = new CajaChica();
    $foliosFacturas = "";
    $bandComa = FALSE;
    
    for ($i = 0; $i < count($_POST['folios']); $i++) {
        if (!$bandComa) {
            $foliosFacturas .= $_POST['folios'][$i];
            $bandComa = TRUE;
        }
        else {
            $foliosFacturas .= ",". $_POST['folios'][$i];
        }
    }

    $totalFacturas = $cc->obtenerTotalFacturasCajaChica($foliosFacturas);
    $idCajaChica = $cc->obtenerIdCajaChica($_POST['IdCajaChica']);
    $cc->pagarFacturasCajaChica($_POST['IdCajaChica'], $foliosFacturas, $totalFacturas, $idCajaChica);
}