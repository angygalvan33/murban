<?php
include_once 'usuario.php';

class Comodin {
    public function idUsuarioSession() {
        if (session_status() != 2)
            session_start();
        
        $us = new Usuario();
        
        return $us->getIdFromUsername($_SESSION['username']);
    }
    
    public function nickUsuarioSession() {
        if (session_status() != 2)
            session_start();
        
        return $_SESSION['username'];
    }
    
    public function  nombreLogoEmpresa() {
        $logo = "";
        $directorio = opendir("../../images/logo"); //ruta actual

        while ($archivo = readdir($directorio)) {//obtenemos un archivo y luego otro sucesivamente
            if (!is_dir($archivo)) {
                $logo = $archivo;
            }
        }
        return $logo;
    }
    
    public function  NombreMaterial($conexion, $idMaterial) {
        $query = "SELECT * FROM Material WHERE IdMaterial = ". $idMaterial;
        $result = mysqli_query($conexion->mysqli, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['Nombre'];
    }
}