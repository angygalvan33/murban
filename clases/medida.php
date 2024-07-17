<?php

include_once 'conexion.php';

class Medida {
    
    private $id;
    private $nombre;
    private $metadato;
    private $comentario;
    
    public function __construct() 
    {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->metadato = NULL;
        $this->comentario = NULL;
    }
    

    public function getMedidas()
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            $query = "SELECT IdMedida,Nombre FROM Medida ORDER BY Nombre ASC";
//          echo $query;
            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $listaMP[]=$fila;
            }
            $conexion->cerrarBD();
            
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMP;
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getJsonMedidas($idMedida)
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            $query = "SELECT Metadato FROM Medida WHERE IdMedida = ".$idMedida;
          //echo $query;
            $result = mysqli_query($conexion->mysqli, $query);
//            while($fila = $result->fetch_assoc())
//            {
//                $listaMP[]=$fila;
//            }
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $result->fetch_assoc();
            
            $conexion->cerrarBD();
            
            
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    
}
