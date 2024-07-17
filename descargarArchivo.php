<?php

$idDetalleOC = $_GET['id'];

include_once 'clases/conexion.php';

$conexion = new Conexion();
$conexion->abrirBD();
$query = "SELECT * FROM DetalleOrdenCompra Where IdDetalleOrdenCompra = ".$idDetalleOC.";";
$result = mysqli_query($conexion->mysqli, $query);
$fila = $result->fetch_assoc();

$conexion->cerrarBD();

$file = $fila['NombreArchivo'];

//$filetype = mime_content_type($fila['NombreArchivo']);
//$filesize = $row -> filesize; 
$deco = $fila['Archivo']; 
$deco = base64_decode($deco);

file_put_contents("descargasTemp/" . $file, $deco);

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$filetype= finfo_file($finfo, "descargasTemp/" . $fila['NombreArchivo']);

if (file_exists("descargasTemp/" . $file)) {
    header('Content-Description: File Transfer');
    header("Content-Type: $filetype");
    header('Content-Disposition: attachment; filename="'.basename("descargasTemp/" . $file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize("descargasTemp/" . $file));
    readfile("descargasTemp/" . $file);
    exit;
}

?>