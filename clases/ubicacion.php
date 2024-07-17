<?php

include_once 'conexion.php';

class Ubicacion {
    private $id;
    private $nombre;
    private $descripcion;
    private $idMaterial;
    
    public function __construct() {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->descripcion = NULL;
        $this->idMaterial = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $nombre_,
                            $descripcion_) {
        $this->id = $id_;
        $this->nombre = $nombre_;
        $this->descripcion = $descripcion_;
    }

    public function inserta() {
        $conexion = new Conexion();
        
        try {
            $conexion->existe('Ubicacion', 'Nombre', "'". $this->nombre ."'", $this->id);
            $repetido = $conexion->result['result'];

            if ($repetido > 0) {
                $conexion->result['error'] = 2;
                $conexion->result['result'] = "NOMBRE DE UBICACION REPETIDO.";
                echo json_encode($conexion->result);
                return;
            }
            
            $conexion->obtenerNuevoIdTabla('Ubicacion')['result'];
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO Ubicacion
                (IdUbicacion,
                Nombre,
                Descripcion)
                VALUES
                (". $nueviId .",
                '$this->nombre',
                '$this->descripcion');";
//                echo $query;
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
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
        echo json_encode($conexion->result);
    }
    
    public function baja($idUbicacion) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Ubicacion SET Eliminado = now() WHERE IdUbicacion = ". $idUbicacion;

                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
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
        //return $conexion->result;
    }
    
    public function editar() {
        $conexion = new Conexion();
        
        try {
            $conexion->existe('Ubicacion', 'Nombre', "'". $this->nombre ."'", $this->id);
            $repetido = $conexion->result['result'];

            if ($repetido > 0) {
                $conexion->result['error'] = 2;
                $conexion->result['result'] = "NOMBRE DE UBICACION REPETIDO.";
                echo json_encode($conexion->result);
                return;
            }
            
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Ubicacion SET Nombre = '$this->nombre', Descripcion = '$this->descripcion' WHERE IdUbicacion = ". $this->id;

                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
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

        echo json_encode($conexion->result);
    }

    public function getUbicacionesFilter($busqueda) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT * FROM Ubicacion WHERE Eliminado IS NULL AND Nombre like '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT * FROM Ubicacion WHERE Eliminado IS NULL limit 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            $listaUbicaciones = array();

            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['IdUbicacion'] = $row['IdUbicacion'];
                $mat['Nombre'] = $row['Nombre'];
                $listaUbicaciones[] = $mat;
                $data[] = array("id" => $mat['IdUbicacion'], "text" => $mat['Nombre']);
            }
            
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        echo json_encode($data);
    }
    
    public function insertaUbicacionMaterial($IdUbicacion_, $IdMaterial_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO UbicacionMaterial (IdUbicacion, IdMaterial) VALUES (". $IdUbicacion_ .", ". $IdMaterial_ .");";
//                echo $query;
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
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

        echo json_encode($conexion->result);
    }
    
    public function bajaUbicacionMaterial($IdUbicacionMaterial) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "DELETE FROM UbicacionMaterial WHERE IdUbicacionMaterial = ". $IdUbicacionMaterial;

                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
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
        //return $conexion->result;
    }

    public function editaUbicacionMaterial($IdUbicacionA_, $IdUbicacionN_, $Cantidad_, $IdMaterial_, $NombreMaterial_) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        $cantRestante = $Cantidad_;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                do {
                    if ($IdMaterial_ == -1) {
                        $subQuery = "SELECT * FROM Inventario WHERE IdMaterial = ". $IdMaterial_ ." AND Nombre LIKE '". $NombreMaterial_ ."' AND IdUbicacion = ". $IdUbicacionA_ ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                    } else {
                        $subQuery = "SELECT * FROM Inventario WHERE IdMaterial = ". $IdMaterial_ ." AND IdUbicacion = ". $IdUbicacionA_ ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                    }
                    //echo "****". $subQuery ."****";
                    $result = mysqli_query($conexion->mysqli, $subQuery);
                    $row = mysqli_fetch_array($result);
                    
                    $cantInventario = $row['Cantidad'];
                    $idInventario = $row['IdInventario'];

                    if (floatval($cantRestante) >= floatval($cantInventario)) {
                        $cantRestante = floatval($cantRestante) - floatval($cantInventario);
                        $queryUpdate = "UPDATE Inventario SET IdUbicacion = ". $IdUbicacionN_ ." WHERE IdInventario = ". $idInventario;
                        //echo "****". $queryUpdate ."****";
                        if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                            $band_query_exito = -1;
                        }
                        //echo "****". $cantRestante .">". $cantInventario ."****";
                        if (floatval($cantRestante) > floatval($cantInventario)) {
                            $result = mysqli_query($conexion->mysqli, $subQuery);
                            //echo "****". $subQuery ."****";
                            $row = mysqli_fetch_array($result);

                            $cantInventario = $row['Cantidad'];
                            $idInventario = $row['IdInventario'];
                        }
                    }
                    else {
                        $resta = $cantInventario - $cantRestante;

                        $queryUpdate = "UPDATE Inventario SET Cantidad = ". $resta ." WHERE IdInventario = ". $idInventario;
                        //echo "****". $queryUpdate ."****";
                        if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                            $band_query_exito = -2;
                        }

                        $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, IdUbicacion)
                            SELECT IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, ". $cantRestante .", PrecioUnitario, ". $IdUbicacionN_ ." FROM Inventario WHERE IdInventario = ". $idInventario;
                        //echo "****". $queryInsert ."****";
                        $cantRestante = 0;

                        if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                            $band_query_exito = -3;
                        }
                    }

                    if (!mysqli_query($conexion->mysqli, $subQuery)) {
                        $band_query_exito = -4;
                    }
                    //echo "**** cantRestante: ".$cantRestante." ****";
                } while(floatval($cantRestante) > 0);
                //echo "**** band_query_exito: ". $band_query_exito ." ****";
                if ($band_query_exito > 0) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO MODIFICADO CON EXITO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "CodigoError: (". $subQuery .") ". $conexion->mysqli->error;
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
        echo json_encode($conexion->result);
    }
}