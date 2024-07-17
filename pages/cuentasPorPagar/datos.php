<?php
include_once '../../clases/obra.php';
include_once '../../clases/material.php';
include_once '../../clases/metodoPago.php';
include_once '../../clases/ordenCompra.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'guardarCxPEspecial') {
        $ordenCompra = [];
        $ordenCompra["IdProveedor"] = $_POST['IdProveedor'];
        $ordenCompra["IdUsuario"] = $_POST['IdUsuario'];
        $ordenCompra["ValorFactura"] = $_POST['totalFactura'];
        $ordenCompra["NumeroFactura"] = $_POST['folioFactura'];
        $ordenCompra["FechaFactura"] = date("Y-m-d", strtotime($_POST['fechaFactura'])) ." 00:00:00";
        $ordenCompra["Descripcion"] = $_POST['Descripcion'];
        $ordenCompra["NotasProveedor"] = $_POST['NotasProveedor'];
        $ordenCompra["IdMetodoPago"] = $_POST['IdMetodoPago'];
        $ordenCompra["numCotizacion"] = $_POST['numCotizacion'];
        $ordenCompra["Subtotal"] = $_POST['Subtotal'];
        $ordenCompra["IVA"] = $_POST['IVA'];
        $ordenCompra["Total"] = $_POST['Total'];
        
        //Detalle de orden de compra
        $detalleCompra = [];
        foreach($_POST as $key => $value) {
            $aux = explode(":_", $key);
            //encontro algo similar 1:_
            if (count($aux) > 1)
                $detalleCompra[$aux[0]][$aux[1]] = $value;
        }

        if (!empty ($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $aux = explode(":_", $key);
                //encontro algo similar 1:_
                if (count($aux) > 1)
                    $detalleCompra[$aux[0]][$aux[1]] = $value;
            }
        }
        
        $ordenCompra["DetalleOC"] = $detalleCompra;
        $oc = new OrdenCompra();
        $oc->insertaCxPEspecial($ordenCompra);
    }
}