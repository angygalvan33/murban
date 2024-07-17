<?php
include_once '../../../clases/articulo.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'guardarArticulo') {
        $articulo = [];
        $articulo["IdLinea"] = $_POST['IdLinea'];
        $articulo["Clave"] = $_POST['Clave'];
        $articulo["Nombre"] = $_POST['Nombre'];
        $articulo["Descripcion"] = $_POST['Descripcion'];
        $articulo["Foto"] = $_POST['Foto'];
        $detalleArticulo = [];
        $detalleArticulo = $_POST['materiales'];
        $fotosArticulos = [];
        $fotosArticulos = $_POST['fotos'];

        $art = new Articulo();
        $art->llenaDatos(-1, $articulo['IdLinea'], $articulo['Clave'], $articulo['Nombre'], $articulo['Descripcion'], $detalleArticulo, $fotosArticulos, $articulo['Foto']);
        echo json_encode($art->insertaArticulo());
    }
    else if ($_POST['accion'] == 'editar') {
        $articulo = [];
        $articulo["IdArticulo"] = $_POST['IdArticulo'];
        $articulo["Clave"] = $_POST['Clave'];
        $articulo["IdLinea"] = $_POST['IdLinea'];
        $articulo["Nombre"] = $_POST['Nombre'];
        $articulo["Descripcion"] = $_POST['Descripcion'];
        $articulo["Foto"] = null;
        $detalleArticulo = [];
        $fotosArticulos = [];
        
        $art = new Articulo();
        $art->llenaDatos($articulo["IdArticulo"], $articulo['IdLinea'], $articulo['Clave'], $articulo['Nombre'], $articulo['Descripcion'], $detalleArticulo, $fotosArticulos, $articulo['Foto']);
        $art->editar();
    }
    else if ($_POST['accion'] == 'baja') {
        $art = new Articulo();
        $art->baja($_POST['id']);
    }
    else if ($_POST['accion'] == 'subeFoto') {
        if (!empty($_FILES)) {
            $archivo = $_FILES['artFoto'];
            $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

            if ($ext != "png" && $ext != "jpg" && $ext != "jpeg")
                return false;
            
            $name = "art_". date("d-M-Y-H-i-s") .".". $ext;
            file_put_contents("../../../images/articulos/". $name, file_get_contents($archivo['tmp_name']));
            echo $name;
        }
        return false;
    }
}