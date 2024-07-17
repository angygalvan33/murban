<?php

include_once 'conexion.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of historicoPrecioPartes
 *
 * @author rafael
 */
class HistoricoPrecioaPub {
    
    private $id;
    private $idParte;
    private $idTipo;
    private $idUsuarioUpdate;
    private $precio;
    private $iva;
    private $moneda;

    public function __construct() 
    {
        $this->id = NULL;
        $this->idParte = NULL;
        $this->idTipo = 0;
        $this->precio = NULL;
        $this->iva = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $idParte_,
                            $idTipo_,
                            $precio_,
                            $iva_,
                            $moneda_)
    {
        $this->id = $id_;
        $this->idParte = $idParte_;
        $this->idTipo = $idTipo_;
        $this->precio = $precio_;
        $this->iva = $iva_;
        $this->moneda = $moneda_;
    }
    
    public function guardarPrecio($accion)
    {
        $conexion = new Conexion();
        $comodin = new Comodin();
        
        try 
        {
            $conexion->obtenerNuevoIdTabla('HistoricoPrecioaPub');
            $nueviId = $conexion->result['result'];

            if( $conexion->abrirBD()!=NULL)
            {
                $query = "CALL InsertaPrecioaPub('$accion', ".$nueviId.", ".$this->idParte.", ".$comodin->idUsuarioSession().", ".$this->precio.", ".$this->iva.", '$this->moneda', ".$this->idTipo.");";

                //echo "********************".$query."******************** ";

                $result = mysqli_query($conexion->mysqli, $query);
                $fila = $result->fetch_assoc();
            
                $conexion->result['error'] = $fila['error_'];
                $conexion->result['result'] = $fila['msg'];
                        
                $conexion->cerrarBD();
            }
            else
            {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) 
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
        }
        echo json_encode($conexion->result);
    }

    function getPrecioPub($idMaterial, $idTipo){
        $conexion = new Conexion();
        $precio = 0;
        $band_query_exito = 1;

        try
        {
            if( $conexion->abrirBD() != NULL)
            {
                $query = "SELECT Precio FROM HistoricoPrecioaPub WHERE IdParte = ". $idMaterial ." AND IdTipo=". $idTipo ." ORDER BY Precio DESC LIMIT 1;";

                if(!mysqli_query($conexion->mysqli, $query))
                {
                    $band_query_exito = 0;
                }

                if( $band_query_exito > 0 )
                {
                    $result = mysqli_query($conexion->mysqli, $query);
                    $row = mysqli_fetch_array($result);
                    if($row !== null){
                        $precio = $row['Precio'];
                    }
                    
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = $precio;
                    $conexion->cerrarBD();
                }
                else
                {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $precio;
                }
            }
            else
            {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $precio;
            }
        }
        catch (Exception $ex)
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $precio;
        }
        echo json_encode($conexion->result);
    }
}