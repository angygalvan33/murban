<?php
include_once 'conexion.php';

class Obra {
    private $id;
    private $idTipoObra;
    private $nombre;
    private $cliente;
    private $domicilio;
    private $descripcion;
    private $presupuesto;
    public $archivo;
    public $nombreArchivo;
    private $idCliente;
    private $ocFolio;
    private $ocMonto;
    private $fechaEstimadaEntrega;
    private $facturaNumero;
    private $facturaValor;
    private $facturaFecha;
    private $entregado;
    private $pagado;

    public function __construct() {
        $this->id = NULL;
        $this->idTipoObra = NULL;
        $this->nombre = NULL;
        $this->cliente = NULL;
        $this->domicilio = NULL;
        $this->descripcion = NULL;
        $this->presupuesto = NULL;
        $this->idCliente = NULL;
        $this->ocFolio = NULL;
        $this->ocMonto = NULL;
        $this->fechaEstimadaEntrega = NULL;
        $this->facturaNumero = NULL;
        $this->facturaValor = NULL;
        $this->facturaFecha = NULL;
        $this->entregado = NULL;
        $this->pagado = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $idTipoObra_,
                            $nombre_,
                            $cliente_,
                            $domicilio_,
                            $descripcion_,
                            $presupuesto_,
                            $idCliente_,
                            $ocFolio_,
                            $ocMonto_,
                            $fechaEntregaEstimada_) {
        $this->id = $id_;
        $this->idTipoObra = $idTipoObra_;
        $this->nombre = $nombre_;
        $this->cliente = $cliente_;
        $this->domicilio = $domicilio_;
        $this->descripcion = $descripcion_;
        $this->presupuesto = $presupuesto_;
        $this->idCliente = $idCliente_;
        $this->ocFolio = $ocFolio_;
        $this->ocMonto = $ocMonto_;
        $this->fechaEstimadaEntrega = $fechaEntregaEstimada_;
    }

    public function inserta() {
        $conexion = new Conexion();
        
        $conexion->existe('Obra', 'Nombre', "'". $this->nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE OBRA REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            $conexion->obtenerNuevoIdTabla('Obra')['result'];
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO Obra
                (IdObra,
                IdTipoObra,
                Nombre,
                Cliente,
                Domicilio,
                Descripcion,
                IdCliente,
                OCFolio,
                OCMonto,
                FechaEntregaEstimada,
                Archivo,
                NombreArchivo)
                VALUES
                (". $nueviId .",
                ". $this->idTipoObra .",
                '$this->nombre',
                '$this->cliente',
                '$this->domicilio',
                '$this->descripcion',
                ". $this->idCliente .",
                '$this->ocFolio',
                ". $this->ocMonto .",
                '$this->fechaEstimadaEntrega',
                '$this->archivo',
                '$this->nombreArchivo');";
                //echo "*****".$query."****";
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
    
    public function baja($idObra) {
        $conexion = new Conexion();

        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Obra SET Eliminado = NOW() WHERE IdObra = ". $idObra;

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
        $conexion->existe('Obra', 'Nombre', "'". $this->nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE OBRA REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Obra SET IdTipoObra = ". $this->idTipoObra .", Nombre = '$this->nombre', Cliente = '$this->cliente', Domicilio = '$this->domicilio', Descripcion = '$this->descripcion', IdCliente = ". $this->idCliente .", OCFolio = '$this->ocFolio', OCMonto = '$this->ocMonto', FechaEntregaEstimada = '$this->fechaEstimadaEntrega', Archivo = '$this->archivo', NombreArchivo = '$this->nombreArchivo' WHERE IdObra = ". $this->id;
                //echo "****".$query."****";
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
    
    public function getObras() {
        $conexion = new Conexion();

        if($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Obra WHERE Eliminado IS NULL;";
            $result = mysqli_query($conexion->mysqli, $query);

            while($fila = $result->fetch_assoc()) {
                $listaObras[]=$fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaObras;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getObraFilter($busqueda) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                $query = "SELECT * FROM Obra WHERE (IdObra != 2 AND IdObra != 3 AND IdObra != 5 AND IdObra != 7 AND IdObra != 8 AND IdObra != 9 AND IdObra != 10 AND IdObra != 11 AND IdObra != 12 AND IdObra != 31 AND IdObra != 32 AND IdObra != 33 AND IdObra != 35 AND IdObra != 36 AND IdObra != 50) AND Eliminado IS NULL AND Terminado = 0 AND Nombre like '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT * FROM Obra WHERE (IdObra != 2 AND IdObra != 3 AND IdObra != 5 AND IdObra != 7 AND IdObra != 8 AND IdObra != 9 AND IdObra != 10 AND IdObra != 11 AND IdObra != 12 AND IdObra != 31 AND IdObra != 32 AND IdObra != 33 AND IdObra != 35 AND IdObra != 36 AND IdObra != 50) AND Eliminado IS NULL AND Terminado = 0 LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while($row = mysqli_fetch_array($result)) {
                $data[] = array("id"=>$row['IdObra'], "text"=>$row['Nombre']);
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
    
    public function getObraFilterSalida($busqueda) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                $query = "SELECT * FROM Obra WHERE Eliminado IS NULL AND IdObra != -1 AND Nombre LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT * FROM Obra WHERE Eliminado IS NULL AND IdObra != -1 LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id" => $row['IdObra'], "text" => $row['Nombre']);
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
    
    public function getObraById($idObra) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Obra WHERE Eliminado IS NULL AND IdObra = ". $idObra;

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc()) {
                $listaObras[]=$fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaObras;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode($txt);
    }
    
    public function facturar($idObra, $facturaNumero, $facturaValor, $facturaFecha) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Obra SET FacturaNumero = '$facturaNumero', FacturaValor = '$facturaValor', FacturaFecha = '$facturaFecha' WHERE IdObra = ". $idObra;
                
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
    
    public function listarPagosObra($idObra) {
        $conexion = new Conexion();
        $lista = array();
        
        if($conexion->abrirBD() != NULL) {
            $query = "CALL ObraDetalleCobros(". $idObra .");";

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc()) {
                $lista[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        echo json_encode($conexion->result['result']);
    }
    
    public function PagarObra($idObra_, $IdMetodoCobro_, $tipoCobro_, $cantidad_, $concepto_, $deuda_, $fecha_) {
        $conexion = new Conexion();
        $band_query_exito = 1;

        try {
            if($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if($cantidad_ == $deuda_) {
                    $tipoCobro_ = 'Liquidación';
                }
                
                $query = "INSERT INTO ObraDetalleCobros
                (IdObra,
                IdMetodoCobro,
                TipoDC,
                Monto,
                FechaCobro,
                Concepto)
                VALUES
                (". $idObra_ .",
                '$IdMetodoCobro_',
                '$tipoCobro_',
                ". $cantidad_ .",
                '$fecha_',
                '$concepto_');";

                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = "PAGO REGISTRADO";
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
    
    public function getTotalCobrarSinFactura() {//No se usa
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT SUM(OCMonto) AS Cobro FROM VistaCuentasCobrarGeneral WHERE FacturaNumero IS NULL";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $fila = $result->fetch_assoc();
            $conexion->cerrarBD();
            $conexion->result['result'] = $fila['Cobro'];
        }
        else {
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getTotalCobrarConFactura() {//No se usa
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT SUM(CobroRestante) AS CobroRestante FROM VistaCuentasCobrarGeneral WHERE FacturaNumero IS NOT NULL";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $fila = $result->fetch_assoc();
            $conexion->cerrarBD();
            $conexion->result['result'] = $fila['CobroRestante'];
        }
        else {
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getTotalCobrarConSinFactura() {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "CALL SPCuentasPorCobrarTotales();";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $fila = $result->fetch_assoc();
            $conexion->cerrarBD();
            $conexion->result['result'] = $fila;
        }
        else {
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getObraReqFilter($busqueda, $band) {
        $conexion = new Conexion();
        if($conexion->abrirBD() != NULL) {
            if($busqueda != NULL) {
                if($band == 0)
                    $query = "SELECT DISTINCT Obra.* FROM Obra JOIN VistaRequisicionesConsulta ON VistaRequisicionesConsulta.IdObra = Obra.IdObra WHERE Obra.Eliminado IS NULL AND Terminado = 0 AND Obra.Nombre LIKE '%". $busqueda ."%' LIMIT 5;";
                else
                    $query = "SELECT DISTINCT Obra.* FROM Obra JOIN VistaRequisicionesxProyectoxKilo ON VistaRequisicionesxProyectoxKilo.IdObra = Obra.IdObra WHERE Obra.Eliminado IS NULL AND Terminado = 0 AND Obra.Nombre LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                if($band == 0)
                    $query = "SELECT DISTINCT Obra.* FROM Obra JOIN VistaRequisicionesConsulta ON VistaRequisicionesConsulta.IdObra = Obra.IdObra WHERE Obra.Eliminado IS NULL AND Terminado = 0 LIMIT 5;";
                else
                    $query = "SELECT DISTINCT Obra.* FROM Obra JOIN VistaRequisicionesxProyectoxKilo ON VistaRequisicionesxProyectoxKilo.IdObra = Obra.IdObra WHERE Obra.Eliminado IS NULL AND Terminado = 0 LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id" => $row['IdObra'], "text" => $row['Nombre']);
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

    public function getObraReqEspFilter($busqueda) {
        $conexion = new Conexion();

        if($conexion->abrirBD() != NULL) {
            if($busqueda != null)
                $query = "SELECT DISTINCT Obra.* FROM Obra JOIN VistaRequisicionesEspxProyecto ON VistaRequisicionesEspxProyecto.IdProyecto = Obra.IdObra WHERE Obra.Eliminado IS NULL AND Terminado = 0 AND Obra.Nombre LIKE '%". $busqueda ."%' LIMIT 5;";
            else
                $query = "SELECT DISTINCT Obra.* FROM Obra JOIN VistaRequisicionesEspxProyecto ON VistaRequisicionesEspxProyecto.IdProyecto = Obra.IdObra WHERE Obra.Eliminado IS NULL AND Terminado = 0 LIMIT 5;";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();

            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id"=>$row['IdObra'], "text"=>$row['Nombre']);
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

    public function sumarGastoObra($idObra_, $idMaterial_, $cantidad_, $nombreMaterial_) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        $estadopago = 0;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                if (intval($idMaterial_) == -1 && $nombreMaterial_ != null) {
                    $querySelect = "SELECT * FROM Inventario WHERE Nombre = '". $nombreMaterial_ ."' AND IdObra = -1 AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                }
                else {
                    $querySelect = "SELECT * FROM Inventario WHERE IdMaterial = ". $idMaterial_ ." AND IdObra = -1 AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                }

                if (!mysqli_query($conexion->mysqli, $querySelect)) {
                    $band_query_exito = 0;
                }
                else {
                    $result = mysqli_query($conexion->mysqli, $querySelect);
                    $row = mysqli_fetch_array($result);
                    //$cantRegistro = $row['Cantidad'];
                    $idInventario = $row['IdInventario'];
                    $idOrdenCompra = $row['IdOrdenCompra'];
                }

                if ($idOrdenCompra != -1) {
                    $querySelect = "SELECT Pagada FROM OrdenCompra WHERE IdOrdenCompra = ". $idOrdenCompra;
                    //echo "***".$querySelect."***";
                    if (!mysqli_query($conexion->mysqli, $querySelect)) {
                        $band_query_exito = -1;
                    }
                    else {
                        $result = mysqli_query($conexion->mysqli, $querySelect);
                        $row = mysqli_fetch_array($result);
                        $pagada = $row['Pagada'];
                        $estadopago = ($pagada == 1) ? 2 : 1;
                    }
                }

                $queryInsert = "INSERT INTO ObraGasto (IdObra, IdOrdenCompra, Tipo, EstadoPago, IdMaterial, NombreMaterial, Cantidad, Total, FechaMovimiento)
                    SELECT ". $idObra_ .", IdOrdenCompra, 'Orden de Compra', ". $estadopago .", IdMaterial, Nombre, ". $cantidad_ .", (PrecioUnitario * ". $cantidad_ ."), NOW() FROM Inventario WHERE IdInventario = ". $idInventario;
                //echo "********".$queryInsert."********";
                if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                    $band_query_exito = -2;
                }

                if ($band_query_exito > 0) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL REDUCIDO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "MATERIAL NO REDUCIDO, ERROR CONEXION BD";
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REDUCCIÓN DE MATERIAL SIN ÉXITO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "MATERIAL NO REDUCIDO";
        }
        //echo json_encode($conexion->result);
    }

    public function restarGastoObra($idObra_, $idMaterial_, $cantidad_, $nombreMaterial_) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if (intval($idMaterial_) == -1) {
                    $query = "SELECT SUM(Cantidad) AS Cantidad FROM ObraGasto WHERE NombreMaterial = '". $nombreMaterial_ ."' AND IdObra = ". $idObra_ ." AND Eliminado IS NULL";
                }
                else {
                    $query = "SELECT SUM(Cantidad) AS Cantidad FROM ObraGasto WHERE IdMaterial = ". $idMaterial_ ." AND IdObra = ". $idObra_ ." AND Eliminado IS NULL";
                }

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $cantSistema = $row['Cantidad'];

                if ($cantSistema >= $cantidad_) {
                    do {
                        if (intval($idMaterial_) == -1) {
                            $subQuery = "SELECT * FROM ObraGasto WHERE NombreMaterial LIKE '". $nombreMaterial_ ."' AND IdObra = ". $idObra_ ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }
                        else {
                            $subQuery = "SELECT * FROM ObraGasto WHERE IdMaterial = ". $idMaterial_ ." AND IdObra = ". $idObra_ ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }
                        
                        $result = mysqli_query($conexion->mysqli, $subQuery);
                        
                        if (!mysqli_query($conexion->mysqli, $subQuery)) {
                            $band_query_exito = 0;
                            break;
                        }
                        else {
                            $row = mysqli_fetch_array($result);
                            $cantRegistro = $row['Cantidad'];
                            $idObraGasto = $row['IdObraGasto'];
                        }
                        
                        if (floatval($cantRegistro) > $cantidad_) {
                            $cantRegistro = $cantRegistro - $cantidad_;
                            
                            $queryUpdate = "UPDATE ObraGasto SET Cantidad = ". $cantRegistro ." WHERE IdObraGasto = ". $idObraGasto;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -2;
                                break;
                            }
                            
                            $cantidad_ = 0;
                        }
                        else if (floatval($cantRegistro) == $cantidad_) {
                            $queryUpdate = "UPDATE ObraGasto SET Eliminado = now() WHERE IdObraGasto = ". $idObraGasto;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -3;
                                break;
                            }
                            $cantidad_ = 0;
                        }
                        else {
                            $queryUpdate = "UPDATE ObraGasto SET Eliminado = now() WHERE IdObraGasto = ". $idObraGasto;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -4;
                                break;
                            }

                            $cantidad_ = $cantidad_ - $cantRegistro;
                        }
                    } while(floatval($cantidad_) > 0);

                    if ($band_query_exito > 0) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'MATERIAL TRASPASADO';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = "CodigoError: (". $query .") ". $conexion->mysqli->error;
                    }
                }
                else {
                    //Eliminar todos los registros para que no deje nada en el proyecto
                    if (intval($idMaterial_) == -1) {
                        $subQuery = "UPDATE ObraGasto SET Eliminado = now() WHERE NombreMaterial LIKE '". $nombreMaterial_ ."' AND IdObra = ". $idObra_ ." AND Eliminado IS NULL";
                    }
                    else {
                        $subQuery = "UPDATE ObraGasto SET Eliminado = now() WHERE IdMaterial = ". $idMaterial_ ." AND IdObra = ". $idObra_ ." AND Eliminado IS NULL";
                    }
                    
                    $result = mysqli_query($conexion->mysqli, $subQuery);
                    
                    if (!mysqli_query($conexion->mysqli, $subQuery)) {
                        $band_query_exito = -5;
                    }
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REDUCCIÓN DE MATERIAL SIN ÉXITO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "MATERIAL NO RECIBIDO";
        }
        //echo json_encode($conexion->result);
    }

    public function cambiarEstadoTerminada($idObra_, $estatus_) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Obra SET Terminado = ". $estatus_ ." WHERE IdObra = ". $idObra_;
                
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

    function agregarProducto($idObra_, $idProducto_, $cantidad_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO ObraProducto
                (IdObra,
                IdArticulo,
                Cantidad)
                VALUES
                (". $idObra_ .",
                ". $idProducto_ .",
                ". $cantidad_ .");";

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
        //echo json_encode($conexion->result);
    }

    function eliminarDeProyecto($idRequisicion_) {
        $conexion = new Conexion();
        $comodin = new Comodin();

        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE ObraProducto SET IdUsuarioElimina = ". $comodin->idUsuarioSession() .", Eliminado = NOW() WHERE IdRequisicion = ". $idRequisicion_;
                echo "****".$query."****";
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
        //echo json_encode($conexion->result);
    }
}