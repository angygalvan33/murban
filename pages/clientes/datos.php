<?php
include_once '../../clases/cliente.php';

$accion = $_POST['accion'];
//alta
if ($accion == 'alta') {
    $cliente = new cliente();
    $cliente->llenaDatos(-1, $_POST['nombre'], $_POST['direccion'], $_POST['tipoPersona'], $_POST['rfc'], $_POST['email'], $_POST['telefono'], $_POST['diasCredito'], $_POST['limiteCredito'], $_POST['contactos']);
    $cliente->inserta();
}
//baja
else if($accion == 'baja') {
    $cliente = new cliente();
    $cliente->baja($_POST['id']);
}
//editar
else if($accion == 'editar') {
    $cliente = new cliente();
    $cliente->llenaDatos($_POST['id'], $_POST['nombre'], $_POST['direccion'], $_POST['tipoPersona'], $_POST['rfc'], $_POST['email'], $_POST['telefono'], $_POST['diasCredito'], $_POST['limiteCredito'], $_POST['contactos']);
    $cliente->editar();
}