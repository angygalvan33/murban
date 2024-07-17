<?php
include_once '../../clases/obra.php';
include_once '../../clases/metodoCobro.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'facturar') {
        $fecha = $_POST['fecha'];
        $fecha = str_replace('/', '-', $fecha);
        $fecha = date('Y-m-d', strtotime($fecha));

        $obra = new Obra();
        $obra->facturar($_POST['id'], $_POST['numFact'], $_POST['valorFact'], $fecha);
    }
    else if ($_POST['accion'] == 'pagarModal') {
       $idObra = $_POST['IdObra'];

       $obra = new Obra();
       $obra->listarPagosObra($idObra);
    }
    else if ($_POST['accion'] == 'cobrar') {
        $cant = str_replace(',', '', $_POST['Cantidad']);
        $fecha = $_POST['Fecha'];
        $fecha = str_replace('/', '-', $fecha);
        $fecha = date('Y-m-d', strtotime($fecha));

        $obra = new Obra();
        $obra->PagarObra($_POST['IdOObra'], $_POST['IdMetodoPago'], 'Abono', $cant, $_POST['Concepto'], $_POST['Deuda'], $fecha);
    }
    else if ($_POST['accion'] == 'totalCobrarSinFactura') {
       $obra = new Obra();
       $obra->getTotalCobrarSinFactura();
    }
    else if ($_POST['accion'] == 'totalCobrarConFactura') {
       $obra = new Obra();
       $obra->getTotalCobrarConFactura();
    }
    else if ($_POST['accion'] == 'totalCobrarConSinFactura') {
       $obra = new Obra();
       $obra->getTotalCobrarConSinFactura();
    }
    else if ($_POST['accion'] == 'getMetodosCobro') {
        $metodoCobro = new MetodoCobro();
        echo $metodoCobro->listadoMetodoCobro();
    }
}