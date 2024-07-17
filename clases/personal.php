<?php
include_once 'conexion.php';

class Personal {
    private $id;
    private $nombre;
    private $direccion;
    private $fechanac;
    private $nss;
    private $telefono;
    
    public function __construct() {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->direccion = NULL;
        $this->fechanac = NULL;
        $this->nss = NULL;
        $this->telefono = NULL;
    }

    public function llenaDatos(
                            $id_,
                            $nombre_,
                            $direccion_,
                            $fechanac_,
                            $nss_,
                            $telefono_) {
        $this->id = $id_;
        $this->nombre = $nombre_;
        $this->direccion = $direccion_;
        $this->fechanac = $fechanac_;
        $this->nss = $nss_;
        $this->telefono = $telefono_;
    }
    
    public function inserta() {
        $conexion = new Conexion();
        
        try {
            $conexion->obtenerNuevoIdTabla('Personal')['result'];
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO Personal (IdPersonal, Nombre, Direccion, FechaNac, NSS, Telefono) VALUES (". $nueviId .", '$this->nombre', '$this->direccion', '$this->fechanac', '$this->nss', '$this->telefono');";
                //echo "****".$query."****";
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = $nueviId;
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
        return $conexion->result;
    }
    
    public function baja($idPers) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Personal SET Eliminado = now() WHERE IdPersonal = ". $idPers;

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
    }
    
    public function editar() {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Personal SET Nombre = '$this->nombre', Direccion = '$this->direccion', FechaNac = '$this->fechanac', NSS = '$this->nss', Telefono = '$this->telefono' WHERE IdPersonal = ". $this->id;

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

        //echo json_encode($conexion->result);
    }
    
    public function getPersonalActivoFilter($busqueda) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT * FROM Personal WHERE Activo = 1 AND Eliminado IS NULL AND Nombre like '%". $busqueda ."%' limit 5;";
            }
            else {
                $query = "SELECT * FROM Personal WHERE Activo = 1 AND Eliminado IS NULL limit 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id" => $row['IdPersonal'], "text" => $row['Nombre']);
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

    public function desactivaPersonal($idPersonal_, $estatus_) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Personal SET Activo = ". $estatus_ ." WHERE IdPersonal = ". $idPersonal_;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        
        echo json_encode($conexion->result);
    }
}
