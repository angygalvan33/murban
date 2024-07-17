<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of conexion
 *
 * @author DELL
 */
class Conexion {
    
    private $servername;
    private $username;
    private $password;
    private $dbname;
    
    public $mysqli;
    public $result;

    public function __construct() 
    {
        $this->result = array();
        $this->result['error'] = '';
        $this->result['result'] = '';

        //$this->servername = "192.185.131.153";192.185.131.189
//        $this->servername = "192.185.131.189";
//        $this->username = "innovace_program";
//        $this->password = "Innovace_program#";
//        $this->dbname = "innovace_cxp";

//        $this->servername = "199.250.214.246";
//        $this->username = "bunraku_admin";
//        $this->password = "Admin%99!";
//        $this->dbname = "bunraku_cxp";

        /*$this->servername = "199.250.214.246";
        $this->username = "bunraku_murban_admin";
        $this->password = "Admin%99!";
        $this->dbname = "bunraku_murban1";*/
        
        $this->servername = "127.0.0.1";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "bunraku_murban";

//        $this->servername = "199.250.214.246";
//        $this->username = "bunraku_construmar_admin";
//        $this->password = "Admin%99!";
//        $this->dbname = "bunraku_construmar";
        
//        $this->servername = "199.250.214.246";
//        $this->username = "bunraku_cyg_admin";
//        $this->password = "Admin%99!";
//        $this->dbname = "bunraku_cyg";

//        $this->servername = "199.250.214.246";
//        $this->username = "bunraku_sanninox_admin";
//        $this->password = "Admin%99!";
//        $this->dbname = "bunraku_sanninox";

        $this->mysqli=null;
    }
    
    public function iniciarSesion($usr, $pass)
    {
        include_once 'usuario.php';
        $usuarioTMP = new Usuario();
        
        $this->abrirBD();
        if($this->mysqli!=NULL)
        {
            $query = "SELECT COUNT(*) AS numeroRegistros FROM Usuario WHERE Usuario = '" . $usr . "' AND Activo = 1 AND Eliminado IS NULL";
            $result = mysqli_query($this->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRegistros = $result['numeroRegistros'];
            $this->result['query1'] = $query;
            
            if($numRegistros == 1)
            {
                $query = "SELECT Password FROM Usuario WHERE Usuario = '" . $usr . "'";
                $result = mysqli_query($this->mysqli, $query);
                $result = mysqli_fetch_assoc($result);
                $password = $result['Password'];
                $this->result['query2'] = $query;
                
//                echo $this->result;
                $this->cerrarBD();
                
                if($usuarioTMP->encriptar($pass) == $password)
                    return 0;
                else
                    return 3;
            }
            else
            {
                $this->cerrarBD();
                return 2;
            }
        }
        else
            return 1;
    }
    
    public function abrirBD()
    {
        $this->mysqli = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($this->mysqli->connect_error) 
        {
            return NULL;
            //die("Connection failed: " . $mysqli->connect_error);
        }
        else
        {
            return $this->mysqli;
        }
    }
    
    public function cerrarBD()
    {
        //echo '<br/>('.$this->mysqli->connect_error.')<br/>';
        if (!$this->mysqli->connect_error) 
        {
            mysqli_close($this->mysqli);
        }
    }
    
    public function iniciaTransaccion()
    {
//        mysqli_begin_transaction($this->mysqli, MYSQLI_TRANS_START_READ_WRITE);
        mysqli_autocommit($this->mysqli, FALSE);
    }
    
    public function commit()
    {
        $var_commit = mysqli_commit($this->mysqli);
        //echo '<br/>Commit: '.$var_commit.'<br/>';
        return $var_commit;
    }
    
    public function rollback()
    {
        
        //echo '<br/>Rollback: '.mysqli_rollback($this->mysqli).'<br/>';
        mysqli_rollback($this->mysqli);
    }

    public function obtenerNuevoIdTabla($nombreTabla)
    {
        $this->abrirBD();
        if($this->mysqli!=NULL)
        {
            $query = "SELECT COUNT(*) AS numeroRegistros FROM ".$nombreTabla;
            $result = mysqli_query($this->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRegistros = $result['numeroRegistros'];
            
            $this->cerrarBD();
            $this->result['error'] = 0;
            $this->result['result'] = $numRegistros+1;
        }
        else
        {
            $this->result['error'] = 1;
            $this->result['result'] = "Error conexion bd";
        }
    }
    
    public function obtenerNuevoIdUsuario()
    {
        $this->abrirBD();
        if($this->mysqli!=NULL)
        {
            $query = "SELECT COUNT(*) AS numeroRegistros FROM Usuario";
            $result = mysqli_query($this->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRegistros = $result['numeroRegistros'];
            
            $this->cerrarBD();
            $this->result['error'] = 0;
            $this->result['result'] = $numRegistros+1;
        }
        else
        {
            $this->result['error'] = 1;
            $this->result['result'] = "Error conexion bd";
        }
        
        echo json_encode($this->result);
    }

    
    /*
     *  La función existe regresa el número de veces que una tabla contiene un
     *  valor en un campo, ambos recibidos como parámetros.
     */
    
    public function existe($nombreTabla, $campo, $valor, $id)
    {
        $this->abrirBD();
        if($this->mysqli!=NULL)
        {
            $query = "SELECT COUNT(*) AS numeroRegistros FROM " . $nombreTabla . " WHERE REPLACE(" . $campo . ", ' ', '') = REPLACE(" . $valor . ", ' ', '') AND Id" . $nombreTabla . " != " . $id . " AND Eliminado IS NULL";
            $result = mysqli_query($this->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRegistros = $result['numeroRegistros'];
            
            $this->cerrarBD();
            $this->result['error'] = 0;
            $this->result['result'] = $numRegistros;
        }
        else
        {
            $this->result['error'] = 1;
            $this->result['result'] = "Error conexion bd";
        }
    }
    
    public function existeInventarioInicial($idMaterial)
    {
        $this->abrirBD();
        if($this->mysqli!=NULL)
        {
            $query = "SELECT COUNT(*) AS numeroRegistros FROM Inventario WHERE IdMaterial = " . $idMaterial  . " AND IdObra = -1 AND Eliminado IS NULL";
            $result = mysqli_query($this->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRegistros = $result['numeroRegistros'];
            
            $this->cerrarBD();
            $this->result['error'] = 0;
            $this->result['result'] = $numRegistros;
        }
        else
        {
            $this->result['error'] = 1;
            $this->result['result'] = "Error conexion bd";
        }
    }
    
    
    public function existeMaterial($valorNombre, $valorMedida, $id)
    {
        $this->abrirBD();
        if($this->mysqli!=NULL)
        {
            $query = "SELECT COUNT(*) AS numeroRegistros FROM Material WHERE REPLACE(Nombre, ' ', '') =  REPLACE('" . $valorNombre . "', ' ', '') AND Medida = " .$valorMedida. " AND IdMaterial != " . $id . " AND Eliminado IS NULL";
            $result = mysqli_query($this->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRegistros = $result['numeroRegistros'];
            
            $this->cerrarBD();
            $this->result['error'] = 0;
            $this->result['result'] = $numRegistros;
        }
        else
        {
            $this->result['error'] = 1;
            $this->result['result'] = "Error conexion bd";
        }
    }
    
    public function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_encode($item);
            }
        });

        return $array;
    }
    
}
