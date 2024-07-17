<?php
include_once 'conexion.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of historicoPrecioMateriales
 *
 * @author rafael
 */
class HistoricoPrecioMateriales {
    private $id;
    private $idMaterial;
    private $idProveedor;
    private $precio;
    private $iva;
    private $cotizador;
    private $moneda;
    private $precioEnDolares;
    
    public function __construct() {
        $this->id = NULL;
        $this->idMaterial = NULL;
        $this->idProveedor = NULL;
        $this->precio = NULL;
        $this->iva = NULL;
        $this->cotizador = NULL;
        $this->dolar = 0;
        $this->precioEnDolares = 0;
    }
    
    public function llenaDatos(
                            $id_,
                            $idMaterial_,
                            $idProveedor_,
                            $precio_,
                            $iva_,
                            $cotizador_,
                            $moneda,
                            $precioEnDolares) {
        $this->id = $id_;
        $this->idMaterial = $idMaterial_;
        $this->idProveedor = $idProveedor_;
        $this->precio = $precio_;
        $this->iva = $iva_;
        $this->cotizador = $cotizador_;
        $this->moneda = $moneda;
        $this->precioEnDolares = $precioEnDolares;
    }
    
    public function guardarPrecio($accion) {
        $conexion = new Conexion();
        
        try {
            $conexion->obtenerNuevoIdTabla('HistoricoPrecioMaterial');
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "CALL InsertaMaterialProveedor('$accion', ". $nueviId .", ". $this->idMaterial .", ". $this->idProveedor .", 0, ". $this->precio .", ". $this->iva .", '$this->cotizador', '$this->moneda', ". $this->precioEnDolares .");";
                //echo $query."****";
                $result = mysqli_query($conexion->mysqli, $query);
                $fila = $result->fetch_assoc();

                $conexion->result['error'] = $fila['error_'];
                $conexion->result['result'] = $fila['msg'];
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
        }
        echo json_encode($conexion->result);
    }
    //Se creó una copia, hace lo mismo que la funcion guardarPrecio pero esta no hace el echo
    //se utiliza en ordenes de compra en la seccipon de requisiciones especiales
    public function guardarPrecioDesdeOC($accion, &$result) {
        $conexion = new Conexion();
        
        try {
            $conexion->obtenerNuevoIdTabla('HistoricoPrecioMaterial');
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "CALL InsertaMaterialProveedor('$accion', ". $nueviId .", ". $this->idMaterial .", ". $this->idProveedor .", 0, ". $this->precio .", ". $this->iva .", '$this->cotizador', '$this->moneda', ". $this->precioEnDolares .");";
                //echo $query.'******';
                $result = mysqli_query($conexion->mysqli, $query);
                $fila = $result->fetch_assoc();

                $conexion->result['error'] = $fila['error_'];
                $conexion->result['result'] = $fila['msg'];
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
        }
        $result =  $conexion->result ;
    }
    
    public function bajaMaterialByProveedor($idProv, $idMat) {
        $conexion = new Conexion();

        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE HistoricoPrecioMaterial SET Eliminado = now() WHERE IdProveedor = ". $idProv ." AND IdMaterial = ". $idMat;
                echo $conexion->result['error'];
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTROS ELIMINADOS';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTROS NO ELIMINADOS, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTROS NO ELIMINADOS";
        }

        echo json_encode($conexion->result);
    }
    
    /*public function baja($idHPM)
    {
        $conexion = new Conexion();
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE HistoricoPreciosMateriales
                    SET Eliminado = now()
                    WHERE IdHistoricoPreciosMateriales = ".$idHPC;
                if( mysqli_query($conexion->mysqli, $query) == TRUE)
                {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO ELIMINADO';
                }
                else
                {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else
            {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO ELIMINADO, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) 
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO ELIMINADO";
        }
        echo json_encode($conexion->result);
        //return $conexion->result;
    }
    
    public function editar()
    {
        $conexion = new Conexion();
        
        try 
        {

            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE HistoricoPreciosMateriales
                SET
                Precio = '$this->precio',
                Cotizador = '$this->cotizador'
                WHERE IdHistoricoPreciosVentas = ".$this->id;
                
                
                
                if( mysqli_query($conexion->mysqli, $query) == TRUE)
                {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO MODIFICADO';
                }
                else
                {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else
            {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO MODIFICADO, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) 
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO MODIFICADO";
        }
        echo json_encode($conexion->result);
    }*/
    
    public function getHistoricoCompleto() {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM HistoricoPrecioMaterial WHERE Eliminado IS NULL;";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaHPC[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaHPC;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getHistoricoMateriales($idProveedor_, $idMaterial_) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM HistoricoPrecioMaterial WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor_ ." AND IdMaterial = ". $idMaterial_ .";";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaHPC[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaHPC;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
	
	public function getPrecioxKiloMateriales($idProveedor_, $idMaterial_) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM VistaPrecioxKilo WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor_ ." AND IdMaterial = ". $idMaterial_ .";";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaHPC[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaHPC;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
	
	public function addPrecioxKilo($Precio, $Iva, $Cotizador, $Moneda, $PrecioDolares, $IdProveedor) {
		$conexion = new Conexion();
        
        try {
            $conexion->obtenerNuevoIdTabla('PrecioxKilo');
            $nueviId = $conexion->result['result'];
            
			if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO PrecioxKilo (IdPrecioxKilo, Precio, Cotizador, Iva, Moneda, PrecioDolares, IdProveedor) VALUES (". $nueviId .", ". $Precio .", '". $Cotizador ."', ". $Iva .", '". $Moneda ."', ". $PrecioDolares .", ". $IdProveedor .");" ;

				if (!mysqli_query($conexion->mysqli, $query)) {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "PRECIO NO INSERTADO-". $query;
					$conexion->result['IdPrecioxKilo'] = $nueviId;
                }
				else {
					$conexion->result['error'] = 0;
                    $conexion->result['result'] = 'PRECIO INSERTADO';
                    $conexion->result['IdPrecioxKilo'] = $nueviId;
				}
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
				$conexion->result['IdPrecioxKilo'] = -1;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
			$conexion->result['IdPrecioxKilo'] = -1;
        }
        return $conexion->result ;
	}
	
	public function asignaPrecioxKilo($IdProveedor, $IdMaterial, $IdPrecio) {
		$conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO PrecioxKiloMat (IdPrecioxKilo, IdProveedor, IdMaterial) VALUES (". $IdPrecio .", ". $IdProveedor .", ". $IdMaterial .");";

				if (!mysqli_query($conexion->mysqli, $query)) {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "PRECIO NO INSERTADO-". $query;
                }
				else {
					$conexion->result['error'] = 0;
                    $conexion->result['result'] = 'PRECIO INSERTADO';
				}
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
        }

        return $conexion->result;
	}
	
	public function bajaPrecioxKilo($idProv, $idMat) {
        $conexion = new Conexion();

        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "DELETE FROM PrecioxKiloMat WHERE IdProveedor = ". $idProv ." AND IdMaterial = ". $idMat;
                echo $conexion->result['error'];

                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTROS ELIMINADOS';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 2;
                $conexion->result['result'] = "REGISTROS NO ELIMINADOS, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTROS NO ELIMINADOS";
        }

        echo json_encode($conexion->result);
    }
	
	public function EditPrecioxKilo($idprecio, $idprov, $iva, $moneda, $precio) {
        $conexion = new Conexion();

        try {
			if ($iva == 1)
				$precio = $precio * 1.16;
			
            $queryp = "UPDATE PrecioxKilo SET Precio = ". $precio ." WHERE IdPrecioxKilo = ". $idprecio ." AND IdProveedor = ". $idprov;

			$queryd = "UPDATE PrecioxKilo SET PrecioDolares = ". $precio ." WHERE IdPrecioxKilo = ". $idprecio ." AND IdProveedor = ". $idprov;

            if ($conexion->abrirBD() != NULL) {
                if ($moneda == 'P')
                    $query = $queryp;
				else
					$query = $queryd;

                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SE HA ACTUALIZADO CORRECTAMENTE EL PRECIO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "NO SE HA PODIDO ACTUALIZAR EL PRECIO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "NO SE HA PODIDO ACTUALIZAR EL PRECIO";
        }

        return $conexion->result;
    }
	
	public function getCotizadoresFilterKg($busqueda, $idProveedor) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT DISTINCT Cotizador FROM PrecioxKilo WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." AND Cotizador LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT DISTINCT Cotizador FROM PrecioxKilo WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            
            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['Cotizador'] = $row['Cotizador'];
                $data[] = array("id"=>$mat['Cotizador'], "text"=>$mat['Cotizador']);
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
	
	public function getpreciosFilterKg($busqueda, $idProveedor) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT IdPrecioxKilo,
                    (CASE WHEN PrecioxKilo.Moneda = 'P' THEN Concat('$', Precio, '-MXN')
                    WHEN PrecioxKilo.Moneda = 'D' THEN Concat('$', PrecioDolares, '-USD') END) AS Precio
                    FROM PrecioxKilo WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." AND Precio LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT IdPrecioxKilo,
                    (CASE WHEN PrecioxKilo.Moneda = 'P' THEN Concat('$', Precio, '-MXN')
                    WHEN PrecioxKilo.Moneda = 'D' THEN Concat('$', PrecioDolares, '-USD') END) AS Precio
                    FROM PrecioxKilo WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            
            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['Precio'] = $row['Precio'];
                $data[] = array("id" => $row['IdPrecioxKilo'], "text" => $mat['Precio']);
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
	
	public function getPrecio_ByIdPrecioProv($idPrecioxkilo, $idProveedor) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM PrecioxKilo WHERE Eliminado IS NULL AND IdPrecioxKilo = ". $idPrecioxkilo ." AND IdProveedor = ". $idProveedor .";";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaprecio[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaprecio;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
		$txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode(array("error"=>$conexion->result['error'],"result"=>$txt));
    }
}