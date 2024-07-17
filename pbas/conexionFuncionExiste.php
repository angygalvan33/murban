<?php

include_once '../clases/conexion.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$con = new Conexion();
$veces = $con -> existe("Categoria", "Nombre", "Papeleria");
echo "hola";