<?php

include_once 'conexion.php';

class ObraDetalle {
    
    private $id;
    private $IdObra;
    private $IdArticulo;
    private $Cantidad;
    
    public function __construct() 
    {
        $this->id = NULL;
        $this->IdObra = NULL;
        $this->IdArticulo = NULL;
        $this->Cantidad = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $IdObra_,
                            $IdArticulo_,
                            $Cantidad_)
    {
        $this->id = $id_;
        $this->IdObra = $IdObra_;
        $this->IdArticulo = $IdArticulo_;
        $this->Cantidad = $Cantidad_;
    }
    
    public function inserta()
    {
        $conexion = new Conexion();
        
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "INSERT INTO ObraDetalle
                (IdObra,
                IdArticulo,
                Cantidad)
                VALUES
                (".$this->IdObra.",
                 ".$this->IdArticulo.",
                 ".$this->Cantidad.");";
                
                if( mysqli_query($conexion->mysqli, $query) == TRUE)
                {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO INSERTADO';
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
                $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
            }
        } 
        catch (Exception $ex) 
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function baja($idObraDetalle)
    {
        $conexion = new Conexion();
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE ObraDetalle
                    SET Eliminado = now()
                    WHERE IdObraDetalle = ".$idObraDetalle;
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
    }
    
    public function editar()
    {
        $conexion = new Conexion();
        
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE ObraDetalle
                SET
                Cantidad = " . $this->Cantidad . "
                WHERE IdObraDetalle = " . $this->id;
                
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
    }
    
    public function solicitar($idObraDetalle_)
    {
        $conexion = new Conexion();
        
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE ObraDetalle
                SET
                Solicitado = 1
                WHERE IdObraDetalle = " . $idObraDetalle_;
                
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
    }
}
