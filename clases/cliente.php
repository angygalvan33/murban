<?php
include_once 'conexion.php';

class cliente {
    private $id;
    private $nombre;
    private $direccion;
    private $tipoPersona;
    private $rfc;
    private $email;
    private $telefono;
    private $diasCredito;
    private $limiteCredito;
    private $contactos;

    public function __construct() {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->direccion = NULL;
        $this->tipoEmpresa = NULL;
        $this->rfc = NULL;
        $this->contactos = NULL;
        $this->email = NULL;
        $this->telefono = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $nombre_,
                            $direccion_,
                            $tipoPersona_,
                            $rfc_,
                            $email_,
                            $telefono_,
                            $diasCredito_,
                            $limiteCredido_,
                            $contactos_) {
        $this->id = $id_;
        $this->nombre = $nombre_;
        $this->direccion = $direccion_;
        $this->tipoPersona = $tipoPersona_;
        $this->rfc = $rfc_;
        $this->email = $email_;
        $this->telefono = $telefono_;
        $this->diasCredito = $diasCredito_;
        $this->limiteCredito = $limiteCredido_;
        $this->contactos = $contactos_;
    }
    
    public function inserta() {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        $conexion->existe('Cliente', 'Nombre', "'". $this->nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE CLIENTE REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO Cliente
                (Nombre,
                Direccion,
                TipoPersona,
                Rfc,
                Email,
                Telefono,
                DiasCredito,
                LimiteCredito,
                Contactos)
                VALUES
                ('$this->nombre',
                '$this->direccion',
                '$this->tipoPersona',
                '$this->rfc',
                '$this->email',
                '$this->telefono',
                '$this->diasCredito',
                '$this->limiteCredito',
                '$this->contactos');";
                
                mysqli_query($conexion->mysqli, $query);
                $query = "SELECT last_insert_id() AS IdCliente";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $idCliente = $row['IdCliente'];
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO GUARDADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO GUARDADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO GUARDADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function baja($idCliente) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Cliente SET Eliminado = now() WHERE IdCliente = ". $idCliente;

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
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query="UPDATE Cliente SET Nombre = '$this->nombre', TipoPersona = '$this->tipoPersona', Direccion = '$this->direccion', Rfc = '$this->rfc', DiasCredito = ". $this->diasCredito .", LimiteCredito = ". $this->limiteCredito .", Contactos = '$this->contactos', Telefono = '$this->telefono', Email = '$this->email' WHERE IdCliente = ". $this->id;
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO GUARDADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else
            {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO GUARDADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO GUARDADO";
        }
        echo json_encode($conexion->result);
    }

    public function getClientes() {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT IdCliente, Nombre FROM Cliente WHERE Eliminado IS null ORDER BY Nombre ASC";

            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaTO[] = $fila;
            }
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaTO;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode(array("error"=>$conexion->result['error'], "result"=>$txt));
    }
}