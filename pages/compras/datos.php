<?php
include_once '../../clases/obra.php';
include_once '../../clases/material.php';
include_once '../../clases/metodoPago.php';
include_once '../../clases/ordenCompra.php';
include_once '../../clases/requisicion.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'getInfoObraById') {
        $obra = new Obra();
        echo $obra->getObraById($_POST['idObra']);
    }
    else if ($_POST['accion'] == 'getInfoMaterialById') {
        $material = new Material();
        $material->getMaterialByIdProveedor($_POST['idMaterial'], $_POST['idProveedor']);
    }
    else if($_POST['accion'] == 'getMetodosPago') {
        $metodoPago = new MetodoPago();
        echo $metodoPago->listadoMetodoPago();
    }
    else if($_POST['accion'] == 'guardarOCEspecial') {
        //Datos de orden de compra
        $ordenCompra = [];
        $ordenCompra["IdProveedor"] = $_POST['IdProveedor'];
        $ordenCompra["IdUsuario"] = $_POST['IdUsuario'];
        $ordenCompra["IdEstadoOC"] = $_POST['IdEstadoOC'];
        $ordenCompra["Subtotal"] = $_POST['Subtotal'];
        $ordenCompra["IVA"] = $_POST['IVA'];
        $ordenCompra["Total"] = $_POST['Total'];
        $ordenCompra["PermisoAutorizar"] = $_POST['PermisoAutorizar'];
        $ordenCompra["Pagada"] = $_POST['Pagada'];
        $ordenCompra["Anticipo"] = $_POST['Anticipo'];
        $ordenCompra["IdMetodoPago"] = $_POST['IdMetodoPago'];
        $ordenCompra["Descripcion"] = $_POST['Descripcion'];
        $ordenCompra["NotasProveedor"] = $_POST['NotasProveedor'];
        $ordenCompra["NumCotizacion"] = $_POST['numCotizacion'];
        $ordenCompra["TipoPago"] = $_POST['TipoPago'];
        //Referencia -> referencia de pago
        //Detalle de orden de compra
        $detalleCompra = [];
        foreach ($_POST as $key => $value) {
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

        $oc = new OrdenCompra();
        $ordenCompra['IdUsuarioAutoriza'] = NULL;
        //si fue PagoRequerido directo a autorizar
        if ($ordenCompra['Pagada'] == '1' || $ordenCompra['PermisoAutorizar'] == 'false') {
            $ordenCompra['IdEstadoOC'] = 1;
        }
        //si no fue con pagoRequerido y se dio algun anticipo o tenia permiso de autorizar va a emitidas
        else if ($ordenCompra['Anticipo'] != '0' || $ordenCompra['PermisoAutorizar'] == 'true') {
            $ordenCompra['IdEstadoOC'] = 2;
            $ordenCompra['IdUsuarioAutoriza'] = $_POST['IdUsuario'];
        }
        
        if ($ordenCompra["TipoPago"] == 3) {
            $oc->permisosAutorizar = 1;
        }
        
        $oc->llenaDatos(-1,
                $ordenCompra['IdProveedor'],
                $ordenCompra['IdUsuario'],
                $ordenCompra['IdEstadoOC'],
                $ordenCompra['Subtotal'],
                $ordenCompra['IVA'],
                $ordenCompra['Total'],
                0, //pagada
                $ordenCompra['Pagada'], //pagoRequerido
                $ordenCompra['Anticipo'],
                $ordenCompra['IdMetodoPago'],
                $ordenCompra['Descripcion'],
                $ordenCompra['NotasProveedor'],
                2,
                $detalleCompra,
                $ordenCompra['IdUsuarioAutoriza'],
                $ordenCompra["NumCotizacion"]);
        echo json_encode($oc->insertaOC());
    }
    //regresa la referencia del método de pago
    else if ($_POST['accion'] == 'getReferenciaMetodoPago') {
        $mp = new MetodoPago();
        $mp->informacionMetodoPago($_POST['idMetodoPago']);
    }
    else if ($_POST['accion'] == 'recibirOC') {
        // Cambios para el sistema básico: IdOC
        $oc = new OrdenCompra();
        $req = new Requisicion();
        $req->entregaRequisicion($_POST['IdOC']);
        echo $oc->recibirCompraCompleta($_POST['IdOC']);
    }
    //OC de requisicion nueva
    else if ($_POST['accion'] == 'guardarOCRequisicionNueva') {
        //Datos de orden de compra
        $ordenCompra = [];
        $ordenCompra["IdProveedor"] = $_POST['IdProveedor'];
        $ordenCompra["IdUsuario"] = $_POST['IdUsuario'];
        $ordenCompra["IdEstadoOC"] = $_POST['IdEstadoOC'];
        $ordenCompra["Subtotal"] = $_POST['Subtotal'];
        $ordenCompra["IVA"] = $_POST['IVA'];
        $ordenCompra["Total"] = $_POST['Total'];
        $ordenCompra["PermisoAutorizar"] = $_POST['PermisoAutorizar'];
        $ordenCompra["Pagada"] = $_POST['Pagada'];
        $ordenCompra["Anticipo"] = $_POST['Anticipo'];
        $ordenCompra["IdMetodoPago"] = $_POST['IdMetodoPago'];
        $ordenCompra["Descripcion"] = $_POST['Descripcion'];
        $ordenCompra["NotasProveedor"] = $_POST['NotasProveedor'];
        $ordenCompra["NotasProveedor"] = $_POST['NotasProveedor'];
        $ordenCompra["NumCotizacion"] = $_POST['numCotizacion'];
        $ordenCompra["TipoPago"] = $_POST['TipoPago'];
        $ordenCompra["idsReqs"] = $_POST['idsReqs']; //Ids de DetalleReq
        //Referencia -> referencia de pago
        //Detalle de orden de compra
        $detalleCompra = [];
        //echo "****".$_POST['IdProveedor']."****";
        foreach ($_POST as $key => $value) {
            $aux = explode(":_", $key);
            //encontro algo similar 1:_
            if (count($aux) > 1)
                $detalleCompra[$aux[0]][$aux[1]] = $value;
        }

        $oc = new OrdenCompra();
        $ordenCompra['IdUsuarioAutoriza'] = NULL;
        //si fue PagoRequerido directo a autorizar
        if ($ordenCompra['Pagada'] == '1' || $ordenCompra['PermisoAutorizar'] == 'false') {
            $ordenCompra['IdEstadoOC'] = 1;
        }
        //si no fue con pagoRequerido y se dio algun anticipo o tenia permiso de autorizar va a emitidas
        else if ($ordenCompra['Anticipo'] != '0' || $ordenCompra['PermisoAutorizar'] == 'true') {
            $ordenCompra['IdEstadoOC'] = 2;
            $ordenCompra['IdUsuarioAutoriza'] = $_POST['IdUsuario'];
        }
        
        if ($ordenCompra["TipoPago"] == 3) {
            $oc->permisosAutorizar = 1;
        }
        
        $oc->llenaDatos(-1,
                $ordenCompra['IdProveedor'],
                $ordenCompra['IdUsuario'],
                $ordenCompra['IdEstadoOC'],
                $ordenCompra['Subtotal'],
                $ordenCompra['IVA'],
                $ordenCompra['Total'],
                0, //pagada
                $ordenCompra['Pagada'], //pagoRequerido
                $ordenCompra['Anticipo'],
                $ordenCompra['IdMetodoPago'],
                $ordenCompra['Descripcion'],
                $ordenCompra['NotasProveedor'],
                2,
                $detalleCompra,
                $ordenCompra['IdUsuarioAutoriza'],
                $ordenCompra["NumCotizacion"]);
        
        echo json_encode($oc->insertaOCRequisicion($ordenCompra["idsReqs"]));
    }
    else if ($_POST['accion'] == 'resetRequisiciones') {
        $req = new Requisicion();
        echo $req->resetRequsicionesCheck();
    }
    else if ($_POST['accion'] == 'preciosOC') {
        $oc = new OrdenCompra();
        $oc->actualizarPrecios($_POST['IdOC']);
        echo $oc->actualizarTotal($_POST['IdOC']);
    }
    else if ($_POST['accion'] == 'precioDetalleOC') {
        $oc = new OrdenCompra();
        $oc->actualizaPrecioDetalle($_POST['idDetalleOC'], $_POST['IdOC']);
        echo $oc->actualizarTotal($_POST['IdOC']);
    }
    else if ($_POST['accion'] == 'cambiaNvoProv') {
        $oc = new OrdenCompra();
        echo $oc->actualizaProveedor($_POST['IdOC'], $_POST['IdProvNvo']);
    }
    else if ($_POST['accion'] == 'eliminarPartida') {
        $oc = new OrdenCompra();
        $oc->eliminarPartidaOC($_POST['idDetalleOC'], $_POST['IdOC']);
        echo $oc->actualizarTotal($_POST['IdOC']);
    }
}