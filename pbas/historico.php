<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../clases/historicoPrecioMateriales.php';
include_once '../clases/material.php';

$historico = new HistoricoPrecioMateriales();

// Prueba de inserción de un precio.

$historico -> llenaDatos(-1, 15, 2, 54.50, 'Raul');
$historico -> guardarPrecio();

// Prueba de obtención de los datos.

echo $historico -> getHistoricoMateriales(2, 15);

// Prueba del método getMateriales.

$material = new Material();
echo $material -> getMateriales();