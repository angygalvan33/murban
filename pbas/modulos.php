<?php
include_once '../clases/modulo.php';

$modulo = new Modulo();

#Regresa todos los modulos
$modulo->listado();
echo '<br/>';
echo '<br/>';
echo '<br/>';
#Regresa un modulo en especial
$IdModulo = 2;
$modulo->listado($IdModulo);