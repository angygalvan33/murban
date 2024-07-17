<?php
include_once 'conexion.php';

class InventarioMaxMin {
    private $id;
    private $idMaterial;
    private $maximo;
    private $minimo;
    private $alerta;
    
    public function __construct() {
        $this->id = NULL;
        $this->idMaterial = NULL;
        $this->maximo = NULL;
        $this->minimo = NULL;
        $this->alerta = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $idMaterial_,
                            $maximo_,
                            $minimo_,
                            $alerta_) {
        $this->id = $id_;
        $this->idMaterial = $idMaterial_;
        $this->maximo = $maximo_;
        $this->minimo = $minimo_;
        $this->alerta = $alerta_;
    }

    public function inserta() {
        $conexion = new Conexion();
        
        if ($this->maximo <= $this->minimo) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "EL VALOR MÁXIMO DEBE DER MAYOR QUE EL VALOR MÍNIMO";
            echo json_encode($conexion->result);
            exit();
        }
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO InventarioMaxMin (IdMaterial, Maximo, Minimo, Alerta)
                    VALUES (". $this->idMaterial .", ". $this->maximo .", ". $this->minimo .", ". $this->alerta .");";

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
    
    public function baja($idMaterial) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        $bandMaxMinActivo = 0;

        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $query = "SELECT * FROM RequisicionDetalle WHERE IdObra = -1 AND  EdoPendienteAtender = 1 AND EdoAtendida = 1 AND EdoRecibida = 0 AND EdoCancelada = 0 AND IdMaterial = ". $idMaterial;

                $result = mysqli_query($conexion->mysqli, $query);
                $numRegistros = mysqli_num_rows($result);

                if (intval($numRegistros) > 0) {
                    $band_query_exito = 0;
                    $bandMaxMinActivo = 1;
                }
                else {
                    $query = "DELETE FROM InventarioMaxMin WHERE IdMaterial = ". $idMaterial;

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                    
                    $query = "SELECT * FROM RequisicionDetalle WHERE IdObra = -1 AND EdoPendienteAtender = 1 AND EdoAtendida = 0 AND EdoRecibida = 0 AND EdoCancelada = 0 AND IdMaterial = ". $idMaterial;
                    $result = mysqli_query($conexion->mysqli, $query);
                    $numRegistros2 = mysqli_num_rows($result);
                    
                    if (intval($numRegistros2) > 0) {
                        $row = mysqli_fetch_array($result);
                        $idRequisicionDetalle = $row['IdRequisicionDetalle'];

                        $query = "UPDATE RequisicionDetalle SET EdoAtendida = 1, EdoCancelada = 1, MotivoCancelacion = 'MAXIMO-MINIMO ELIMINADO' WHERE IdRequisicionDetalle = ". $idRequisicionDetalle;

                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }
                }
                
                if ($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO ELIMINADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                if ($bandMaxMinActivo == 1) {
                    $conexion->result['result'] = "NO SE PUEDE ELIMINAR, EXISTE UNA REQUISICION ACTIVA";
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
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE InventarioMaxMin SET IdMaterial = ". $this->idMaterial .", Maximo = ". $this->maximo .", Minimo = ". $this->minimo .", Alerta = ". $this->alerta ." WHERE IdInventarioMaxMin = ". $this->id;
                //echo $query;
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
}
