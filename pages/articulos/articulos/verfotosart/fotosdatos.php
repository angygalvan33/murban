<?php
include_once '../../../../clases/articulo.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'nuevafoto') {
        //Datos de la Foto
        $Foto = $_POST["Foto"];
	    $IdArticulo = $_POST["Id"];
	    $Principal = $_POST["Principal"];
        //echo "****".$IdArticulo."****";
        $art = new Articulo();
        echo json_encode($art->nuevaFoto($Foto, $IdArticulo, $Principal));
    }
    else if ($_POST['accion'] == 'principal') {
		$Foto = $_POST["Foto"];
		$IdArticulo = $_POST["Id"];
        $art = new Articulo();
        $art->fotoToPrincipal($Foto, $IdArticulo);
    }
    else if ($_POST['accion'] == 'eliminarfoto') {
        $art = new Articulo();
        $art->eliminarFotoArticulo($_POST['IdFoto']);
    }
}