<?php
include_once 'conexion.php';

class Nomina {
    private $id;
    private $idPersonal;
    private $fechIng;
    private $idDepartamento;
    private $puesto;
    private $periodo;
    private $sueldo;
    private $fechaBaja;
    
    public function __construct() {
        $this->id = NULL;
        $this->idPersonal = NULL;
        $this->fechaIng = NULL;
        $this->idDepartamento = NULL;
        $this->puesto = NULL;
        $this->periodo = NULL;
        $this->sueldo = NULL;
        $this->fechaBaja = NULL;
    }

    public function llenaDatos(
                            $id_,
                            $idPersonal_,
                            $fechaIng_,
                            $idDepartamento_,
                            $puesto_,
                            $periodo_,
                        	$sueldo_) {
        $this->id = $id_;
        $this->idPersonal = $idPersonal_;
        $this->fechaIng = $fechaIng_;
        $this->idDepartamento = $idDepartamento_;
        $this->puesto = $puesto_;
        $this->periodo = $periodo_;
        $this->sueldo = $sueldo_;
    }
    
    public function inserta() {
        $conexion = new Conexion();
        
        try {
            $conexion->obtenerNuevoIdTabla('Nomina')['result'];
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO Nomina (IdNomina, IdPersonal, FechaIngreso, IdDepartamento, Puesto, Periodo, Sueldo)
                    VALUES (". $nueviId .", $this->idPersonal, '$this->fechaIng', $this->idDepartamento, '$this->puesto', $this->periodo, $this->sueldo);";
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = "REGISTRO INSERTADO";
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

    public function editar() {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Nomina SET FechaIngreso = '$this->fechaIng', IdDepartamento = $this->idDepartamento, Puesto = '$this->puesto', Periodo = $this->periodo, Sueldo = $this->sueldo WHERE IdPersonal = ". $this->idPersonal;
                
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

    public function baja($idPers) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Nomina SET FechaBaja = now() WHERE IdPersonal = ". $idPers;

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
        //echo json_encode($conexion->result);
    }
}