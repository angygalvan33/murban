<?php
include_once 'conexion.php';

class CajaChica {
    private $id;
    private $idUsuario;
    private $presupuesto;
    
    public function __construct() {
        $this->id = NULL;
        $this->idUsuario = NULL;
        $this->presupuesto = NULL;
    }
    
    public function llenaDatos($id_,
                                $idUsuario_,
                                $presupuesto_) {
        $this->id = $id_;
        $this->idUsuario = $idUsuario_;
        $this->presupuesto = $presupuesto_;
    }

    public function inserta() {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            $conexion->obtenerNuevoIdTabla('CajaChica');
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO CajaChica (IdCajaChica, IdUsuario, Presupuesto, Activa)
                        VALUES (". $nueviId .", ". $this->idUsuario .", ". $this->presupuesto .", 1);";
//                echo $query;
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "INSERT INTO CajaChicaAbonos (IdCajaChica, TipoAbono, Cantidad, IdUsuario)
                        VALUES (". $nueviId .", 'Abono inicial', ". $this->presupuesto .", 1)";
//                echo $query;
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                $query = "SELECT last_insert_id() AS IdCajaChicaAbonos";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                
                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'CAJA CHICA CREADA';
                    $conexion->result['idCajaChicaAbonos'] = $row['IdCajaChicaAbonos'];
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
            $conexion->result['result'] = "CAJA CHICA NO CREADA";
        }
        
        echo json_encode($conexion->result);
    }
    /**************************************************************************/
    public function insertaCajaChicaDetalle($IdUsCajaChica_, $IdObra_, $IdMaterial_, $Descripcion_, $IdProveedor_, $NombreProveedor_, $Facturable_, $FolioFactura_, $Total_) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            $conexion->obtenerNuevoIdTabla('CajaChicaDetalle');
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if ($FolioFactura_ == "") {
                    $folio = "NULL";
                }
                else {
                    $folio = "'". $FolioFactura_ ."'";
                }
                
                $query = "INSERT INTO CajaChicaDetalle (IdCajaChicaDetalle, IdCajaChica, IdObra, IdMaterial, Descripcion, IdProveedor, NombreProveedor, Facturable, FolioFactura, Total, Pagada)
                    SELECT ". $nueviId .", CajaChica.IdCajaChica, ". $IdObra_ .", ". $IdMaterial_ .", '$Descripcion_', ". $IdProveedor_ .", '$NombreProveedor_', ". $Facturable_ .", ". $folio .", ". $Total_ .", 0
                    FROM CajaChica
                    WHERE IdUsuario = ". $IdUsCajaChica_ .";";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "INSERT INTO ObraGasto (IdObra, IdCajaChicaDetalle, Tipo, EstadoPago, IdMaterial, NombreMaterial, Cantidad, Total, FechaMovimiento)
                    VALUES (". $IdObra_ .", ". $nueviId .", 'Caja Chica', 2, ". $IdMaterial_ .", '$Descripcion_', 1, ". $Total_ .", NOW());";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = 'UPDATE CajaChica SET Presupuesto = Presupuesto - '. $Total_ ." WHERE IdUsuario = ". $IdUsCajaChica_ .";";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SALIDA REGISTRADA';
                    $conexion->result['idCajaChicaDetalle'] = $nueviId;
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
            $conexion->result['result'] = "SALIDA NO REGISTRADA";
        }
        
        echo json_encode($conexion->result);
    }
    
    public function pagarFacturasCajaChica($IdUsuario_, $foliosFacturas_, $totalFacturas_, $idCajaChica_) {
        $conexion = new Conexion();
        $band_query_exito = 1;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE CajaChicaDetalle
                    SET Pagada = 1, FechaRegistroCorte = now()
                    WHERE IdCajaChicaDetalle IN (". $foliosFacturas_ .") AND IdCajaChica = (SELECT IdCajaChica FROM CajaChica WHERE IdUsuario = ". $IdUsuario_ .");";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                $query = "UPDATE CajaChica SET Presupuesto = Presupuesto + (SELECT SUM(Total) FROM CajaChicaDetalle WHERE IdCajaChicaDetalle IN (". $foliosFacturas_ .")) WHERE IdUsuario = ". $IdUsuario_ .";";

                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "INSERT INTO CajaChicaAbonos (IdCajaChica, TipoAbono, Cantidad, IdUsuario)
                    VALUES (". $idCajaChica_ .", 'REEMBOLSO DE FACTURAS', ". $totalFacturas_ .", 1);";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SE REGISTRARON LAS FACTURAS PAGADAS';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "NO SE REGISTRARON LAS FACTURAS PAGADAS, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "NO SE REGISTRARON LAS FACTURAS PAGADAS";
        }
        
        echo json_encode($conexion->result);
    }
    /**************************************************************************/
    public function reembolsar($IdCajaChica_, $Total_, $Descripcion_) { //falta guardar la descripcion
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE CajaChicaDetalle SET FechaRegistroCorte = now(), Pagada = 1 WHERE FechaRegistroCorte IS NULL AND FolioFactura IS NULL AND Pagada = '0' AND IdCajaChica = ". $IdCajaChica_;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "UPDATE CajaChica SET Presupuesto = Presupuesto + ". $Total_ ." WHERE IdCajaChica = ". $IdCajaChica_;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "INSERT INTO CajaChicaAbonos (IdCajaChica, TipoAbono, Cantidad, IdUsuario, Descripcion)
                    VALUES (". $IdCajaChica_ .", 'REEMBOLSO DE EFECTIVO', ". $Total_ .", 1, '$Descripcion_')";

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "SELECT last_insert_id() AS IdCajaChicaAbonos";
                $result = mysqli_query($conexion->mysqli, $query);
                $row = mysqli_fetch_array($result);
                
                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'CAJA CHICA CREADA';
                    $conexion->result['idCajaChicaAbonos'] = $row['IdCajaChicaAbonos'];
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
    
    public function cambiarEdoCajaChica($idCajaChica_,$estado_) {
        $conexion = new Conexion();
        $band_query_exito = 1;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE CajaChica SET Activa = ". $estado_ ." WHERE IdCajaChica = ". $idCajaChica_;

                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                if ($estado_ == 0) {
                    $query = "UPDATE CajaChicaDetalle SET Pagada = 1, Eliminado = now() WHERE IdCajaChica = ". $idCajaChica_;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                    }

                    $query = "UPDATE CajaChica SET Presupuesto = 0 WHERE IdCajaChica = ". $idCajaChica_;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                    }

                    $query = "UPDATE CajaChicaAbonos SET Eliminado = now() WHERE IdCajaChica = ". $idCajaChica_;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                    }
                }
                
                if ($band_query_exito == 1) {
                    $conexion->commit();
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
    
    public function obtenerPresupuestoUsuarioCajaChica($IdUsuario) {
        $presupuesto = -1;
        $conexion = new Conexion();
        $conexion->abrirBD();

        if ($conexion->mysqli != NULL) {
            $query = "SELECT Presupuesto FROM CajaChica Where IdUsuario = ". $IdUsuario;
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $presupuesto = $result['Presupuesto'];
            $conexion->cerrarBD();
        }
        
        return $presupuesto;
    }
    
    public function obtenerTotalFacturasCajaChica($foliosFacturas_) {
        $total = -1;
        $conexion = new Conexion();
        $conexion->abrirBD();

        if ($conexion->mysqli != NULL) {
            $query = "SELECT SUM(Total) AS TotalFacturas FROM CajaChicaDetalle WHERE IdCajaChicaDetalle IN (". $foliosFacturas_ .");";

            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $total = $result['TotalFacturas'];
            $conexion->cerrarBD();
        }

        return $total;
    }

    public function obtenerIdCajaChica($IdUsuario) {
        $id = -1;
        $conexion = new Conexion();
        $conexion->abrirBD();

        if ($conexion->mysqli != NULL) {
            $query = "SELECT IdCajaChica FROM CajaChica WHERE IdUsuario = ". $IdUsuario .";";
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $id = $result['IdCajaChica'];
            $conexion->cerrarBD();
        }
        return $id;
    }
    
    public function Facturar($idDetalleCajaChica_, $folioFactura_, $fecha_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE CajaChicaDetalle SET FolioFactura = '$folioFactura_', FechaFactura = '". $fecha_ ."' WHERE IdCajaChicaDetalle = ". $idDetalleCajaChica_;
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = "CAMBIOS GUARDADOS";
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