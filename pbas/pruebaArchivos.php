<?php

include_once '../clases/conexion.php';

function insertarArchivo($nombre, $blob, $extension)
{
    $conexion = new Conexion();
    $conexion->abrirBD();
    
    $query = "INSERT INTO PruebaArchivo(Nombre, Archivo, Extension) VALUES ('" . $nombre . "', '" . $blob . "', '" . $extension . "');";

    mysqli_query($conexion->mysqli, $query);
    
    $conexion->cerrarBD();
}

/*  Inserción de prueba 

$url = "C:\Users\Rafa\Pictures\Wallpapers\Bliss.png";

$nombre = "Carro";
$blob = file_get_contents($url);
$extension = "png";

insertarArchivo($nombre, $blob, $extension);
  
*/

function descargarArchivo($idArchivo)
{
    $conexion = new Conexion();
    $conexion->abrirBD();
    
    $query = "SELECT * FROM PruebaArchivo WHERE IdArchivo = " . $idArchivo . ";";
    
    $result = mysqli_query($conexion->mysqli, $query);
    $result = mysqli_fetch_assoc($result);
    
    $conexion->cerrarBD();
    
    $nombre = $result['Nombre'];
    $archivo = $result['Archivo'];
    $extension = $result['Extension'];
    
    $ruta = "C:/" . $nombre . "." . $extension;
    
    file_put_contents($ruta, $archivo);
}

/*  Descarga de prueba

descargarArchivo(4);

*/