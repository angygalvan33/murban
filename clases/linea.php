<?php

include_once 'conexion.php';

class Linea {
    private $id;
    private $nombre;
    
    public function __construct() 
    {
        $this->id = NULL;
        $this->nombre = NULL;
    }
    
    public function llenaDatos(
                            $id_,
                            $nombre_)
    {
        $this->id = $id_;
        $this->nombre = $nombre_;
    }

    public function inserta()
    {
        $conexion = new Conexion();
        
        $conexion->existe('Linea', 'Nombre', "'" . $this->nombre . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido == 0)
        {
            try 
            {
                $conexion->obtenerNuevoIdTabla('Linea');
                $nueviId = $conexion->result['result'];


                if( $conexion->abrirBD()!=NULL)
                {
                    $query = "INSERT INTO Linea
                    (IdLinea,
                    Nombre)
                    VALUES
                    (".$nueviId.",
                    '$this->nombre');";
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
        }
        else
        {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE LÍNEA REPETIDO.";
        }
        
        echo json_encode($conexion->result);
    }
    
    public function baja($idLinea)
    {
        $conexion = new Conexion();
        
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE Linea
                    SET Eliminado = now()
                    WHERE IdLinea = ".$idLinea;
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
        
        $conexion->existe('Linea', 'Nombre', "'" . $this->nombre . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if($repetido == 0)
        {
            try 
            {
                if( $conexion->abrirBD()!=NULL)
                {
                    $query = "UPDATE Linea
                    SET
                    Nombre = '$this->nombre'
                    WHERE IdLinea = ".$this->id;

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
        }
        else
        {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE LÍNEA REPETIDO.";
        }
        echo json_encode($conexion->result);
    }
    
    public function getLineas()
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            $query = "SELECT IdLinea,Nombre FROM Linea WHERE Eliminado IS NULL";
//          echo $query;
            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $listaLin[]=$fila;
            }
            $conexion->cerrarBD();
            
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaLin;
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode(array("error"=>$conexion->result['error'],"result"=>$txt));
    }
    
    public function getLineasFilter($busqueda)
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            if($busqueda!=null)
            {
                $query = "SELECT * FROM Linea WHERE Eliminado IS NULL AND Nombre like '%".$busqueda."%' limit 5;";
            }
            else
            {
                $query = "SELECT * FROM Linea WHERE Eliminado IS NULL limit 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) 
            {    
                $data[] = array("id"=>$row['IdLinea'], "text"=>$row['Nombre']);
            }
             $data = $conexion->utf8_converter($data);
            echo json_encode($data);
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
    }
}
