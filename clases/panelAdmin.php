<?php
include_once 'conexion.php';

class PanelAdmin {
    private $id;
    private $idUsuario;
    private $presupuesto;
    
    public function __construct() {
        $this->id = NULL;
        $this->idUsuario = NULL;
        $this->presupuesto = NULL;
    }
    
    public function llenaDatos($id_, $idUsuario_, $presupuesto_) {
        $this->id = $id_;
        $this->idUsuario = $idUsuario_;
        $this->presupuesto = $presupuesto_;
    }

    public function actualizaDatosEmpresa($nombreEmp_, $direccion_, $municipio_, $estado_, $telefono_, $representante_, $rfc_, $email_, $maximoSinAutorizacion_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE DatosEmpresa SET Nombre = '$nombreEmp_', Direccion = '$direccion_', Municipio = '$municipio_', Estado = '$estado_', Telefono = '$telefono_', Representante = '$representante_', RFC = '$rfc_', Email = '$email_', MaximoSinAutorizacion = $maximoSinAutorizacion_ WHERE IdDatosEmpresa = 1";
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'INFORMACIÓN MODIFICADA';
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
            $conexion->result['result'] = "REGISTRO NO MODIFICADO";
        }
        
        echo json_encode($conexion->result);
    }
    
    public function leerDatosEmpresa() {
        $conexion = new Conexion();
        $conexion->abrirBD();
        if ($conexion->mysqli != NULL) {
            $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1;";
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);

            if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                $conexion->result['nombreEmpresa'] = $result['Nombre'];
                $conexion->result['rfcEmpresa'] = $result['RFC'];
                $conexion->result['emailEmpresa'] = $result['Email'];
                $conexion->result['direccionEmpresa'] = $result['Direccion'];
                $conexion->result['municipioEmpresa'] = $result['Municipio'];
                $conexion->result['edoEmpresa'] = $result['Estado'];
                $conexion->result['representanteEmpresa'] = $result['Representante'];
                $conexion->result['telefonoEmpresa'] = $result['Telefono'];
                $conexion->result['politicasCompras'] = $result['PoliticasCompra'];
                $conexion->result['maximoSinAutorizacionEmpresa'] = $result['MaximoSinAutorizacion'];
                $conexion->result['error'] = 0;
                $result['error'] = 0;
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
            $conexion->cerrarBD();
        }
        echo json_encode($conexion->result);
    }
    
    public function acceso($nombre) {
        $conexion = new Conexion();
        $conexion->abrirBD();

        if ($conexion->mysqli != NULL) {
            $query = "SELECT * FROM PanelAdmin WHERE Nombre = '". $nombre ."'";
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            return (intval($result["Valor"]) == 1);
        }
        return false;
    }
    
    public function actualizaPoliticasCompra($politicas_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE DatosEmpresa SET PoliticasCompra = '$politicas_' WHERE IdDatosEmpresa = 1";
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'INFORMACIÓN MODIFICADA';
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
            $conexion->result['result'] = "REGISTRO NO MODIFICADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function leerPoliticasCompra() {
        $conexion = new Conexion();
        $conexion->abrirBD();

        try {
            if ($conexion->mysqli != NULL) {
                $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1;";
                $result = mysqli_query($conexion->mysqli, $query);
                $result = mysqli_fetch_assoc($result);

                $conexion->result['error'] = 0;
                $conexion->result['politicasCompras'] = $result['PoliticasCompra'];
                $conexion->cerrarBD();
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR LEER INFORMACIÓN DE POLITICAS DE COMPRA";
        }
        echo json_encode($conexion->result);
    }
}