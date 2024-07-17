<?php
include_once '../../clases/obra.php';
include_once '../../clases/proveedor.php';
include_once '../../clases/material.php';
include_once '../../clases/usuario.php';
include_once '../../clases/comodin.php';

if (!isset($_POST['searchTerm']))
    $search = null;
else
    $search = $_POST['searchTerm'];

if (!isset($_POST['band']))
    $band = null;
else
    $band = $_POST['band'];

if (isset($_POST['nombreAutocomplete'])) {
    if ($_POST['nombreAutocomplete'] == 'obra') {
        $obra = new Obra();
        echo $obra->getObraFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'proveedor') {
        $proveedor = new Proveedor();
        $proveedor->getProveedorFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'proveedorReq') {
        $proveedor = new Proveedor();
        $proveedor->getProveedorReqFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'proveedorReqxkilo') {
        $proveedor = new Proveedor();
        $proveedor->getProveedorReqFilterxkilo($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'proveedorReqEspecial') {
        $proveedor = new Proveedor();
        $proveedor->getProveedorReqEspecialesFilter($search);
    }
    else if ($_POST['nombreAutocomplete'] == 'material') {
        $material = new Material();
        echo $material->getMaterialProveedorFilter($search, $_POST['IdProveedor']);
    }
    else if ($_POST['nombreAutocomplete'] == 'usuario') {
        $usuario = new Usuario();
        echo $usuario->getUsuario($search);
        /*$comodin = new Comodin();
        $idUsuario = $comodin->idUsuarioSession();
        $usuario = new Usuario();
        echo $usuario->getUsuario($idUsuario);*/
    }
    else if ($_POST['nombreAutocomplete'] == 'proyectosReq') {
        $proyecto = new Obra();
        $proyecto->getObraReqFilter($search, $band);
    }
    else if ($_POST['nombreAutocomplete'] == 'proyectosReqEsp') {
        $proyecto = new Obra();
        $proyecto->getObraReqEspFilter($search);
    }
}