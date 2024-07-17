<?php

include_once 'conexion.php';

class MetodoCobro {
    
    private $id;
    private $nombre;
    private $referencia;
    
    public function __construct() 
    {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->referencia = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $nombre_,
                            $referencia_)
    {
        $this->id = $id_;
        $this->nombre = $nombre_;
        $this->referencia = $referencia_;
    }

    public function inserta()
    {
        $conexion = new Conexion();
        
        $conexion->existe('MetodoCobro', 'Nombre', "'" . $this->nombre . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0)
        {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE METODO DE COBRO REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try 
        {
            $conexion->obtenerNuevoIdTabla('MetodoCobro')['result'];
            $nueviId = $conexion->result['result'];


            if( $conexion->abrirBD()!=NULL)
            {
                $query = "INSERT INTO MetodoCobro
                (IdMetodoPago,
                Nombre,
                Referencia)
                VALUES
                (".$nueviId.",
                '$this->nombre',
                '$this->referencia');";
//                echo $query;
                if( mysqli_query($conexion->mysqli, $query) == TRUE)
                {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO INSERTADO';
//                    echo '1-';
                }
                else
                {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
//                echo "2-";
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
    
    public function baja($idMP)
    {
        $conexion = new Conexion();
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE MetodoCrobro
                    SET Eliminado = now()
                    WHERE IdMetodoCobro = ".$idMP;
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
        //return $conexion->result;
    }
    
    public function editar()
    {
        $conexion = new Conexion();
        
        $conexion->existe('MetodoCobro', 'Nombre', "'" . $this->nombre . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido > 0)
        {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE METODO DE COBRO REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try 
        {

            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE MetodoCobro
                SET
                Nombre = '$this->nombre',
                Referencia = '$this->referencia'
                WHERE IdMetodoCobro = ".$this->id;
                
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
    
    public function listadoMetodoCobro()
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            $query = "SELECT * 
            FROM MetodoCobro
            WHERE Eliminado IS NULL;";
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
        
        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode($txt);


        //echo json_encode($conexion->result['result']);
    }
    
    public function informacionMetodoCobro($idMetodoPago_)
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            $query = "Select Nombre, Referencia from MetodoCobro Where IdMetodoCobro = ".$idMetodoPago_." ;";
//            echo $query;
            $result = mysqli_query($conexion->mysqli, $query);
                        
            $conexion->result['result'] = $result->fetch_assoc();
            
            $conexion->cerrarBD();
            
            $conexion->result['error'] = 0;
//            $conexion->result['result'] = $listaMP;
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        $conexion->result['result'] = $conexion->utf8_converter($conexion->result['result']);
        
//        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode($conexion->result['result']);
    }
    
}
