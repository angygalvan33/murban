<?php

include_once 'conexion.php';

class TipoObra {
    
    private $id;
    private $nombre;
    
    public function __construct() 
    {
        $this->id = NULL;
        $this->nombre = NULL;
    }

    public function getTiposObra()
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            $query = "SELECT IdTipoObra, Nombre FROM TipoObra WHERE IdTipoObra > -1 ORDER BY Nombre ASC";
//          echo $query;
            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $listaTO[]=$fila;
            }
            $conexion->cerrarBD();
            
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaTO;
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
//        var_dump($conexion->result['result']);
//        print json_encode($conexion->result['result']);
        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode(array("error"=>$conexion->result['error'],"result"=>$txt));
        
    }
    
    
}
