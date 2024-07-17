<?php
include_once 'conexion.php';
include_once 'material.php';
include_once 'usuario.php';
include_once "comodin.php";

class Inventario {
    private $id;
    private $idOrdenCompra;
    private $idProveedor;
    private $idObra;
    private $idMaterial;
    private $nombre;
    private $cantidad;
    private $precioUnitario;
    
    public function __construct() {
        $this->id = NULL;
        $this->idOrdenCompra = NULL;
        $this->idProveedor = NULL;
        $this->idObra = NULL;
        $this->idMaterial = NULL;
        $this->nombre = NULL;
        $this->cantidad = NULL;
        $this->precioUnitario = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $idOrdenCompra_,
                            $idProveedor_,
                            $idObra_,
                            $idMaterial_,
                            $nombre_,
                            $cantidad_,
                            $precioUnitario_) {
        $this->id = $id_;
        $this->idOrdenCompra = $idOrdenCompra_;
        $this->idProveedor = $idProveedor_;
        $this->idObra = $idObra_;
        $this->idMaterial = $idMaterial_;
        $this->nombre = $nombre_;
        $this->cantidad = $cantidad_;
        $this->precioUnitario = $precioUnitario_;
        
        if ($this->idMaterial != -1) {
            $mat = new Material();
            $nomb = $mat->getNombreMaterialByID($this->idMaterial);
            $this->nombre = $nomb;
        }
    }
    
    public function inserta() {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO Inventario
                    (IdOrdenCompra,
                    IdProveedor,
                    IdObra,
                    IdMaterial,
                    Nombre,
                    Cantidad,
                    PrecioUnitario)
                    VALUES
                    (". $this->idOrdenCompra .",
                    ". $this->idProveedor .",
                    ". $this->idObra .",
                    ". $this->idMaterial .",
                    '$this->nombre',
                    ". $this->cantidad .",
                    ". $this->precioUnitario .");";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "INSERT INTO InventarioMovimiento
                (IdMaterial,
                NombreMaterial,
                IdObraOrigen,
                IdObraDestino,
                TipoMovimiento,
                IdOrdenCompra,
                IdUsuario,
                Cantidad,
                Comentario,
                IdUsuarioRegistro)
                VALUES
                (". $this->idMaterial .",
                '$this->nombre',
                ". $this->idObra .",
                ". $this->idObra .",
                'ENTRADA',
                ". $this->idOrdenCompra .",
                1,
                ". $this->cantidad .",
                'INVENTARIOS INICIALES',
                ". $comodin->idUsuarioSession() .");";
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL RECIBIDO';
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
                $conexion->result['result'] = "MATERIAL NO RECIBIDO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) 
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "MATERIAL NO RECIBIDO";
        }
        echo json_encode($conexion->result);
    }
    
    public function bajaInicial($idMat)
    {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Inventario SET Eliminado = NOW() WHERE IdMaterial = ". $idMat ." AND IdObra = -1";
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
    
    public function editarInicial() {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Inventario SET Cantidad = ". $this->cantidad .", PrecioUnitario = ". $this->precioUnitario ." WHERE IdMaterial = ". $this->idMaterial ." AND IdObra = -1";
                
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
    
    public function insertaEntrada() {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO Inventario
                (IdOrdenCompra,
                IdProveedor,
                IdObra,
                IdMaterial,
                Nombre,
                Cantidad,
                PrecioUnitario)
                VALUES
                (". $this->idOrdenCompra .",
                ". $this->idProveedor .",
                ". $this->idObra .",
                ". $this->idMaterial .",
                '$this->nombre',
                ". $this->cantidad .",
                ". $this->precioUnitario .");";
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "INSERT INTO InventarioMovimiento
                (IdMaterial,
                NombreMaterial,
                IdObraOrigen,
                IdObraDestino,
                TipoMovimiento,
                IdOrdenCompra,
                IdUsuario,
                Cantidad,
                Comentario,
                IdUsuarioRegistro)
                VALUES
                (". $this->idMaterial .",
                '$this->nombre',
                ". $this->idObra .",
                ". $this->idObra .",
                'ENTRADA',
                ". $this->idOrdenCompra .",
                ". $comodin->idUsuarioSession() .",
                ". $this->cantidad .",
                'ENTRADA DE MATERIAL',
                ". $comodin->idUsuarioSession() .")";
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL RECIBIDO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "MATERIAL NO RECIBIDO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "MATERIAL NO RECIBIDO";
        }
        echo json_encode($conexion->result);
    }
    
    public function incrementaRecibido($idDetalleOC, $cant) {
        $hoyf = date('Y-m-d H:i:s');
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "UPDATE DetalleOrdenCompra SET Recibido = Recibido + ". $cant .", FechaRecepcion = '". $hoyf ."' WHERE IdDetalleOrdenCompra = ". $idDetalleOC;

            mysqli_query($conexion->mysqli, $query);
            
            $query = "SELECT IdOrdenCompra,IdRequisicionDetalle FROM DetalleOrdenCompra WHERE IdDetalleOrdenCompra = ". $idDetalleOC;

            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $idOrdenCompra = $result["IdOrdenCompra"];
            $idReqDetalle = $result["IdRequisicionDetalle"];
            
            $query = "SELECT * FROM DetalleOrdenCompra WHERE IdOrdenCompra = ". $idOrdenCompra;

            $result = mysqli_query($conexion->mysqli, $query);

            $completa = true;
            while ($row = $result->fetch_assoc()) {
                if (floatval($row["Cantidad"]) != floatval($row["Recibido"])) {
                    $completa = false;
                }
            }

            if ($completa) {
                $query = "UPDATE OrdenCompra SET IdEstadoOC = 3, Recibida = 1 WHERE IdOrdenCompra = ". $idOrdenCompra;
                mysqli_query($conexion->mysqli, $query);
            }
            
            if ($idReqDetalle > 0 && $idReqDetalle != NULL) {
                $query = "UPDATE RequisicionDetalle SET CantidadRecibida = CantidadRecibida + ". $cant .", FechaIngr = '". $hoyf ."' WHERE IdRequisicionDetalle = ". $idReqDetalle;

                $result = mysqli_query($conexion->mysqli, $query);
                
                $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicionDetalle = ". $idReqDetalle;

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $CantidadPedida = $row["CantidadPedida"];
                $CantidadRecibida = $row["CantidadRecibida"];
                $IdReq = $row['IdRequisicion'];
                
                if (floatval($CantidadPedida) == floatval($CantidadRecibida)) {
                    $query = "UPDATE RequisicionDetalle SET EdoRecibida = 1, FechaIngr = '". $hoyf ."' WHERE IdRequisicionDetalle = ". $idReqDetalle;

                    $result = mysqli_query($conexion->mysqli, $query);
                }
            }
            $conexion->cerrarBD();
        }
    }
    
    public function salidaMaterial($idObra, $idMaterial, $nombreMaterial, $precioUnitario, $cantidad, $idPersonal, $descripcion, $idObraSalida) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $cantMovimiento = $cantidad;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if (intval($idMaterial) == -1) {
                    $subQuery = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE Nombre = '". $nombreMaterial ."' AND PrecioUnitario = ". $precioUnitario ." AND IdObra = ". $idObra ." AND Eliminado IS NULL";
                }
                else {
                    $subQuery = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL";
                }

                if (!mysqli_query($conexion->mysqli, $subQuery)) {
                    $band_query_exito = 0;
                }

                $result = mysqli_query($conexion->mysqli, $subQuery);
                $row = mysqli_fetch_array($result);
                $cantSistema = $row['Cantidad'];
                
                if ($cantSistema >= $cantidad) {
                    do {
                        if (intval($idMaterial) == -1) {
                            $querySelect = "SELECT * FROM Inventario WHERE Nombre = '". $nombreMaterial ."' AND PrecioUnitario = ". $precioUnitario ." AND IdObra = ". $idObra ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }
                        else {
                            $querySelect = "SELECT * FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }

                        if (!mysqli_query($conexion->mysqli, $querySelect)) {
                            $band_query_exito = -1;
                            break;
                        }
                        
                        $result = mysqli_query($conexion->mysqli, $querySelect);
                        $row = mysqli_fetch_array($result);
                        $cantRegistro = $row['Cantidad'];
                        $idInventario = $row['IdInventario'];
                        $idOrdenCompra = $row['IdOrdenCompra'];
                        $nombreMaterial = $row["Nombre"];
                        
                        if (floatval($cantRegistro) > $cantidad) {
                            $cantidad = floatval($cantRegistro) - floatval($cantidad);

                            $queryUpdate = "UPDATE Inventario SET Cantidad = ". $cantidad ." WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -2;
                                break;
                            }
                            $cantidad = 0;
                        }
                        else if (floatval($cantRegistro) == $cantidad) {
                            $queryUpdate = "UPDATE Inventario SET Eliminado = now() WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -3;
                                break;
                            }
                            $cantidad = 0;
                        }
                        else {
                            $queryUpdate = "UPDATE Inventario SET Eliminado = now() WHERE IdInventario = ". $idInventario;
                            
                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -4;
                                break;
                            }
                            
                            $cantidad = floatval($cantidad) - floatval($cantRegistro);
                        }
                    } while (floatval($cantidad) > 0);
                }

                $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial, IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario,IdUsuarioRegistro)
                VALUES (". $idMaterial .", '$nombreMaterial', ". $idObra .", ". $idObraSalida .", 'SALIDA', -1, ". $idPersonal .", ". $cantMovimiento .", '$descripcion', ". $comodin->idUsuarioSession() .")";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -5;
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SALIDA REGISTRADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "SALIDA NO REGISTRADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "SALIDA NO REGISTRADA";
        }
        echo json_encode($conexion->result);
    }
    
    public function requisicionMaterialInventario($idObra, $idMaterial, $idPersonal) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query= "SELECT count(*) AS NumRegActivos FROM RequisicionDetalle WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND EdoRecibida = 0 AND EdoCancelada = 0";

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $NumRegActivos = $row['NumRegActivos'];
                
                if ($NumRegActivos == 0) {                    
                    $query = "SELECT InventarioMaxMin.IdMaterial,
                    Material.Nombre,
                    InventarioMaxMin.Maximo,
                    InventarioMaxMin.Minimo,
                    if(VistaInventarioSalidas.Cantidad IS NULL, 0, VistaInventarioSalidas.Cantidad) AS Cantidad
                    FROM InventarioMaxMin
                    LEFT JOIN VistaInventarioSalidas ON VistaInventarioSalidas.IdMaterial = InventarioMaxMin.IdMaterial
                    INNER JOIN Material ON Material.IdMaterial = InventarioMaxMin.IdMaterial
                    WHERE InventarioMaxMin.Minimo > 0 AND InventarioMaxMin.IdMaterial = ".$idMaterial;

                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }

                    if ($row != NULL) {
                        $maximo = floatval($row['Maximo']);
                        $minimo = floatval($row['Minimo']);
                        $cantidadActual = floatval($row['Cantidad']);
                        $nombreMaterial = $row['Nombre'];
                        if ($cantidadActual <= $minimo) {
                            $query = "INSERT INTO Requisicion
                            (EdoPendienteAtender,
                            EdoSolicitadaParcial,
                            EdoAtendida,
                            EdoRecibida,
                            IdTipoRequisicion,
                            Descripcion)
                            VALUES
                            (1,0,0,0,1,'MaximosMinimos');";

                            if (mysqli_query($conexion->mysqli, $query)) {
                                $query = "SELECT last_insert_id() AS IdReq";
                                $result = mysqli_query($conexion->mysqli, $query);
                                $row = mysqli_fetch_array($result);

                                $query = "INSERT INTO RequisicionDetalle
                                (IdRequisicion,
                                IdMaterial,
                                IdObra,
                                NombreObra,
                                NombreMaterial,
                                CantidadSolicitada,
                                Piezas,
                                EdoPendienteAtender,
                                EdoAtendida,
                                EdoRecibida,
                                EdoCancelada,
                                CantidadRecibida,
                                IdUsuarioSolicita)
                                VALUES
                                (". $row['IdReq'] .",
                                ". $idMaterial .",
                                -1,
                                'Stock 2024',
                                '$nombreMaterial',
                                $maximo - $cantidadActual,
                                $maximo - $cantidadActual,
                                1, 0, 0, 0, 0, 27);";

                                if (!mysqli_query($conexion->mysqli, $query)) {
                                    $band_query_exito = 0;
                                }
                            }
                        }
                        else {
//                            echo "NOOOOO requisicion de material";
                        }
                    }
                    else {
//                        echo "este material no está en máximos y mínimos";
                    }
                }
                else {
//                     echo "este material YA está solicitado";
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SALIDA REGISTRADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "SALIDA NO REGISTRADA, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "SALIDA NO REGISTRADA";
        }
        //echo json_encode($conexion->result);
    }

    public function reducirMaterial($idObra, $idMaterial, $cantidad_reducir, $nombreMaterial, $reponer) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $cantMovimiento = $cantidad_reducir;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if (intval($idMaterial) == -1) {
                    $query = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE Nombre = '". $nombreMaterial ."' AND IdObra = ". $idObra ." AND Eliminado IS NULL";
                }
                else {
                    $query = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL";
                }

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $cantSistema = $row['Cantidad'];

                if ($cantSistema >= $cantidad_reducir) {
                    do {
                        if (intval($idMaterial) == -1) {
                            $subQuery = "SELECT * FROM Inventario WHERE Nombre LIKE '". $nombreMaterial ."' AND IdObra = ". $idObra ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }
                        else {
                            $subQuery = "SELECT * FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }
                        
                        $result = mysqli_query($conexion->mysqli, $subQuery);
                        
                        if (!mysqli_query($conexion->mysqli, $subQuery)) {
                            $band_query_exito = 0;
                            break;
                        }
                        else {
                            $row = mysqli_fetch_array($result);
                            $cantRegistro = $row['Cantidad'];
                            $idInventario = $row['IdInventario'];
                        }
                        
                        if (floatval($cantRegistro) > $cantidad_reducir) {
                            $cantRegistro = $cantRegistro - $cantidad_reducir;

                            $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                                SELECT IdOrdenCompra, IdProveedor, -1, IdMaterial, Nombre, ". $cantidad_reducir .", PrecioUnitario FROM Inventario WHERE IdInventario = ". $idInventario;
                            
                            if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                                $band_query_exito = -1;
                                break;
                            }

                            $queryUpdate = "UPDATE Inventario SET Cantidad = ". $cantRegistro ." WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -2;
                                break;
                            }
                            
                            $cantidad_reducir = 0;
                        }
                        else if (floatval($cantRegistro) == $cantidad_reducir) {
                            $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                                SELECT IdOrdenCompra, IdProveedor, -1, IdMaterial, Nombre, ". $cantRegistro .", PrecioUnitario FROM Inventario WHERE IdInventario = ". $idInventario;
                            
                            if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                                $band_query_exito = -3;
                                break;
                            }

                            $queryUpdate = "UPDATE Inventario SET Eliminado = now() WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -4;
                                break;
                            }
                            $cantidad_reducir = 0;
                        }
                        else {
                            $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                                SELECT IdOrdenCompra, IdProveedor, -1, IdMaterial, Nombre, ". $cantRegistro .", PrecioUnitario FROM Inventario WHERE IdInventario = ". $idInventario;
                            
                            if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                                $band_query_exito = -5;
                                break;
                            }

                            $queryUpdate = "UPDATE Inventario SET Eliminado = now() WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -6;
                                break;
                            }

                            $cantidad_reducir = $cantidad_reducir - $cantRegistro;
                        }
                    } while(floatval($cantidad_reducir) > 0);

                    $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial,IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario, IdUsuarioRegistro) VALUES (". $idMaterial .", '$nombreMaterial', ". $idObra .", -1, 'TRASPASO', -1, ". $comodin->idUsuarioSession() .", ". $cantMovimiento .", 'TRASPASO DE MATERIAL - CONSULTA STOCK', ". $comodin->idUsuarioSession() .");";
                
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -7;
                    }

                    if ($band_query_exito > 0) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'MATERIAL REDUCIDO';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = "CodigoError: (". $query .") ". $conexion->mysqli->error;
                    }
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
        if ($reponer == 'false')
            echo json_encode($conexion->result);
    }
    
    public function reasignarMaterialDeStock($idObra, $idMaterial, $cantidad_reducir, $nombreMaterial) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $cantMovimiento = $cantidad_reducir;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if (intval($idMaterial) == -1) {
                    $subQuery = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE Nombre = '". $nombreMaterial ."' AND IdObra = -1 AND Eliminado IS NULL";
                }
                else {
                    $subQuery = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = -1 AND Eliminado IS NULL";
                }

                if (!mysqli_query($conexion->mysqli, $subQuery)) {
                    $band_query_exito = 0;
                }

                $result = mysqli_query($conexion->mysqli, $subQuery);
                $row = mysqli_fetch_array($result);
                $cantSistema = $row['Cantidad'];
                
                if ($cantSistema >= $cantidad_reducir) {
                    do {
                        if (intval($idMaterial) == -1) {
                            $querySelect = "SELECT * FROM Inventario WHERE Nombre = '". $nombreMaterial ."' AND IdObra = -1 AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }
                        else {
                            $querySelect = "SELECT * FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = -1 AND Eliminado IS NULL ORDER BY Creado ASC LIMIT 1";
                        }

                        if (!mysqli_query($conexion->mysqli, $querySelect)) {
                            $band_query_exito = -1;
                            break;
                        }
                        else {
                            $result = mysqli_query($conexion->mysqli, $querySelect);
                            $row = mysqli_fetch_array($result);
                            $cantRegistro = $row['Cantidad'];
                            $idInventario = $row['IdInventario'];
                        }
                        //echo $querySelect."*****";
                        if (floatval($cantRegistro) > $cantidad_reducir) {
                            $cantRegistro = $cantRegistro - $cantidad_reducir;

                            $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor,IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                                SELECT IdOrdenCompra, IdProveedor, ". $idObra .", IdMaterial, Nombre, ". $cantidad_reducir .", PrecioUnitario FROM Inventario WHERE IdInventario = ". $idInventario;
                            
                            //echo $queryInsert."*****";
                            if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                                $band_query_exito = -1;
                                break;
                            }

                            $queryUpdate = "UPDATE Inventario SET Cantidad = ". $cantRegistro ." WHERE IdInventario = ". $idInventario;
                            //echo $queryUpdate."*****";
                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -2;
                                break;
                            }
                            
                            $cantidad_reducir = 0;
                        }
                        else if (floatval($cantRegistro) == $cantidad_reducir) {
                            $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                                SELECT IdOrdenCompra, IdProveedor, ". $idObra .", IdMaterial, Nombre, ". $cantRegistro .", PrecioUnitario FROM Inventario WHERE IdInventario = ". $idInventario;
                            
                            if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                                $band_query_exito = -4;
                                break;
                            }

                            $queryUpdate = "UPDATE Inventario SET Eliminado = now() WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -5;
                                break;
                            }
                            $cantidad_reducir = 0;
                        }
                        else {
                            $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                                SELECT IdOrdenCompra, IdProveedor, ". $idObra .", IdMaterial, Nombre, ". $cantRegistro .", PrecioUnitario FROM Inventario WHERE IdInventario = ". $idInventario;
                            
                            if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                                $band_query_exito = -6;
                                break;
                            }

                            $queryUpdate = "UPDATE Inventario SET Eliminado = now() WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                                $band_query_exito = -7;
                                break;
                            }

                            $cantidad_reducir = $cantidad_reducir - $cantRegistro;
                        }
                    } while (floatval($cantidad_reducir) > 0);
                
                    $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial, IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario, IdUsuarioRegistro) VALUES (". $idMaterial .", '$nombreMaterial', -1, ". $idObra .", 'TRASPASO', -1, ". $comodin->idUsuarioSession() .", ". $cantMovimiento .", 'TRASPASO DE MATERIAL - CONSULTA STOCK', ". $comodin->idUsuarioSession() .");";
                
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -8;
                    }
                
                    if ($band_query_exito > 0) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'MATERIAL RECIBIDO';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = "CodigoError: (".$query.") ".$conexion->mysqli->error;
                    }
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "MATERIAL NO RECIBIDO, ERROR CONEXION BD";
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
        echo json_encode($conexion->result);
    }
    //Recibe todo el material que se pidio desde una orde de compra y se registra en el inventario
    public function recibirTodoMaterialInventarioOC($IdOC_) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $query_error = "";
        $hoyf = date('Y-m-d H:i:s');
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $query = "SELECT COUNT(*) AS RegistrosIncompletos FROM DetalleOrdenCompra WHERE IdOrdenCompra = ". $IdOC_ ." AND Recibido < Cantidad;";

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $regIncompletos = $row['RegistrosIncompletos'];

                $query = "UPDATE RequisicionDetalle SET FechaIngr = '". $hoyf ."' WHERE IdOC = ". $IdOC_ ." AND Eliminado IS NULL";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -1;
                    $query_error = $query;
                }
                
                for ($i = 0; $i < $regIncompletos; $i++) {
                    $query = "SELECT * FROM DetalleOrdenCompra WHERE IdOrdenCompra = ". $IdOC_ ." AND Recibido < Cantidad LIMIT 1;";
                    
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    $idDetalleOC = $row['IdDetalleOrdenCompra'];
                    $idMaterial = $row['IdMaterial'];
                    $nombreMat = $row['NombreMaterial'];
                    $cantidad = $row['Cantidad'];
                    $recibido = $row['Recibido'];
                    $cantidadFaltante = floatval($cantidad) - floatval($recibido);
                    $idObra = $row['IdObra'];
                    $precioUnitario = $row['PrecioUnitario'];
                    $idReqDetalle = $row['IdRequisicionDetalle'];
                    
                    $query = "UPDATE DetalleOrdenCompra SET Recibido = Cantidad WHERE IdDetalleOrdenCompra = ". $idDetalleOC;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -1;
                        $query_error = $query;
                    }

                    $query = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                        SELECT ". $IdOC_ .", IdProveedor, ". $idObra .", ". $idMaterial .", '". $nombreMat ."', ". $cantidadFaltante .", ". $precioUnitario ." FROM OrdenCompra WHERE IdOrdenCompra = ". $IdOC_;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -2;
                        $query_error = $query;
                    }
                    
                    $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial, IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario, IdUsuarioRegistro) VALUES (". $idMaterial .", '$nombreMat', ". $idObra .", ". $idObra .", 'ENTRADA', ". $IdOC_ .", 1, ". $cantidadFaltante .", 'ENTRADA DE MATERIAL', ". $comodin->idUsuarioSession() .");";

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -3;
                        $query_error = $query;
                    }
                }
                
                $query = "UPDATE OrdenCompra SET IdEstadoOC = 3, Recibida = 1 WHERE IdOrdenCompra = ". $IdOC_;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -4;
                    $query_error = $query;
                }
                
                if ($band_query_exito > 0) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL RECIBIDO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "CodigoError (". $band_query_exito .") : (". $query_error .") ". $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "MATERIAL NO RECIBIDO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "MATERIAL NO RECIBIDO";
        }
        echo json_encode($conexion->result);
    }
    //Permite obtener el precio del materiel si ya se agregó a inventario inicial
    public function getPrecioMaterialInvInicial($idMaterial) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "SELECT * FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdOrdenCompra <> -1 ORDER BY Creado DESC LIMIT 1";
                //echo "**** ". $query ." ****";
                if (mysqli_query($conexion->mysqli, $query)) {
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    if ($row)
                        $precioUnitario = $row['PrecioUnitario'];
                    else
                        $precioUnitario = 0;

                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = $precioUnitario;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = 0;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "0";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "0";
        }
        echo json_encode($conexion->result);
    }
    //Se utiliza en Prestamo y regusdo para obtener la 
    //cantidad de material que hay en stock que se puede prestar
    //a los trabajadores
    public function getCantidadMaterialStock($idMaterial, $nombreMaterial) {
        $conexion = new Conexion();
        $cantidadDisp = 0;

        try {
            if ($conexion->abrirBD() != NULL) {
                if($idMaterial != -1){
                    $query = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) AND Eliminado IS NULL";
                }
                else {
                    $query = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = -1 AND Nombre LIKE '". $nombreMaterial ."' AND (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) AND Eliminado IS NULL";
                }
                //echo "****".$query."****";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $cantidadDisp = $row['Cantidad'];
                $conexion->result['error'] = 0;
                $conexion->result['result'] = $cantidadDisp;
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $cantidadDisp;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $cantidadDisp;
        }
        echo json_encode($conexion->result);
    }

    public function getCantidadMaterial($idMaterial, $idObra) {
        $conexion = new Conexion();
        $cantidadDisp = 0;

        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL";
                //echo $query."***";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $cantidadDisp = $row['Cantidad'];
                $conexion->result['error'] = 0;
                $conexion->result['result'] = $cantidadDisp;
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $cantidadDisp;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $cantidadDisp;
        }
        echo json_encode($conexion->result);
    }
    
    public function getProyectosMaterial($idMaterial, $proyecto) {
        $conexion = new Conexion();

        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT Obra.* FROM Inventario JOIN Obra ON Obra.IdObra = Inventario.IdObra WHERE IdMaterial = ". $idMaterial ." AND Obra.Eliminado IS NULL AND Obra.Nombre LIKE '%".$proyecto."%' GROUP BY Obra.IdObra ORDER BY Obra.Nombre LIMIT 5;";
            //echo $query."***";
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            $listaProyectos = array();

            while ($row = mysqli_fetch_array($result)) {
                $proy = array();
                $listaProyectos[] = $proy;
                $data[] = array("id"=>$row['IdObra'], "text"=>$row['Nombre']);
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        $data = $conexion->utf8_converter($data);
        echo json_encode($data);
    }

    public function ajustarMaterialProyecto($idObra, $idMaterial, $ajuste, $conteo, $nota) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $subQuery = "SELECT SUM(Cantidad) AS Cantidad, Nombre FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL";
                
                $result = mysqli_query($conexion->mysqli, $subQuery);
                $row = mysqli_fetch_array($result);
                
                $cantInventario = $row['Cantidad'];
                $nombreMaterial = $row['Nombre'];

                if (floatval($conteo) == 0) {
                    $query = "UPDATE Inventario SET Eliminado = now() WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL";

                    if ( !mysqli_query($conexion->mysqli, $query) ) {
                        $band_query_exito = 0;
                    }
                }
                else if (floatval($conteo) > $cantInventario){
                    $query = "SELECT IdInventario FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL ORDER BY Creado DESC LIMIT 1";
                    
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    
                    $idInventario = $row['IdInventario'];

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -1;
                    }

                    $falta = $conteo - $cantInventario;
                    $query = "UPDATE Inventario SET Cantidad = Cantidad + ". $falta ." WHERE IdInventario = ". $idInventario;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -2;
                    }
                }
                else {
                    $query = "SELECT * FROM Inventario WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL ORDER BY Creado DESC LIMIT 1";
                    
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    
                    $idOrdenCompra = $row['IdOrdenCompra'];
                    $idProveedor = $row['IdProveedor'];
                    $idObra = $row['IdObra'];
                    $idMaterial = $row['IdMaterial'];
                    $nombre = $row['Nombre'];
                    $precioUnitario = $row['PrecioUnitario'];
                    
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -3;
                    }
                    
                    $queryUpdate = "UPDATE Inventario SET Eliminado = now() WHERE IdMaterial = ". $idMaterial ." AND IdObra = ". $idObra ." AND Eliminado IS NULL";
                    
                    if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                        $band_query_exito = -4;
                    }

                    $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario) VALUES (". $idOrdenCompra .", ". $idProveedor .", ". $idObra .", ". $idMaterial .", '". $nombre ."', ". $conteo .", ". $precioUnitario .")";
                    
                    if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                        $band_query_exito = -5;
                    }
                }

                $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial, IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario, IdUsuarioRegistro) VALUES(". $idMaterial .", '". $nombreMaterial ."', ". $idObra .", ". $idObra .", 'AJUSTE', -1, ". $comodin->idUsuarioSession() .", ". $ajuste .", '". $nota ."', ". $comodin->idUsuarioSession() .");";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -6;
                }
                
                $query = "SELECT * FROM InventarioMovimiento ORDER BY Creado DESC LIMIT 1";
                
                $result = mysqli_query($conexion->mysqli, $query);
                $result = mysqli_fetch_assoc($result);
                $nueviId = $result['IdInventarioMovimiento'];
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -7;
                }
                
                if ($band_query_exito > 0) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = $nueviId;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "CodigoError: (". $query .") ". $conexion->mysqli->error;
                }
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "AJUSTE SIN ÉXITO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "AJUSTE SIN ÉXITO";
        }

        return json_encode($conexion->result["result"]);
    }

    public function registrarAjuste($idInvMov, $idObra, $idMaterial, $cantidad, $ajuste, $conteo, $nota) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $query = "INSERT INTO HistoricoAjustes (IdInventarioMovimiento, IdMaterial, IdObra, Cantidad, Conteo, Ajuste, Nota, IdUsuario) VALUES (". $idInvMov .", ". $idMaterial .", ". $idObra .", ". $cantidad .", ". $conteo .", ". $ajuste .", '". $nota ."', ". $comodin->idUsuarioSession() .");";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito > 0) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'AJUSTE EXITOSO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "AJUSTE SIN ÉXITO";
                }
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "AJUSTE SIN ÉXITO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "AJUSTE SIN ÉXITO";
        }
        echo json_encode($conexion->result);
    }

    public function registrarEvento($evento) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;

        try {
            $conexion->obtenerNuevoIdTabla('Eventos');
            $nuevoId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO Eventos (Descripcion) VALUES('". $evento ."');";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                $query = "UPDATE HistoricoAjustes SET IdEvento = ". $nuevoId ." WHERE IdEvento IS NULL";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito > 0) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'AJUSTE EXITOSO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "AJUSTE SIN ÉXITO";
                }
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "AJUSTE SIN ÉXITO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "AJUSTE SIN ÉXITO";
        }
        echo json_encode($conexion->result);
    }

    public function getEventosFilter($busqueda) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != NULL)
                $query = "SELECT * FROM Eventos WHERE Descripcion LIKE '%". $busqueda ."%' LIMIT 5;";
            else
                $query = "SELECT * FROM Eventos LIMIT 5;";

            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();

            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id"=>$row['IdEvento'], "text"=>$row['Descripcion']);
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

    public function cancelarAjuste($IdHistoricoAjustes) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE HistoricoAjustes SET Eliminado = NOW() WHERE IdHistoricoAjustes = ". $IdHistoricoAjustes;
                
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
}