<?php
include_once 'conexion.php';
class Proveedor {
    private $id;
    private $nombre;
    private $direccion;
    private $telefono;
    private $representante;
    private $email;
    private $rfc;
    private $diasCredito;
    private $limiteCredito;
    
    public function __construct() {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->direccion = NULL;
        $this->telefono = NULL;
        $this->representante = NULL;
        $this->email = NULL;
        $this->rfc = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $nombre_,
                            $direccion_,
                            $telefono_,
                            $representante_,
                            $email_,
                            $rfc_,
                            $diasCredito_,
                            $limiteCredido_) {
        $this->id = $id_;
        $this->nombre = $nombre_;
        $this->direccion = $direccion_;
        $this->telefono = $telefono_;
        $this->representante = $representante_;
        $this->email = $email_;
        $this->rfc = $rfc_;
        $this->diasCredito = $diasCredito_;
        $this->limiteCredito = $limiteCredido_;
    }

    public function inserta() {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        $conexion->existe('Proveedor', 'Nombre', "'". $this->nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE PROVEEDOR REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            $conexion->obtenerNuevoIdTabla('Proveedor')['result'];
            $nueviId = $conexion->result['result'];

            if($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO Proveedor
                (IdProveedor,
                Nombre,
                Direccion,
                Telefono,
                Representante,
                Email,
                Rfc,
                DiasCredito,
                LimiteCredito)
                VALUES
                (". $nueviId .",
                '$this->nombre',
                '$this->direccion',
                '$this->telefono',
                '$this->representante',
                '$this->email',
                '$this->rfc',
                '$this->diasCredito',
                '$this->limiteCredito');";

                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO INSERTADO';
                    $conexion->result['Id'] = $nueviId;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                    $conexion->result['Id'] = -1;
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
    
    public function baja($idProv) {
        $conexion = new Conexion();
        $conexion->existe('HistoricoPrecioMaterial', 'IdProveedor', $idProv, -1);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "EL PROVEEDOR YA HA COTIZADO MATERIALES.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Proveedor SET Eliminado = now() WHERE IdProveedor = ". $idProv;

                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
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
        $conexion->existe('Proveedor', 'Nombre', "'". $this->nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE PROVEEDOR REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        $conexion->existe('Proveedor', 'Rfc', "'". $this->rfc ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "RFC DE PROVEEDOR REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Proveedor SET Nombre = '$this->nombre', Direccion = '$this->direccion', Telefono = '$this->telefono', Representante = '$this->representante', Email = '$this->email', Rfc = '$this->rfc', DiasCredito = '$this->diasCredito', LimiteCredito = '$this->limiteCredito' WHERE IdProveedor = ".$this->id;

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
        echo json_encode($conexion->result);
    }
    
    public function actualizaTotalProponer($idProveedor, $estado, $valorFactura) {
        $conexion = new Conexion();
        try {
            if($conexion->abrirBD() != NULL) {
                if($estado == 0) {
                    $query = "UPDATE Proveedor SET TotalPropuesto = TotalPropuesto - ". $valorFactura ." WHERE IdProveedor = ". $idProveedor;
                }
                else if($estado == 1) {
                    $query = "UPDATE Proveedor SET TotalPropuesto = TotalPropuesto + ". $valorFactura ." WHERE IdProveedor = ". $idProveedor;
                }
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'PROPUESTA REALIZADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "PROPUESTA NO REALIZADA, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "PROPUESTA NO REALIZADA, ERROR CONEXION BD";
        }
        echo json_encode($conexion->result);
    }
    
    public function actualizaTotalAutorizar($idProveedor, $estado, $valorFactura) {
        $conexion = new Conexion();

        try {
            if( $conexion->abrirBD() != NULL) {
                if($estado == 0) {
                    $query = "UPDATE Proveedor SET TotalAutorizado = TotalAutorizado - ". $valorFactura ." WHERE IdProveedor = ".$idProveedor;
                }
                else if($estado == 1) {
                    $query = "UPDATE Proveedor SET TotalAutorizado = TotalAutorizado + ". $valorFactura ." WHERE IdProveedor = ". $idProveedor;
                }
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'AUTORIZACIÓN REALIZADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "AUTORIZACIÓN NO REALIZADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "AUTORIZACIÓN NO REALIZADA, ERROR CONEXION BD";
        }
        echo json_encode($conexion->result);
    }
    //Regresa un listado (json) de proveedores
    //Cada registro contiene:
    //Todos los datos del proveedor
    public function listadoProveedores() {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Proveedor WHERE Eliminado IS NULL;";
            $result = mysqli_query($conexion->mysqli, $query);

            while($fila = $result->fetch_assoc()) {
                $listaMP[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMP;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getProveedorFilter($busqueda) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                $query = "SELECT * FROM Proveedor WHERE Eliminado IS NULL AND Nombre like '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT * FROM Proveedor WHERE Eliminado IS NULL LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();

            while($row = mysqli_fetch_array($result)) {
                $data[] = array("id" => $row['IdProveedor'], "text" => $row['Nombre']);
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
    
    public function getProveedorReqFilter($busqueda) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                $query = "SELECT DISTINCT(IdProveedor) AS IdProveedor, Proveedor FROM VistaRequisConcentradoProv WHERE Proveedor LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT DISTINCT(IdProveedor) AS IdProveedor, Proveedor FROM VistaRequisConcentradoProv LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                if($row['IdProveedor'] != -1)
                    $data[] = array("id" => $row['IdProveedor'], "text" => $row['Proveedor']);
            }

            $data[] = array("id" => -1, "text" => "Stock");
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
	
	public function getProveedorReqFilterxkilo($busqueda) {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD() != NULL) {
            if($busqueda!=NULL) {
                $query = "SELECT DISTINCT Proveedor.IdProveedor, Proveedor.Nombre FROM Proveedor INNER JOIN PrecioxKilo ON Proveedor.IdProveedor = PrecioxKilo.IdProveedor WHERE Proveedor.Nombre LIKE '%". $busqueda ."%' GROUP BY Proveedor.IdProveedor LIMIT 5;";
            }
            else {
                $query = "SELECT DISTINCT Proveedor.IdProveedor, Proveedor.Nombre FROM Proveedor INNER JOIN PrecioxKilo ON Proveedor.IdProveedor = PrecioxKilo.IdProveedor GROUP BY Proveedor.IdProveedor";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                if($row['IdProveedor'] != -1)
                    $data[] = array("id" => $row['IdProveedor'], "text" => $row['Nombre']);
            }

            $data[] = array("id" => -1, "text" => "Stock");
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
    
    public function getProveedorReqEspecialesFilter($busqueda) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                $query = "SELECT DISTINCT(IdProveedor) AS IdProveedor, Proveedor FROM VistaRequisConcentradoEspecial WHERE Proveedor LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT DISTINCT(IdProveedor) AS IdProveedor, Proveedor FROM VistaRequisConcentradoEspecial LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();

            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id" => $row['IdProveedor'], "text" => $row['Proveedor']);
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
    
    public function getCotizadoresFilter($busqueda, $idMaterial) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                $query = "SELECT DISTINCT Cotizador FROM ProveedorByMaterial WHERE Eliminado IS NULL AND IdMaterial = ". $idMaterial ." AND Cotizador LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT DISTINCT Cotizador FROM ProveedorByMaterial WHERE Eliminado IS NULL AND IdMaterial = ". $idMaterial ." LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            
            while($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['Cotizador'] = $row['Cotizador'];
                $data[] = array("id" => $mat['Cotizador'], "text" => $mat['Cotizador']);
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
    //listado para reportes, no json
    public function listadoProveedoresNoJSON() {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Proveedor WHERE Eliminado IS NULL;";

            $result = mysqli_query($conexion->mysqli, $query);

            while($fila = $result->fetch_assoc()) {
                $listaMP[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMP;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        return $conexion->result['result'];
    }
}