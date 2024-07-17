<?php
include_once "../../clases/requisicion.php";

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'guardarRequisicionManual') {
        //Observaciones
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
        echo $requi->crearRequiManual($ordenCompra, $detalleCompra);
    }
	else if ($_POST['accion'] == 'getMaterialById') {
        $material = new Material();
        $material->getMaterial_ById($_POST['idMaterial']);
    }
    else if ($_POST['accion'] == 'guardarRequisicionEspecial') {
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
        echo $requi->crearRequiEspecial($ordenCompra, $detalleCompra);
    }
    else if ($_POST['accion'] == 'modificarRequisicionDetalle') {
        $requi = new Requisicion();
        $requi->modificarRequisicionDetalle($IdRequisicionDetalle, $IdMaterial, $NombreMaterial, $IdProveedor, $Precio);
    }
    else if ($_POST['accion'] == 'cancelarRequisicion') {
        $requi = new Requisicion();
        $requi->cancelarRequisicionCompleta($_POST['idRequisicion'], $_POST['Motivo']);
    }
    else if ($_POST['accion'] == 'eliminarDetalle') {
        $requi = new Requisicion();
        $requi->eliminarDetalleRequisicion($_POST['IdRequisicionDetalle']);
    }
    else if ($_POST['accion'] == 'actualizarRequisicionManual') {
        //Observaciones
        $ordenCompra = [];
        $ordenCompra["Observaciones"] = $_POST['Observaciones'];
        $ordenCompra["IdRequisicion"] = $_POST['IdRequisicion'];
        //Detalle de requisicion
        $detalleCompra = [];
        foreach ($_POST as $key => $value) {
            $aux = explode(":_", $key);
            //encontro algo similar 1:_
            if (count($aux) > 1)
                $detalleCompra[$aux[0]][$aux[1]] = $value;
        }

        $requi = new Requisicion();
        $requi->actualizarRequiManual($ordenCompra, $detalleCompra);
    }
    else if ($_POST['accion'] == 'getMaterialByIdDetalle') {
        $requi = new Requisicion();
        $requi->obtenerDetalleRequisicion($_POST['IdRequisicionDetalle']);
    }
    else if ($_POST['accion'] == 'guardarDetalleRequisicion') {
        $detalleOrdenCompra = [];
        $detalleOrdenCompra["IdRequisicionDetalle"] = $_POST['IdRequisicionDetalle'];
        $detalleOrdenCompra["IdProyecto"] = $_POST['IdProyecto'];
        $detalleOrdenCompra["Proyecto"] = $_POST['Proyecto'];
        $detalleOrdenCompra["IdMaterial"] = $_POST['IdMaterial'];
        $detalleOrdenCompra["Material"] = $_POST['Material'];
        $detalleOrdenCompra["Cantidad"] = $_POST['Cantidad'];
        $detalleOrdenCompra["Unidad"] = $_POST['Unidad'];

        $requi = new Requisicion();
        $requi->editarDetalleRequisicion($detalleOrdenCompra);
    }
    else if ($_POST['accion'] == 'autorizarRequisicion') {
        $requi = new Requisicion();
        $requi->autorizarRequisicionCompleta($_POST['idRequisicion']);
    }
}