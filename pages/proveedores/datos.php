<?php
include_once '../../clases/proveedor.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $_POST['direccion'] = empty($_POST['direccion']) == TRUE ? '' : $_POST['direccion'];
    $_POST['telefono'] = empty($_POST['telefono']) == TRUE ? '' : $_POST['telefono'];
    $_POST['representante'] = empty($_POST['representante']) == TRUE ? '' : $_POST['representante'];
    $_POST['email'] = empty($_POST['email']) == TRUE ? '' : $_POST['email'];
    $_POST['rfc'] = empty($_POST['rfc']) == TRUE ? '' : $_POST['rfc'];
    $_POST['diasCredito'] = empty($_POST['diasCredito']) == TRUE ? '0' : $_POST['diasCredito'];
    $_POST['limiteCredito'] = empty($_POST['limiteCredito']) == TRUE ? '0' : $_POST['limiteCredito'];
    
    $prov = new Proveedor();
    $prov->llenaDatos(-1, $_POST['nombre'], $_POST['direccion'], $_POST['telefono'], $_POST['representante'], $_POST['email'], $_POST['rfc'], $_POST['diasCredito'], $_POST['limiteCredito']);
    $prov->inserta();
}
//baja
else if ($accion == 'baja') {
    $prov = new Proveedor();
    return $prov->baja($_POST['id']);
}
//editar
else if ($accion == 'editar') {
    $_POST['direccion'] = empty($_POST['direccion']) == TRUE ? '' : $_POST['direccion'];
    $_POST['telefono'] = empty($_POST['telefono']) == TRUE ? '' : $_POST['telefono'];
    $_POST['representante'] = empty($_POST['representante']) == TRUE ? '' : $_POST['representante'];
    $_POST['email'] = empty($_POST['email']) == TRUE ? '' : $_POST['email'];
    $_POST['rfc'] = empty($_POST['rfc']) == TRUE ? '' : $_POST['rfc'];
    $_POST['diasCredito'] = empty($_POST['diasCredito']) == TRUE ? '0' : $_POST['diasCredito'];
    $_POST['limiteCredito'] = empty($_POST['limiteCredito']) == TRUE ? '0' : $_POST['limiteCredito'];
    
    $prov = new Proveedor();
    $prov->llenaDatos($_POST['id'], $_POST['nombre'], $_POST['direccion'], $_POST['telefono'], $_POST['representante'], $_POST['email'], $_POST['rfc'], $_POST['diasCredito'], $_POST['limiteCredito']);
    $prov->editar();
}
//get Proveedores json
else if ($accion == 'getProveedores') {
    $prov = new Proveedor();
    $prov->listadoProveedores();
}