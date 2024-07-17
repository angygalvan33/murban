<?php
include_once '../../clases/obra.php';
include_once '../../clases/tipoObra.php';
include_once '../../clases/cliente.php';
include_once '../../clases/requisicion.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $_POST['IdTipoObra'] = empty($_POST['IdTipoObra']) == TRUE ? '' : $_POST['IdTipoObra'];
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $_POST['nombreCliente'] = empty($_POST['nombreCliente']) == TRUE ? '' : $_POST['nombreCliente'];
    $_POST['domicilio'] = empty($_POST['domicilio']) == TRUE ? '' : $_POST['domicilio'];
    $_POST['descripcion'] = empty($_POST['descripcion']) == TRUE ? '' : $_POST['descripcion'];
    $_POST['presupuesto'] = empty($_POST['presupuesto']) == TRUE ? '' : $_POST['presupuesto'];
	$_POST['foto'] = empty($_POST['foto']) == TRUE ? '' : $_POST['foto'];
    $_POST['ocMonto'] = empty($_POST['ocMonto']) == TRUE ? 0 : $_POST['ocMonto'];
    /*Estos campos están vacios por que ya no se estás ocupando*/
    $_POST['nombreCliente'] = '';
    $_POST['domicilio'] = '';
    $_POST['presupuesto'] = 0.00;
    
    $fecha = $_POST['fechaEntregaEstimada'];
    $fecha = str_replace('/', '-', $fecha);
    $fecha = date('Y-m-d', strtotime($fecha));
    
    $ob = new Obra();
    $ob->llenaDatos(-1, $_POST['tipoObra'], $_POST['nombre'], $_POST['nombreCliente'], $_POST['domicilio'], $_POST['descripcion'], $_POST['presupuesto'], $_POST['ComboClientes'], $_POST['ocFolio'], $_POST['ocMonto'], $fecha);
	$ob->archivo = $_POST['foto'];
    $ob->inserta();
}
//baja
else if ($accion == 'baja') {
    $ob = new Obra();
    return $ob->baja($_POST['id']);
}
//editar
else if($accion == 'editar') {
    $_POST['IdTipoObra'] = empty($_POST['IdTipoObra']) == TRUE ? '' : $_POST['IdTipoObra'];
    //echo "*******".$_POST['tipoObra']."******";
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $_POST['nombreCliente'] = empty($_POST['nombreCliente']) == TRUE ? '' : $_POST['nombreCliente'];
    $_POST['domicilio'] = empty($_POST['domicilio']) == TRUE ? '' : $_POST['domicilio'];
    $_POST['descripcion'] = empty($_POST['descripcion']) == TRUE ? '' : $_POST['descripcion'];
    $_POST['presupuesto'] = empty($_POST['presupuesto']) == TRUE ? '' : $_POST['presupuesto'];
	$_POST['foto'] = empty($_POST['foto']) == TRUE ? '' : $_POST['foto'];
    $_POST['ocMonto'] = empty($_POST['ocMonto']) == TRUE ? 0 : $_POST['ocMonto'];
    /*Estos campos están vacios por que ya no se estás ocupando*/
    $_POST['nombreCliente'] = '';
    $_POST['domicilio'] = '';
    $_POST['presupuesto'] = '';
    
    $fecha = $_POST['fechaEntregaEstimada'];
    $fecha = str_replace('/', '-', $fecha);
    $fecha = date('Y-m-d', strtotime($fecha));
    
    $ob = new Obra();
    $ob->llenaDatos($_POST['id'], $_POST['tipoObra'], $_POST['nombre'], $_POST['nombreCliente'], $_POST['domicilio'], $_POST['descripcion'], $_POST['presupuesto'], $_POST['ComboClientes'], $_POST['ocFolio'], $_POST['ocMonto'], $fecha);
	$ob->archivo = $_POST['foto'];
    $ob->editar();
}
else if ($accion == 'getTiposObra') {
    $tipo = new TipoObra();
    $tipo -> getTiposObra();
}
else if ($accion == 'getClientes') {
    $cliente = new cliente();
    $cliente->getClientes();
}
else if ($_POST['accion'] == 'subeFoto') {
    if (!empty ($_FILES)) {
        $archivo = $_FILES['artFoto'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if($ext != "png" && $ext != "jpg" && $ext != "jpeg") {
            return false;
        }

        $name = "art_". date("d-M-Y-H-i-s") .".". $ext;
        file_put_contents("../../images/obra/". $name, file_get_contents($archivo['tmp_name']));
        echo $name;
    }
    return false;
}
else if ($accion == 'terminar') {
    $obra = new Obra();
    echo $obra->cambiarEstadoTerminada($_POST['IdObra'], $_POST['Estatus']);
}
else if ($accion == 'agregarAProyecto') {
    $obra = new Obra();
    $obra->agregarProducto($_POST['idObra'], $_POST['idProducto'], $_POST['cantidad']);
    
    $requisicion = new Requisicion();
    echo $requisicion->crearRequisicionProducto($_POST['idObra'], $_POST['idProducto'], $_POST['cantidad']);
}
else if ($accion == 'eliminarDeProyecto') {
    //echo "****".$_POST['idRequisicion']."****";
    $obra = new Obra();
    $obra->eliminarDeProyecto($_POST['idRequisicion']);
    
    $requisicion = new Requisicion();
    echo $requisicion->eliminarRequisicionProducto($_POST['idRequisicion']);
}