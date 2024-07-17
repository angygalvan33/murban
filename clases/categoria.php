<?php
include_once 'conexion.php';

class Categoria {
    private $id;
    private $nombre;
    
    public function __construct() {
        $this->id = NULL;
        $this->nombre = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $nombre_) {
        $this->id = $id_;
        $this->nombre = $nombre_;
    }

    public function inserta() {
        $conexion = new Conexion();
        $conexion->existe('Categoria', 'Nombre', "'". $this->nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido == 0) {
            try {
                $conexion->obtenerNuevoIdTabla('Categoria');
                $nueviId = $conexion->result['result'];

                if($conexion->abrirBD() != NULL) {
                    $query = "INSERT INTO Categoria (IdCategoria, Nombre) VALUES (". $nueviId .", '$this->nombre');";

                    if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'REGISTRO INSERTADO';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = $conexion->mysqli->error;
                    }
                    $conexion->cerrarBD();
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
                }
            }
            catch (Exception $ex) {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO";
            }
        }
        else {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE CATEGORIA REPETIDO.";
        }
        
        echo json_encode($conexion->result);
    }
    
    public function baja($idCat) {
        $conexion = new Conexion();
        $conexion->existe('Material', 'IdCategoria', $idCat, -1);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "CATEGORIA ASIGNADA A UN MATERIAL.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Categoria SET Eliminado = now() WHERE IdCategoria = ". $idCat;

                if( mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO ELIMINADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO ELIMINADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO ELIMINADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function editar() {
        $conexion = new Conexion();
        $conexion->existe('Categoria', 'Nombre', "'". $this->nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido == 0) {
            try {
                if($conexion->abrirBD() != NULL) {
                    $query = "UPDATE Categoria SET Nombre = '$this->nombre' WHERE IdCategoria = ". $this->id;

                    if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'REGISTRO MODIFICADO';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = $conexion->mysqli->error;
                    }
                    $conexion->cerrarBD();
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "REGISTRO NO MODIFICADO, ERROR CONEXION BD";
                }
            }
            catch (Exception $ex) {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO MODIFICADO";
            }
        }
        else {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE CATEGORIA REPETIDO.";
        }
        echo json_encode($conexion->result);
    }
    
    public function getCategorias() {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT IdCategoria, Nombre FROM Categoria WHERE Eliminado IS NULL";
            $result = mysqli_query($conexion->mysqli, $query);

            while($fila = $result->fetch_assoc()) {
                $listaCat[]=$fila;
            }
            $conexion->cerrarBD();
            
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaCat;
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode(array("error"=>$conexion->result['error'],"result"=>$txt));
    }
    
    public function getCategoriaFilter($busqueda)
    {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                $query = "SELECT * FROM Categoria WHERE Eliminado IS NULL AND Nombre LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT * FROM Categoria WHERE Eliminado IS NULL LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            $data[] = array("id" => 0, "text" => 'SIN CATEGORÍA');
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id" => $row['IdCategoria'], "text" => $row['Nombre']);
            }
            
            $data = $conexion->utf8_converter($data);
            echo json_encode($data);
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
    }
}