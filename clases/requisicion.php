<?php
include_once 'conexion.php';
include_once 'material.php';
include_once 'usuario.php';
include_once "comodin.php";

class Requisicion {
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
    //se hace una conulta para saber cuales son todos los materiales pendientes de requisitar
    //De máximos y mínimos
    public function materialesPendientesRequisMaxMin() {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "SELECT InventarioMaxMin.IdMaterial,
                    Material.Nombre,
                    InventarioMaxMin.Maximo,
                    InventarioMaxMin.Minimo,
                    if(VistaInventarioSalidas.Cantidad IS NULL, 0, VistaInventarioSalidas.Cantidad) AS Cantidad
                    FROM InventarioMaxMin
                    LEFT JOIN VistaInventarioSalidas ON VistaInventarioSalidas.IdMaterial = InventarioMaxMin.IdMaterial
                    INNER JOIN Material ON Material.IdMaterial = InventarioMaxMin.IdMaterial
                    WHERE InventarioMaxMin.Minimo > 0 AND VistaInventarioSalidas.Cantidad <= InventarioMaxMin.Minimo AND InventarioMaxMin.IdMaterial NOT IN (SELECT IdMaterial 
                            FROM RequisicionDetalle
                            INNER JOIN Requisicion ON Requisicion.IdRequisicion = RequisicionDetalle.IdRequisicion
                            WHERE Requisicion.IdTipoRequisicion = 1 AND RequisicionDetalle.EdoCancelada = 0 AND RequisicionDetalle.EdoParcialmenteCancelada = 0 AND RequisicionDetalle.EdoAtendida = 0)";
                
                $resultado = mysqli_query($conexion->mysqli, $query);
                while ($registro = mysqli_fetch_assoc($resultado)) {
                    $IdMaterial = $registro['IdMaterial'];
                    $NombreMaterial = $registro['Nombre'];
                    $Maximo = $registro['Maximo'];
                    $CantidadActual = $registro['Cantidad'];
                    
                    $query = "INSERT INTO Requisicion (EdoPendienteAtender, EdoSolicitadaParcial, EdoAtendida, EdoRecibida, IdTipoRequisicion, Descripcion)
                        VALUES (1, 0, 0, 0, 1,'MaximosMinimos');";
                    
                    mysqli_query($conexion->mysqli, $query);
                    $query = "SELECT last_insert_id() AS IdReq";
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    $IdReq = $row['IdReq'];
                    
                    $query = "INSERT INTO RequisicionDetalle
                        (IdRequisicion,
                        IdMaterial,
                        IdObra,
                        NombreObra,
                        NombreMaterial,
                        CantidadSolicitada,
                        Piezas,
                        EdoPendienteAtender,
                        EdoParcialmenteAtendida,
                        EdoParcialmenteCancelada,
                        EdoAtendida,
                        EdoRecibida,
                        EdoCancelada,
                        CantidadRecibida,
                        IdUsuarioSolicita)
                        VALUES
                        (".$IdReq.",
                        ".$IdMaterial.",
                        -1,
                        'Stock 2024',
                        '$NombreMaterial',
                        $Maximo - $CantidadActual,
                        $Maximo - $CantidadActual,
                        1,0,0,0,0,0,0,27);";
//                    echo $query;
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }
                /* liberar el conjunto de resultados */
                mysqli_free_result($resultado);
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICIONES REALIZADAS';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICIONES NO REALIZADAS, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICIONES REALIZADAS';
        }
//        echo json_encode($conexion->result);
    }
    
    public function cancelarRequisicionDetalle($IdRequisicionDetalle, $MotivoCancelacion) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_assoc($result);
                $CantidadSolicitada = floatval($row['CantidadSolicitada']);
                $CantidadPedida = floatval($row['CantidadPedida']);
                
                if ($CantidadPedida == 0) {
                    $query = "UPDATE RequisicionDetalle SET EdoCancelada = 1, MotivoCancelacion = '$MotivoCancelacion', IdUsuarioCancela = ". $comodin->idUsuarioSession() .", FechaCancelacion = now() WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;
                }
                else {
                    $query = "UPDATE RequisicionDetalle SET EdoParcialmenteCancelada = 1, MotivoCancelacion = '$MotivoCancelacion', IdUsuarioCancela = ". $comodin->idUsuarioSession() .", FechaCancelacion = now() WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;
                }

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "UPDATE RequisicionAtendida SET Eliminado = now() WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION CANCELADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO CANCELADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION CANCELADA';
        }
        echo json_encode($conexion->result);
    }
    /* Cancela una requisicion completa si no tiene materiales atendidos o cancelados */
    public function cancelarRequisicionCompleta($IdRequisicion, $MotivoCancelacion) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $bandMaterialesPendientes = TRUE;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicion = ". $IdRequisicion;

                $result = mysqli_query($conexion->mysqli, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    if (($row['EdoPendienteAtender'] == '1' || $row['EdoRevision'] == '1') && $row['EdoParcialmenteAtendida'] == '0' && $row['EdoParcialmenteCancelada'] == '0' && $row['EdoAtendida'] == '0' && $row['EdoRecibida'] == '0' && $row['EdoCancelada'] == '0') {}
                    else {
                        $bandMaterialesPendientes = FALSE;
                    }
                }
                if ($bandMaterialesPendientes) {
                    $query = "UPDATE RequisicionDetalle SET EdoRevision = 0, EdoPendienteAtender = 1, EdoAtendida = 0, EdoRecibida = 0, EdoCancelada = 1, IdUsuarioCancela = ". $comodin->idUsuarioSession() .", MotivoCancelacion = '$MotivoCancelacion', FechaCancelacion = now() WHERE IdRequisicion = ". $IdRequisicion;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }

                if ($band_query_exito && $bandMaterialesPendientes) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION CANCELADA';
                }
                else if($band_query_exito && !$bandMaterialesPendientes) {
                    $conexion->commit();
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = 'REQUISICION NO CANCELADA, VERIFIQUE EL ESTADO DE LOS MATERIALES';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO CANCELADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION CANCELADA';
        }
        echo json_encode($conexion->result);
    }
    /* Al cancelar la OC se debe reiniciar los valores de la requisicion para que quede como pendiente */
    public function resetRequisicion($IdOrdenCompra) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE RequisicionDetalle SET EdoPendienteAtender = 1, EdoAtendida = 0, EdoRecibida = 0, EdoCancelada = 0 WHERE IdOC = ". $IdOrdenCompra;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION RESETEADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO CANCELADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION CANCELADA';
        }

        echo json_encode($conexion->result);
    }
    /**************************************************************************/
    public function crearRequiManual($req, $reqDetalle) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO Requisicion (EdoPendienteAtender, EdoSolicitadaParcial, EdoAtendida, EdoRecibida, IdTipoRequisicion, Descripcion)
                    VALUES (1, 0, 0, 0, 2, '". $req['Observaciones'] ."');";
                    
                mysqli_query($conexion->mysqli, $query);
                $query = "SELECT last_insert_id() AS IdReq";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $IdReq = $row['IdReq'];
                    
                foreach ($reqDetalle as $i) {
                    $query = "INSERT INTO RequisicionDetalle
                    (IdRequisicion,
                    IdMaterial,
                    IdObra,
                    NombreObra,
                    NombreMaterial,
                    CantidadSolicitada,
                    EdoPendienteAtender,
                    EdoParcialmenteAtendida,
                    EdoParcialmenteCancelada,
                    EdoAtendida,
                    EdoRecibida,
                    EdoCancelada,
                    CantidadRecibida,
                    IdUsuarioSolicita,
                    Unidad,
                    Piezas,
                    FechaReq)
                    VALUES
                    (". $IdReq .",
                    ". $i['IdMaterial'] .",
                    ". $i['IdObra'] .",
                    FnNombreObra(". $i['IdObra'] ."),
                    FnNombreMaterial(". $i['IdMaterial'] ."),
                    ". $i['Cantidad'] .",
                    1, 0, 0, 0, 0, 0, 0,
                    ". $comodin->idUsuarioSession() .",
                    '". $i['Unidad'] ."',
                    ". $i['Piezas'] .",
                    '". $i['FechaReq'] ."');";

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION REALIZADA FOLIO = '. $IdReq;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO REALIZADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION NO REALIZADA';
        }

        echo json_encode($conexion->result);
    }
    
    public function crearRequiEspecial($req, $reqDetalle) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
            
                $query = "INSERT INTO Requisicion
                (EdoRevision,
                EdoPendienteAtender,
                EdoSolicitadaParcial,
                EdoAtendida,
                EdoRecibida,
                IdTipoRequisicion,
                Descripcion)
                VALUES
                (1, 0, 0, 0, 0, 4, '". $req['Observaciones'] ."');";
                
                mysqli_query($conexion->mysqli, $query);
                $query = "SELECT last_insert_id() AS IdReq";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $IdReq = $row['IdReq'];
                
                foreach ($reqDetalle as $i) {
                    $query = "INSERT INTO RequisicionDetalle
                    (IdRequisicion,
                    UnicaOcasion,
                    IdMaterial,
                    IdObra,
                    NombreObra,
                    NombreMaterial,
                    CantidadSolicitada,
                    Piezas,
                    EdoRevision,
                    EdoPendienteAtender,
                    EdoParcialmenteAtendida,
                    EdoParcialmenteCancelada,
                    EdoAtendida,
                    EdoRecibida,
                    EdoCancelada,
                    CantidadRecibida,
                    IdUsuarioSolicita,
                    FechaReq)
                    VALUES
                    (". $IdReq .",
                    ". $i['UnicaOcasion'] .",
                    ". $i['IdMaterial'] .",
                    ". $i['IdObra'] .",
                    'Una obra',
                    '". $i['Material'] ."',
                    ". $i['Cantidad'] .",
                    ". $i['Cantidad'] .",
                    1, 0, 0, 0, 0, 0, 0, 0,
                    ". $comodin->idUsuarioSession() .",
                    '". $i['FechaRequiEsp'] ."');";

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION REALIZADA FOLIO = '. $IdReq;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO REALIZADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION NO REALIZADA';
        }

        echo json_encode($conexion->result);
    }
    
    public function modificarRequisicionDetalle($IdRequisicionDetalle, $IdMaterial, $NombreMaterial, $IdProveedor, $Precio) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_assoc($result);
                $UnicaOcasion = $row['UnicaOcasion'];
                
                if ($UnicaOcasion == 1) {
                    $query = "UPDATE RequisicionDetalle SET Precio = ". str_replace(",", "", $Precio) .", IdProveedor = ". $IdProveedor ." WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;
                }
                else {
                    $query = "UPDATE RequisicionDetalle SET IdMaterial = ". $IdMaterial .", NombreMaterial = '$NombreMaterial', IdProveedor = ". $IdProveedor .", Precio = ". str_replace(",", "", $Precio) ." WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;
                }

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION ACTUALIZADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO ACTUALIZADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION NO ACTUALIZADA';
        }

        echo json_encode($conexion->result);
    }
    /**************************************************************************/
    public function resetRequsicionesCheck($tipoRequisicion) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        $idTipoRequisicion = 0;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if ($tipoRequisicion == "Manual")
                    $idTipoRequisicion = 2;
                else if ($tipoRequisicion == "Especial")
                    $idTipoRequisicion = 4;
                
                $query = "UPDATE RequisicionAtendida SET Eliminado = now() WHERE SurtidoDesde IS NULL AND IdTipoRequisicion = ". $idTipoRequisicion;
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'RESET REQUISICIONES';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "NO RESET REQUISICIONES, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'NO RESET REQUISICIONES';
        }

        echo json_encode($conexion->result);
    }
    
    public function comprarMaterial($idRequisicionDetalle, $cantidadPedida, $idMateral, $idProveedor, $tipoRequisicion, $fechaProv) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $bandMensajeError = "";

        try {
            if ($conexion->abrirBD() != NULL ) {
                $conexion->iniciaTransaccion();

                $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;
                
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_assoc($result);

                if ($row['Unidad'] == 'Pieza') {
                    $CantidadSolicitadaRD = $row['Piezas'];
                    
                    if ($tipoRequisicion == 4 && $CantidadSolicitadaRD == 0) {
                        $CantidadSolicitadaRD = $row['CantidadSolicitada'];
                    }
                }
                else {
                    $CantidadSolicitadaRD = $row['CantidadSolicitada'];
                }
                
                $CantidadPedidaRD = $row['CantidadPedida'];

                $query = "UPDATE RequisicionDetalle SET FechaProv = '". $fechaProv ."' WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                $query = "SELECT * FROM RequisicionAtendida WHERE IdRequisicionDetalle = ". $idRequisicionDetalle ." AND SurtidoDesde IS NULL AND IdProveedor = ". $idProveedor ." AND IdMaterial = ". $idMateral ." AND Eliminado IS NULL";

                $result = mysqli_query($conexion->mysqli, $query);
                $numRegistros = mysqli_fetch_assoc($result);

                if (intval($numRegistros) > 0) {
                    //falta determinar si la cantidad pedida está dentro de lo que se debe solicitar
                    //analizar la idea de que cada que se solicite material de esta requi
                    //incrementarlo en la Tabla RequisicionDetalle en el campo CantidadPedida
                    //Para saber cuanto nos falta de pedir.. hacer la resta entre Cantid pedida y CantidadSolicitada
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_assoc($result);
                    $idRequisicionAtendida = $row['IdRequisicionAtendida'];
                    $CantidadPedida_Atendida = $row['CantidadPedida'];

                    if ((floatval($CantidadPedidaRD) + ($cantidadPedida)) <= floatval($CantidadSolicitadaRD)) {
                        $query = "UPDATE RequisicionAtendida SET CantidadPedida = ". $cantidadPedida ." WHERE IdRequisicionAtendida = ". $idRequisicionAtendida;

                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }
                    else { //No se puede solicitar, se excede
                        $band_query_exito = 0;
                        $bandMensajeError = "FAVOR DE VERIFICAR LA CANTIDAD A SOLICITAR";
                    }
                }
                else {
                    if ((floatval($CantidadPedidaRD) + ($cantidadPedida)) <= floatval($CantidadSolicitadaRD)) {
                        $query = "INSERT INTO RequisicionAtendida
                        (IdRequisicionDetalle,
                        CantidadPedida,
                        IdProveedor,
                        IdMaterial,
                        IdUsuario,
                        IdTipoRequisicion)
                        VALUES
                        (". $idRequisicionDetalle .",
                        ". $cantidadPedida .",
                        ". $idProveedor .",
                        ". $idMateral .",
                        ". $comodin->idUsuarioSession() .",
                        ". $tipoRequisicion .");";

                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }
                    else {
                        $band_query_exito = 0;
                        $bandMensajeError = "FAVOR DE VERIFICAR LA CANTIDAD A SOLICITAR";
                    }
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SOLICITUD GUARDADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    if ($bandMensajeError != "") {
                        $conexion->result['result'] = $bandMensajeError;
                    }
                    else {
                        $conexion->result['result'] = $conexion->mysqli->error;
                    }
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "NO RESET REQUISICIONES, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'NO RESET REQUISICIONES';
        }
        echo json_encode($conexion->result);
    }
    
    public function comprarMaterialxkilo($idRequisicionDetalle, $cantidadPedida, $idMateral, $idProveedor, $tipoRequisicion) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $bandMensajeError = "";

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;

                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_assoc($result);

                $CantidadSolicitadaRD = $row['CantidadSolicitada'];
                $CantidadPedidaRD = $row['CantidadPedida'];
                
                $query = "SELECT * FROM RequisicionAtendida WHERE IdRequisicionDetalle = ". $idRequisicionDetalle ." AND SurtidoDesde IS NULL AND IdProveedor = ". $idProveedor ." AND IdMaterial = ". $idMateral ." AND Eliminado IS NULL";

                $result = mysqli_query($conexion->mysqli, $query);
                $numRegistros = mysqli_fetch_assoc($result);

                if (intval($numRegistros) > 0) {
                    //falta determinar si la cantidad pedida está dentro de lo que se debe solicitar
                    //analizar la idea de que cada que se solicite material de esta requi
                    //incrementarlo en la Tabla RequisicionDetalle en el campo CantidadPedida
                    //Para saber cuanto nos falta de pedir.. hacer la resta entre Cantid pedida y CantidadSolicitada
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_assoc($result);

                    $idRequisicionAtendida = $row['IdRequisicionAtendida'];
                    $CantidadPedida_Atendida = $row['CantidadPedida'];
                    //Si se puede solicitar
                    if ((floatval($CantidadPedidaRD) + ($cantidadPedida)) <= floatval($CantidadSolicitadaRD)) {
                        $query = "UPDATE RequisicionAtendida SET CantidadPedida = ". $cantidadPedida ." WHERE IdRequisicionAtendida = ". $idRequisicionAtendida;

                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }
                    else {
                        //No se puede solicitar, se excede
                        $band_query_exito = 0;
                        $bandMensajeError = "FAVOR DE VERIFICAR LA CANTIDAD A SOLICITAR";
                    }
                }
                else {
                    if ((floatval($CantidadPedidaRD) + ($cantidadPedida)) <= floatval($CantidadSolicitadaRD)) {
                        $query = "INSERT INTO RequisicionAtendida
                        (IdRequisicionDetalle,
                        CantidadPedida,
                        IdProveedor,
                        IdMaterial,
                        IdUsuario,
                        IdTipoRequisicion)
                        VALUES
                        (". $idRequisicionDetalle .",
                        ". $cantidadPedida .",
                        ". $idProveedor .",
                        ". $idMateral .",
                        ". $comodin->idUsuarioSession() .",
                        ". $tipoRequisicion .");";

                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }
                    else {
                        $band_query_exito = 0;
                        $bandMensajeError = "FAVOR DE VERIFICAR LA CANTIDAD A SOLICITAR";
                    }
                    //falta determinar si la cantidad pedida está dentro de lo que se debe solicitar
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SOLICITUD GUARDADA';
                }
                else {
                    $conexion->result['error'] = 1;

                    if ($bandMensajeError != "") {
                        $conexion->result['result'] = $bandMensajeError;
                    }
                    else {
                        $conexion->result['result'] = $conexion->mysqli->error;
                    }
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "NO RESET REQUISICIONES, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'NO RESET REQUISICIONES';
        }
        echo json_encode($conexion->result);
    }
    /*
     * Una requisicion se va a almacenando en requis atendidades y de ahí a solicitar oc
     * cuando se guarda la orden de compra se deben actualizar todas esas requis atendidas 
     * estableciendo el campo de Surtido desde con la palabra OC
     * 
     * Aun no funciona solo tiene los parámetros
    */
    public function MarcarRequisAtendidasComoAtendidads($idRequisicionAtendidas) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE RequisicionAtendida SET SurtidoDesde = OC WHERE SurtidoDesde IS NULL";
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'RESET REQUISICIONES';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "NO RESET REQUISICIONES, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'NO RESET REQUISICIONES';
        }
        echo json_encode($conexion->result);
    }
    /**************************************************************************/
    public function eliminarRequisicionOC($IdRequisicionAtendida) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE RequisicionAtendida SET Eliminado = now() WHERE IdRequisicionAtendida = ". $IdRequisicionAtendida;
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION ELIMINADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO ELIMINADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION ELIMINADA';
        }
        echo json_encode($conexion->result);
    }
    /* Permite atender una requisicion con material de stock */
    public function reasignarMaterialDeStock($idRequisicionDetalle, $idObra, $idMaterial, $cantidad_reducir, $tipoRequisicion) {
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
                //echo $subQuery."****";
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
                        //echo $querySelect."****";
                        if (floatval($cantRegistro) > $cantidad_reducir) {
                            $cantRegistro = $cantRegistro - $cantidad_reducir;

                            $queryInsert = "INSERT INTO Inventario (IdOrdenCompra, IdProveedor,IdObra, IdMaterial, Nombre, Cantidad, PrecioUnitario)
                                SELECT IdOrdenCompra, IdProveedor, ". $idObra .", IdMaterial, Nombre, ". $cantidad_reducir .", PrecioUnitario FROM Inventario WHERE IdInventario = ". $idInventario;
                            //echo $queryInsert."****";
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
                    
                    $query = "INSERT INTO InventarioMovimiento (IdMaterial, NombreMaterial, IdObraOrigen, IdObraDestino, TipoMovimiento, IdOrdenCompra, IdUsuario, Cantidad, Comentario, IdUsuarioRegistro)
                        VALUES(". $idMaterial .", '". $comodin->NombreMaterial($conexion, $idMaterial) ."', -1, ". $idObra .", 'TRASPASO', -1, ". $comodin->idUsuarioSession() .", ". $cantMovimiento .", 'TRASPASO DE MATERIAL - REQUISICION STOCK', ". $comodin->idUsuarioSession() .");";

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -4;
                    }
                    
                    $query = "INSERT INTO RequisicionAtendida
                    (IdRequisicionDetalle,
                    IdTipoRequisicion,
                    CantidadPedida,
                    SurtidoDesde,
                    IdOrdenCompra,
                    IdProveedor,
                    IdMaterial,
                    IdUsuario)
                    VALUES
                    (". $idRequisicionDetalle .",
                    ". $tipoRequisicion .",
                    ". $cantMovimiento .",
                    'STOCK',
                    -1,
                    -1,
                    ". $idMaterial .",
                    ". $comodin->idUsuarioSession() .");";
                    //echo $query."****";
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -4;
                    }
                    
                    $query = "UPDATE RequisicionDetalle SET EdoParcialmenteAtendida = 1, CantidadPedida = CantidadPedida + ". $cantMovimiento ." WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;
                    //echo $query."****";
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                    
                    $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;

                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    $CantidadSolicitada = $row['CantidadSolicitada'];
                    $CantidadPedida = $row['CantidadPedida'];
                    //echo "**** CantidadSolicitada: ". $CantidadSolicitada ." >= CantidadPedida: ". $CantidadPedida ."****";
                    if ($CantidadPedida >= $CantidadSolicitada) {
                        $query = "UPDATE RequisicionDetalle SET EdoAtendida = 1 WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;
                        //echo $query."****";
                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }
                
                    if ($band_query_exito > 0) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'MATERIAL ATENDIDO CON STOCK';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = "CodigoError: (".$query.") ".$conexion->mysqli->error;
                    }
                    
                    $conexion->cerrarBD();
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "MATERIAL NO ATENDIDO CON STOCK, REVISE LA CANTIDAD PEDIDA";
                }
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "MATERIAL NO ATENDIDO CON STOCK, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "MATERIAL NO ATENDIDO CON STOCK";
        }
        echo json_encode($conexion->result);
    }
    
    public function entregaRequisicion($IdOrdenCompra) {
        $hoyf = date('Y-m-d H:i:s');
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $query = "UPDATE RequisicionDetalle SET FechaIngr = '". $hoyf ."' WHERE IdOC = ". $IdOrdenCompra;
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION RECIBIDA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO CANCELADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION CANCELADA';
        }
        //echo json_encode($conexion->result);
    }
    
    public function eliminarDetalleRequisicion($IdRequisicion) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE RequisicionDetalle SET Eliminado = now() WHERE IdRequisicionDetalle = ". $IdRequisicion;
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL DE REQUISICION ELIMINADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "MATERIAL DE REQUISICION NO ELIMINADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'MATERIAL DE REQUISICION ELIMINADA';
        }
        echo json_encode($conexion->result);
    }

    public function obtenerDetalleRequisicion($IdRequisicionDetalle) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM VistaRequisicionesDetallePendientesAtendidas WHERE IdRequisicionDetalle = ". $IdRequisicionDetalle;

            $result = mysqli_query($conexion->mysqli, $query);
            while ($fila = $result->fetch_assoc()) {
                $listaMat[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMat;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        echo json_encode($conexion->result['result']);
    }

    public function actualizarRequiManual($req, $reqDetalle) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                $idRequisicion = $req['IdRequisicion'];
                
                foreach ($reqDetalle as $i) {
                    $query = "INSERT INTO RequisicionDetalle
                    (IdRequisicion,
                    IdMaterial,
                    IdObra,
                    NombreObra,
                    NombreMaterial,
                    CantidadSolicitada,
                    EdoPendienteAtender,
                    EdoParcialmenteAtendida,
                    EdoParcialmenteCancelada,
                    EdoAtendida,
                    EdoRecibida,
                    EdoCancelada,
                    CantidadRecibida,
                    IdUsuarioSolicita,
                    Unidad,
                    Piezas)
                    VALUES
                    (". $idRequisicion .",
                    ". $i['IdMaterial'] .",
                    ". $i['IdObra'] .",
                    FnNombreObra(". $i['IdObra'] ."),
                    FnNombreMaterial(". $i['IdMaterial'] ."),
                    ". $i['Cantidad'] .",
                    1, 0, 0, 0, 0, 0, 0,
                    ". $comodin->idUsuarioSession(). ",
                    '". $i['Unidad'] ."',
                    ". $i['Piezas'] .");";
                    
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION ACTUALIZADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO ACTUALIZADA, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION NO ACTUALIZADA';
        }
        echo json_encode($conexion->result);
    }

    public function editarDetalleRequisicion($detalleReq) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE RequisicionDetalle SET IdObra = ". $detalleReq['IdProyecto'] .", IdMaterial = ". $detalleReq['IdMaterial'] .", CantidadSolicitada = ". $detalleReq['Cantidad'] .", Piezas = ". $detalleReq['Cantidad'] .", Unidad = '". $detalleReq['Unidad'] ."' WHERE IdRequisicionDetalle = ". $detalleReq['IdRequisicionDetalle'];
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL EN REQUISICION ACTUALIZADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "MATERIAL EN REQUISICION NO ACTUALIZADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'MATERIAL EN REQUISICION NO ACTUALIZADA';
        }
        echo json_encode($conexion->result);
    }

    public function regresarDetalleRequisicion($idRequisicionDetalle) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                $query = "UPDATE RequisicionDetalle SET EdoRevision = 0, EdoPendienteAtender = 1, EdoParcialmenteAtendida = 0, EdoAtendida = 0, EdoRecibida = 0 WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                $query2 = "UPDATE RequisicionAtendida SET Eliminado = now() WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;

                if (!mysqli_query($conexion->mysqli, $query2)) {
                    $band_query_exito = 0;
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'MATERIAL EN REQUISICION ACTUALIZADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "MATERIAL EN REQUISICION NO ACTUALIZADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'MATERIAL EN REQUISICION NO ACTUALIZADA';
        }
        echo json_encode($conexion->result);
    }

    public function cambiarSeleccionada($idReqAtendida_, $valor_) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE RequisicionAtendida SET Seleccionada = ". $valor_ ." WHERE IdRequisicionAtendida = ". $idReqAtendida_;

                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
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
        
        return $conexion->result;
    }

    public function crearRequiReducida($idMaterial_, $idObra_, $cantidad_, $nombreMaterial_, $reponer) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        if ($reponer == 'true') {
            try {
                if ($conexion->abrirBD() != NULL) {
                    $conexion->iniciaTransaccion();

                    if ($idMaterial_ == -1)
                        $query = "INSERT INTO Requisicion (EdoPendienteAtender, EdoSolicitadaParcial, EdoAtendida, EdoRecibida, IdTipoRequisicion, Descripcion)
                        VALUES (1, 0, 0, 0, 4, 'Creada por reducción de material');";
                    else
                        $query = "INSERT INTO Requisicion (EdoPendienteAtender, EdoSolicitadaParcial, EdoAtendida, EdoRecibida, IdTipoRequisicion, Descripcion)
                        VALUES (1, 0, 0, 0, 2, 'Creada por reducción de material');";
                        
                    mysqli_query($conexion->mysqli, $query);
                    $query = "SELECT last_insert_id() AS IdReq";
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    $IdReq = $row['IdReq'];


                    if ($idMaterial_ == -1)
                        $query = "INSERT INTO RequisicionDetalle
                        (IdRequisicion,
                        IdMaterial,
                        IdObra,
                        NombreObra,
                        NombreMaterial,
                        CantidadSolicitada,
                        EdoPendienteAtender,
                        EdoParcialmenteAtendida,
                        EdoParcialmenteCancelada,
                        EdoAtendida,
                        EdoRecibida,
                        EdoCancelada,
                        CantidadRecibida,
                        IdUsuarioSolicita,
                        Unidad,
                        Piezas,
                        FechaReq,
                        UnicaOcasion)
                        VALUES
                        (". $IdReq .",
                        ". $idMaterial_ .",
                        ". $idObra_ .",
                        FnNombreObra(". $idObra_ ."),
                        '". $nombreMaterial_ ."',
                        ". $cantidad_ .",
                        1, 0, 0, 0, 0, 0, 0,
                        ". $comodin->idUsuarioSession() .",
                        'Pieza',
                        ". $cantidad_ .",
                        NOW(),
                        0);";
                    else
                        $query = "INSERT INTO RequisicionDetalle
                        (IdRequisicion,
                        IdMaterial,
                        IdObra,
                        NombreObra,
                        NombreMaterial,
                        CantidadSolicitada,
                        EdoPendienteAtender,
                        EdoParcialmenteAtendida,
                        EdoParcialmenteCancelada,
                        EdoAtendida,
                        EdoRecibida,
                        EdoCancelada,
                        CantidadRecibida,
                        IdUsuarioSolicita,
                        Unidad,
                        Piezas,
                        FechaReq)
                        VALUES
                        (". $IdReq .",
                        ". $idMaterial_ .",
                        ". $idObra_ .",
                        FnNombreObra(". $idObra_ ."),
                        FnNombreMaterial(". $idMaterial_ ."),
                        ". $cantidad_ .",
                        1, 0, 0, 0, 0, 0, 0,
                        ". $comodin->idUsuarioSession() .",
                        'Pieza',
                        ". $cantidad_ .",
                        NOW());";

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }

                    if ($band_query_exito) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'REQUISICION REALIZADA FOLIO = '. $IdReq;
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = $conexion->mysqli->error;
                    }

                    $conexion->cerrarBD();
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "REQUISICION NO REALIZADA, ERROR CONEXION BD";
                }
            }
            catch (Exception $ex) {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = 'REQUISICION NO REALIZADA';
            }
            echo json_encode($conexion->result);
        }
    }

    public function crearRequisicionProducto($idObra_, $idProducto_, $cantidad_) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $querySelect = "SELECT Nombre FROM Articulo WHERE IdArticulo = ". $idProducto_;
                //echo $querySelect."****";
                $resultSelect = mysqli_query($conexion->mysqli, $querySelect);
                $rowSelect = mysqli_fetch_array($resultSelect);
                $producto = $rowSelect['Nombre'];

                if (!mysqli_query($conexion->mysqli, $querySelect)) {
                    $band_query_exito = 0;
                }

                $queryInsert = "INSERT INTO Requisicion (EdoPendienteAtender, EdoSolicitadaParcial, EdoAtendida, EdoRecibida, IdTipoRequisicion, Descripcion)
                    VALUES (1, 0, 0, 0, 2, 'Creada para:". $producto ."');";
                //echo $queryInsert."****";
                if (!mysqli_query($conexion->mysqli, $queryInsert)) {
                    $band_query_exito = -1;
                }

                $query = "SELECT last_insert_id() AS IdReq";
                //echo $query."****";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                $IdReq = $row['IdReq'];

                $queryUpdate = "UPDATE ObraProducto SET IdRequisicion = ". $IdReq ." WHERE IdArticulo = ". $idProducto_ ." AND IdObra = ". $idObra_ ." AND Cantidad = ". $cantidad_ ." AND Eliminado IS NULL";
                //echo $queryUpdate."****";
                $result = mysqli_query($conexion->mysqli, $queryUpdate);

                if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                    $band_query_exito = -2;
                }
                
                $query = "SELECT * FROM ArticuloDetalle WHERE IdArticulo = ". $idProducto_ ." AND Eliminado IS NULL";
                //echo $query."****";
                $result = mysqli_query($conexion->mysqli, $query);

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -3;
                }

                while ($row = $result->fetch_assoc()) {
                    $query = "INSERT INTO RequisicionDetalle
                    (IdRequisicion,
                    IdMaterial,
                    IdObra,
                    NombreObra,
                    NombreMaterial,
                    CantidadSolicitada,
                    EdoPendienteAtender,
                    EdoParcialmenteAtendida,
                    EdoParcialmenteCancelada,
                    EdoAtendida,
                    EdoRecibida,
                    EdoCancelada,
                    CantidadRecibida,
                    IdUsuarioSolicita,
                    Unidad,
                    Piezas,
                    FechaReq)
                    VALUES
                    (". $IdReq .",
                    ". $row['IdMaterial'] .",
                    ". $idObra_ .",
                    FnNombreObra(". $idObra_ ."),
                    FnNombreMaterial(". $row['IdMaterial'] ."),
                    ". $row['Cantidad'] * $cantidad_ .",
                    1, 0, 0, 0, 0, 0, 0,
                    ". $comodin->idUsuarioSession() .",
                    'Pieza',
                    ". $row['Cantidad'] * $cantidad_ .",
                    NOW());";

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -4;
                    }
                }
                //echo "****bandquery: ".$band_query_exito." ****";
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION REALIZADA FOLIO = '. $IdReq;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO REALIZADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION NO REALIZADA';
        }
        echo json_encode($conexion->result);
    }

    public function eliminarRequisicionProducto($idRequisicion_) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $query = "UPDATE RequisicionDetalle SET EdoCancelada = 1, IdUsuarioCancela = ". $comodin->idUsuarioSession() .", MotivoCancelacion = 'Se eliminó el producto del proyecto', FechaCancelacion = NOW() WHERE IdRequisicion = ". $idRequisicion_;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = -1;
                }

                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION ELIMINADA FOLIO = '. $idRequisicion_;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO REALIZADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION NO REALIZADA';
        }
        echo json_encode($conexion->result);
    }

    public function autorizarRequisicionCompleta($IdRequisicion) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        $bandMaterialesRevision = TRUE;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicion = ". $IdRequisicion;

                $result = mysqli_query($conexion->mysqli, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['EdoRevision'] == '1' && $row['EdoPendienteAtender'] == '0' && $row['EdoParcialmenteAtendida'] == '0' && $row['EdoParcialmenteCancelada'] == '0' && $row['EdoAtendida'] == '0' && $row['EdoRecibida'] == '0' && $row['EdoCancelada'] == '0') {}
                    else {
                        $bandMaterialesRevision = FALSE;
                    }
                }
                if ($bandMaterialesRevision) {
                    $query = "UPDATE RequisicionDetalle SET EdoRevision = 0, EdoPendienteAtender = 1, EdoAtendida = 0, EdoRecibida = 0, EdoCancelada = 0 WHERE IdRequisicion = ". $IdRequisicion;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }

                if ($band_query_exito && $bandMaterialesRevision) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REQUISICION APROBADA';
                }
                else if($band_query_exito && !$bandMaterialesRevision) {
                    $conexion->commit();
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = 'REQUISICION NO APROBADA, VERIFIQUE EL ESTADO DE LOS MATERIALES';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REQUISICION NO APROBADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = 'REQUISICION NO APROBADA';
        }
        echo json_encode($conexion->result);
    }
}