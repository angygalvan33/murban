<?php
include_once "../../../clases/historicoPrecioMateriales.php";
include_once "../../../clases/requisicion.php";

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'guardarRequisicionEspecial') {
        $ordenCompra = [];
        $ordenCompra["Observaciones"] = $_POST['Observaciones'];
        //Detalle de requisicion
        $detalleCompra = [];
        foreach ($_POST as $key => $value) {
            $aux = explode(":_", $key);
            //encontro algo similar 1:_
            if (count($aux) > 1)
                $detalleCompra[$aux[0]][$aux[1]] = $value;
        }

        $requi = new Requisicion();
        $requi->crearRequiEspecial($ordenCompra,$detalleCompra);
    }
    //asignar prov precio
    else if ($_POST['accion'] == 'asigarProveedor' || $_POST['accion'] = 'asigarProveedorUnicaOcasion') {
        $IdRequisicionDetalle = $_POST['idReqDetalle'];
        $IdMaterial = $_POST['idMaterial'];
        $NombreMaterial = $_POST['material'];
        $IdProveedor = $_POST['idProveedor'];
        $Precio = $_POST['precio'];
        $result['error'] = 0;
        $result['result'] = "";
        //echo "Asignar proveedor****";
        if ($_POST['accion'] == 'asigarProveedor') {
            if ($_POST['moneda'] == 'P')
                $_POST['dolares'] = 0;
            
            $Iva = $_POST['iva'];
            $Moneda = $_POST['moneda'];
            $Cotizador = $_POST['cotizador'];
            $Dolares = $_POST['dolares'];
            
            $historico = new HistoricoPrecioMateriales();
            $historico->llenaDatos(-1, $IdMaterial, $IdProveedor, $Precio, $Iva, $Cotizador, $Moneda, $Dolares);
            $historico->guardarPrecioDesdeOC('historico', $result);
        }

        if($result['error'] == 0) {
            $requi = new Requisicion();
            $requi->modificarRequisicionDetalle($IdRequisicionDetalle, $IdMaterial, $NombreMaterial, $IdProveedor, $Precio);
        }
    }
    else if($accion == 'comprarOCEspecial') {
        $IdRequisicionDetalle = $_POST['idRequisicionDetalle'];
        $IdMaterial = $_POST['idMaterial'];
        $IdProyecto = $_POST['idProyecto'];
        $Cantidad = $_POST['cantidad'];
        $idProveedor =  $_POST['idProveedor'];
        
        $conexion->result['error'] = 0;
        $conexion->result['result'] = 'REQUISICION ESPECIAL LISTA PARA OC';
        echo json_encode($conexion->result);
    }
}