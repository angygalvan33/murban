<?php

include_once 'conexion.php';
class Modulo{
    //put your code here
    
    
    public function __construct() 
    {
        
    }
   
    
    public function listado($id=0)
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            if($id == 0)
                $query = "SELECT * FROM Modulo";
            else
                $query = "SELECT * FROM Modulo WHERE IdModulo = ".$id;
//            echo $query;
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
    
    
}
