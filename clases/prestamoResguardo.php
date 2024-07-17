<?php
include_once 'conexion.php';
include_once "comodin.php";

class PrestamoResguardo {
    private $id;
    private $fechaPrestamo;
    private $idMaterial;
    private $cantidad;
    private $diasPrestamo;
    private $descripcion;
    private $tipo;
    private $idPersonal;
    private $idUsuario;
    private $idUsuarioRegistro;
    private $nombreMaterial;
    
    public function __construct() {
        $this->id = NULL;
        $this->idMaterial = NULL;
        $this->cantidad = NULL;
        $this->descripcion = NULL;
        $this->tipo = NULL;
        $this->idPersonal = NULL;
        $this->idUsuario = NULL;
        $this->idUsuarioRegistro = NULL;
        $this->nombreMaterial = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $fechaPrestamo_,
                            $idMaterial_,
                            $cantidad_,
                            $diasPrestamo_,
                            $descripcion_,
                            $tipo_,
                            $idPersonal_,
                            $idUsuario_,
                            $nombreMaterial_) {
        $comodin = new Comodin();
        $this->id = $id_;
        $this->fechaPrestamo = $fechaPrestamo_;
        $this->idMaterial = $idMaterial_;
        $this->cantidad = $cantidad_;
        $this->diasPrestamo = $diasPrestamo_;
        $this->descripcion = $descripcion_;
        $this->tipo = $tipo_;
        $this->idPersonal = $idPersonal_;
        $this->idUsuario = $idUsuario_;
        $this->idUsuarioRegistro = $comodin->idUsuarioSession();
        $this->nombreMaterial = $nombreMaterial_;
    }

    public function inserta() {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO PrestamoResguardo
                (FechaPrestamo,
                IdMaterial,
                NombreMaterial,
                Cantidad,
                DiasPrestamo,
                Descripcion,
                TipoPrestamo,
                IdPersonal,
                IdUsuario,
                IdUsuarioRegistro)
                VALUES
                ('$this->fechaPrestamo',
                ". $this->idMaterial .",
                '$this->nombreMaterial',
                ". $this->cantidad .",
                ". $this->diasPrestamo .",
                '$this->descripcion',
                '$this->tipo',
                ". $this->idPersonal .",
                ". $this->idUsuario .",
                ". $this->idUsuarioRegistro .");";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                //echo $query;
                $query = "SELECT @@identity AS IdPrestamoResguardo";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $IdPrestamoResguardo = $row['IdPrestamoResguardo'];
                
                if ($this->idMaterial!=-1)
                    $subQuery = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = ". $this->idMaterial ." AND (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) AND Eliminado IS NULL ORDER BY Creado";
                else
                    $subQuery = "SELECT SUM(Cantidad) AS Cantidad FROM Inventario WHERE IdMaterial = ". $this->idMaterial ." AND Nombre LIKE '$this->nombreMaterial' AND (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) AND Eliminado IS NULL ORDER BY Creado";
                //echo '****'.$subQuery.'****';
                $cantidad_reducir = $this->cantidad;
                $result = mysqli_query($conexion->mysqli, $subQuery);
                $row = mysqli_fetch_array($result);
                $cantSistema = $row['Cantidad'];
                //echo $subQuery."****";
                if ($cantSistema >= $cantidad_reducir) {
                    do {
                        if ($this->idMaterial != -1)
                            $subQuery = "SELECT * FROM Inventario WHERE IdMaterial = ". $this->idMaterial ." AND (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) AND Eliminado IS NULL ORDER BY Creado LIMIT 1";
                        else
                            $subQuery = "SELECT * FROM Inventario WHERE IdMaterial = ". $this->idMaterial ." AND Nombre LIKE '$this->nombreMaterial' AND (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) AND Eliminado IS NULL ORDER BY Creado LIMIT 1";
                        $result = mysqli_query($conexion->mysqli, $subQuery);

                        if (!mysqli_query($conexion->mysqli, $subQuery)) {
                            $band_query_exito = -1;
                            break;
                        }
                        else {
                            $row = mysqli_fetch_array($result);
                            $cantRegistro = $row['Cantidad'];
                            $idInventario = $row['IdInventario'];
                            $nombreMaterial = $row['Nombre'];
                            $idObra = $row['IdObra'];
                        }

                        if (floatval($cantRegistro) > $cantidad_reducir) {
                            $cantRegistro = $cantRegistro - $cantidad_reducir;

                            $query = "INSERT INTO PrestamoResguardoInventario (IdInventario, IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, IdPrestamoResguardo, Comentario)
                                    SELECT IdInventario, IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, ". $cantidad_reducir .", PrecioUnitario, ". $IdPrestamoResguardo .", Comentario FROM Inventario WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = -2;
                            }

                            $query = "UPDATE Inventario SET Cantidad = Cantidad - ". $cantidad_reducir ." WHERE IdInventario = ". $idInventario;
                        
                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = -3;
                            }

                            $cantidad_reducir = -4;
                        }
                        else if ($cantRegistro == $cantidad_reducir) {
                            $query = "INSERT INTO PrestamoResguardoInventario (IdInventario, IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, IdPrestamoResguardo, Comentario)
                                    SELECT IdInventario, IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, ". $cantidad_reducir .", PrecioUnitario, ". $IdPrestamoResguardo .", Comentario FROM Inventario WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = -5;
                            }

                            $query = "UPDATE Inventario SET Eliminado = NOW() WHERE IdInventario = ". $idInventario;
                        
                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = -6;
                            }

                            $cantidad_reducir = 0;
                        }
                        else {
                            $query = "INSERT INTO PrestamoResguardoInventario (IdInventario, IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, IdPrestamoResguardo, Comentario)
                                    SELECT IdInventario, IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, ". $IdPrestamoResguardo .", Comentario FROM Inventario WHERE IdInventario = ". $idInventario;

                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = -7;
                            }
                        
                            $query = "UPDATE Inventario SET Eliminado = NOW() WHERE IdInventario = ". $idInventario;
                            
                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = -8;
                            }
                            
                            $cantidad_reducir = floatval($cantidad_reducir) - floatval($cantRegistro);
                        }
                    } while ($cantidad_reducir > 0);

                    $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial, IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario)
                            VALUES (". $this->idMaterial .", '$nombreMaterial', ". $idObra .", ". $idObra .", 'PRESTAMO-RESGUARDO-SALIDA', -1, ". $this->idPersonal .", ". $this->cantidad .", 'PRESTAMO-RESGUARDO-SALIDA');";
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -9;
                    }
                    
                    $query = "INSERT INTO PrestamoResguardoMovimientos (Cantidad, IdMaterial, NombreMaterial, IdPersonal, IdUsuarioRegistro, TipoPrestamo, TipoMovimiento)
                            SELECT ". $this->cantidad .", IdMaterial, NombreMaterial, IdPersonal, ". $this->idUsuarioRegistro .", '$this->tipo', 'ENTRADA' FROM PrestamoResguardo WHERE IdPrestamoResguardo = ". $IdPrestamoResguardo;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -10;
                    }
                    //echo "****".$band_query_exito."****";
                    if ($band_query_exito) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'MOVIMIENTO REGISTRADO';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = $conexion->mysqli->error;
                    }
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "PRÉSTAMO NO REALIZADO, COR";
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "MOVIMIENTO NO REGISTRADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "MOVIMIENTO NO REGISTRADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function recibirMaterialPrestamo($idPrestamoResguardo, $cantidadRecibida) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        $query_error = "";
        $cantMovimiento = $cantidadRecibida;
        //revisar con recibir resguardo
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "SELECT * FROM PrestamoResguardo WHERE IdPrestamoResguardo = ". $idPrestamoResguardo;
                
                $result = mysqli_query($conexion->mysqli, $query);
                $result = mysqli_fetch_assoc($result);
                $cantidadPrestada = $result["Cantidad"];
                $idPersonal = $result['IdPersonal'];
                
                if ($cantidadPrestada >= $cantidadRecibida) {
                    do {
                        $subQuery = "SELECT * FROM PrestamoResguardoInventario WHERE IdPrestamoResguardo = ". $idPrestamoResguardo ." ORDER BY Creado ASC LIMIT 1";

                        $result = mysqli_query($conexion->mysqli, $subQuery);
                        $row = mysqli_fetch_array($result);

                        $cantidadRegistro = $row['Cantidad'];
                        $idPresResInventario = $row['IdPrestamoResguardoInventario'];
                        $idMaterial = $row['IdMaterial'];
                        $nombreMaterial = $row['Nombre'];

                        if ($cantidadRegistro > $cantidadRecibida) {
                            $query="INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, Comentario)
                                    SELECT IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, ". $cantidadRecibida .", PrecioUnitario, Comentario FROM PrestamoResguardoInventario WHERE IdPrestamoResguardoInventario = ". $idPresResInventario;

                            if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                                $query_error = $query;
                            }
                            
                            $query = "UPDATE PrestamoResguardoInventario SET Cantidad = Cantidad - ". $cantidadRecibida ." WHERE IdPrestamoResguardoInventario = ". $idPresResInventario;
                            
                            if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                                $query_error = $query;
                            }

                            $cantidadRecibida = 0;
                        }
                        else if ($cantidadRegistro == $cantidadRecibida) {
                            $query="INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, Comentario)
                                    SELECT IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, Comentario FROM PrestamoResguardoInventario WHERE IdPrestamoResguardoInventario = ". $idPresResInventario;

                            if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                                $query_error = $query;
                            }
                            
                            $query = "UPDATE PrestamoResguardoInventario SET Eliminado = NOW() WHERE IdPrestamoResguardoInventario = ". $idPresResInventario;
                            
                            if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                                $query_error = $query;
                            }

                            $cantidadRecibida = 0;
                        }
                        else {
                            $query="INSERT INTO Inventario (IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario, Comentario)
                                    SELECT IdOrdenCompra, IdProveedor, IdObra, IdMaterial, Nombre, ". $cantidadRegistro .", PrecioUnitario, Comentario FROM PrestamoResguardoInventario WHERE IdPrestamoResguardoInventario = ". $idPresResInventario;

                            if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                                $query_error = $query;
                            }
                            
                            $query = "UPDATE PrestamoResguardoInventario SET Eliminado = NOW() WHERE IdPrestamoResguardoInventario = ". $idPresResInventario;
                            
                            if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                                $query_error = $query;
                            }

                            $cantidadRecibida = $cantidadRecibida - $cantidadRegistro;
                        }
                    } while ($cantidadRecibida > 0);

                    $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial, IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario)
                        VALUES (". $idMaterial .", '$nombreMaterial', -1, -1, 'PRESTAMO-RESGUARDO-ENTRADA', -1, ". $idPersonal .", ". $cantMovimiento .", 'PRESTAMO-RESGUARDO-ENTRADA');";

                    if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                        $query_error = $query;
                    }
                    
                    if ($cantMovimiento < $cantidadPrestada) {
                        $query = "INSERT INTO PrestamoResguardoMovimientos (Cantidad, IdMaterial, NombreMaterial, IdPersonal, IdUsuarioRegistro, TipoPrestamo, TipoMovimiento)
                                SELECT ". $cantMovimiento .", IdMaterial, NombreMaterial, IdPersonal, 1, TipoPrestamo, 'SALIDA' FROM PrestamoResguardo WHERE IdPrestamoResguardo = ". $idPrestamoResguardo;
                        //echo $query."****";
                        if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -1;
                        }
                        
                        $query = "UPDATE PrestamoResguardo SET Cantidad = Cantidad - ". $cantMovimiento ." WHERE IdPrestamoResguardo = ". $idPrestamoResguardo;
                        //echo $query."****";
                        if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                            $query_error = $query;
                        }
                    }

                    if ($cantMovimiento == $cantidadPrestada) {
                        $query = "INSERT INTO PrestamoResguardoMovimientos (Cantidad, IdMaterial, NombreMaterial, IdPersonal, IdUsuarioRegistro, TipoPrestamo, TipoMovimiento)
                                    SELECT ". $cantMovimiento .", IdMaterial, NombreMaterial, IdPersonal, 1, TipoPrestamo, 'SALIDA' FROM PrestamoResguardo WHERE IdPrestamoResguardo = ". $idPrestamoResguardo .";";
                        //echo $query."****";
                        if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -3;
                            $query_error = $query;
                        }

                        $query = "UPDATE PrestamoResguardo SET Eliminado = NOW() WHERE IdPrestamoResguardo = ". $idPrestamoResguardo;
                        //echo $query."****";
                        if ($band_query_exito && !mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -2;
                            $query_error = $query;
                        }
                    }
                }

                if ($cantidadRecibida > $cantidadPrestada) {
                    $band_query_exito = 0;
                    $conexion->result['result'] = "FAVOR DE VERIFICAR LA CANTIDAD RECIBIDA";
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL RECIBIDO';
                }
                else {
                    $conexion->result['error'] = 1;
                    //$conexion->result['result'] = $conexion->mysqli->error;
                    $conexion->result['result'] = $query_error = $query;
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
    
    public function refrendar($idPrestamoResguardo, $IncrementoDias) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE PrestamoResguardo SET DiasPrestamo = DiasPrestamo + ". $IncrementoDias ." WHERE IdPrestamoResguardo = ". $idPrestamoResguardo;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                $query = "INSERT INTO PrestamoResguardoMovimientos
                (Cantidad,
                IdMaterial,
                NombreMaterial,
                IdPersonal,
                IdUsuarioRegistro,
                TipoPrestamo,
                TipoMovimiento)
                SELECT
                Cantidad,
                IdMaterial,
                NombreMaterial,
                IdPersonal,
                1,
                TipoPrestamo,
                'REFRENDO'
                FROM PrestamoResguardo
                WHERE IdPrestamoResguardo = ". $idPrestamoResguardo;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -3;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REFRENDO REGISTRADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REFRENDO NO REGISTRADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REFRENDO NO REGISTRADO";
        }
        echo json_encode($conexion->result);
    }
}