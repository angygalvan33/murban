<?php
include_once 'conexion.php';

class Usuario {
    private $id;
    private $nombre;
    private $usuario;
    private $email;
    private $password;
    private $permisos;
    private $cambioPassword;

    public function __construct() {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->usuario = NULL;
        $this->email = NULL;
        $this->password = NULL;
        $this->permisos = 0;
        $this->cambioPassword = NULL;
        $this->edo = 0;
    }
    
    public function llenaDatos($id_,
                                $nombre_,
                                $usuario_,
                                $email_) {
        $this->id = $id_;
        $this->nombre = $nombre_;
        $this->usuario = $usuario_;
        $this->email = $email_;
        $this->edo = 1;
    }
    
    public function inserta() {
        $conexion = new Conexion();
        $conexion->existe('Usuario', 'Usuario', "'" . $this->usuario . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE USUARIO REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        $conexion->existe('Usuario', 'Email',"'" . $this->email . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "CORREO ELECTRONICO REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            $conexion->obtenerNuevoIdTabla('Usuario')['result'];
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO Usuario
                (IdUsuario,
                Nombre,
                Email,
                Permisos,
                Usuario,
                Activo,
                Password,
                CambioPassword)
                VALUES
                (".$nueviId.",
                '$this->nombre',
                '$this->email',
                ".$this->permisos.",
                '$this->usuario',
                '$this->edo',
                0,0)";

                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
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

    public function baja($idUs) {
        $conexion = new Conexion();

        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Usuario SET Eliminado = now() WHERE IdUsuario = ". $idUs;
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO ELIMINADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
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
        $conexion->existe('Usuario', 'Usuario', "'" . $this->usuario . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE USUARIO REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        $conexion->existe('Usuario', 'Email',"'" . $this->email . "'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "CORREO ELECTRONICO REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Usuario SET Nombre = '$this->nombre', Email = '$this->email', Usuario = '$this->usuario' WHERE IdUsuario = ". $this->id;

                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
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
    
    public function activar($idUs) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Usuario SET Activo = 1 WHERE IdUsuario = ". $idUs;
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'USUARIO ACTIVADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "USUARIO NO ACTIVADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "USUARIO NO ACTIVADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function desactivar($idUs) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE Usuario SET Activo = 0 WHERE IdUsuario = ". $idUs;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'USUARIO DESACTIVADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "USUARIO NO DESACTIVADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "USUARIO NO DESACTIVADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function listado() {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Usuario WHERE Eliminado IS NULL";

            $result = mysqli_query($conexion->mysqli, $query);
            while ($fila = $result->fetch_assoc()) {
                $listaMP[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMP;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        echo json_encode($conexion->result['result']);
    }
    
    public function getIdFromUsername($usr) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT IdUsuario FROM Usuario WHERE Usuario = '". $usr ."'";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $idUsr = $result['IdUsuario'];
            $conexion->cerrarBD();
            return $idUsr;
        }
        else {
            return null;
        }
    }
    
    public function getNameFromUsername($usr) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT Nombre FROM Usuario WHERE Usuario = '". $usr ."'";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $nomb = $result['Nombre'];
            $conexion->cerrarBD();
            return $nomb;
        }
        else {
            return null;
        }
    }
    
    public function getPassFromUsername($usr) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT Password FROM Usuario WHERE Usuario = '". $usr ."'";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $pass = $result['Password'];
            $conexion->cerrarBD();
            return $pass;
        }
        else {
            return null;
        }
    }
    
    public function getUsuarioActivoFilter($busqueda) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT * FROM Usuario WHERE Activo = 1 AND Eliminado IS NULL AND Usuario like '%". $busqueda ."%' limit 5;";
            }
            else {
                $query = "SELECT * FROM Usuario WHERE Activo = 1 AND Eliminado IS NULL limit 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id"=>$row['IdUsuario'], "text"=>$row['Nombre']);
            }
            
            $data = $conexion->utf8_converter($data);
            echo json_encode($data);
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
    }
    
    public function getUsuariosCajaChica($busqueda, $conCaja) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($conCaja == 'IN') {
                if ($busqueda != null) {
                    $query = "SELECT * FROM Usuario WHERE Activo = 1 AND Eliminado IS NULL AND Usuario like '%". $busqueda ."%' AND IdUsuario ". $conCaja ." (SELECT IdUsuario FROM CajaChica WHERE Activa = 1) LIMIT 5;";
                }
                else {
                    $query = "SELECT * FROM Usuario WHERE Activo = 1 AND Eliminado IS NULL AND IdUsuario ". $conCaja ." (SELECT IdUsuario FROM CajaChica WHERE Activa = 1) LIMIT 5;";
                }
            }
            else {
                if ($busqueda != null) {
                    $query = "SELECT * FROM Usuario WHERE Activo = 1 AND Eliminado IS NULL AND Usuario LIKE '%". $busqueda ."%' AND IdUsuario ". $conCaja ." (SELECT IdUsuario FROM CajaChica) LIMIT 5;";
                }
                else {
                    $query = "SELECT * FROM Usuario WHERE Activo = 1 AND Eliminado IS NULL AND IdUsuario ". $conCaja ." (SELECT IdUsuario FROM CajaChica ) LIMIT 5;";
                }
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();

            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id"=>$row['IdUsuario'], "text"=>$row['Nombre']);
            }
            
            $data = $conexion->utf8_converter($data);
            echo json_encode($data);
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
    }
    
    public function establecerPermisos($idUs_, $permisos_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Usuario SET Permisos = ". $permisos_ ." WHERE IdUsuario = ". $idUs_;
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'PERMISOS EDITADOS';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "PERMISOS NO EDITADOS, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "PERMISOS NO EDITADOS";
        }
        
        echo json_encode($conexion->result);
    }

    public function obtenerPermisos($usr) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "SELECT Permisos FROM Usuario WHERE Usuario = '". $usr ."'";
                
                $result = mysqli_query($conexion->mysqli, $query);
                $result = mysqli_fetch_assoc($result);
                $permisos = $result['Permisos'];
                $conexion->cerrarBD();
                
                return $permisos;
            }
            else
                return -1;
        }
        catch (Exception $ex) {
            return -1;
        }
    }
    
    public function obtenerNombresPermisos($perms) {
        include_once 'conexion.php';
        include_once 'Math/BigInteger.php';
        
        $conexion = new Conexion();
        $nombres = array();
        $permisos = new Math_BigInteger($perms, 10);
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "SELECT * FROM Modulo WHERE Visible = 1";
                
                $resultado = mysqli_query($conexion->mysqli, $query);
                $i = 0;
                
                while ($row = mysqli_fetch_assoc($resultado)) {
                    $idMod = new Math_BigInteger($row['IdModulo'], 10);
                    $and = $idMod->bitwise_and($permisos);
                    
                    if (($and->compare($idMod)) == 0) {
                        $nombres[$i] = $row['Nombre'];
                        $i++;
                    }
                }
                
                $conexion->cerrarBD();
                return $nombres;
            }
            else
                return -1;
        }
        catch (Exception $ex) {
            return -1;
        }
    }
    
    public function encriptar($string) {
        return strrev(md5($string));
    }
    
    public function cambiarContrasena($idUsuario, $username, $contraAnt, $contraNueva, $contraConf) {
        $conexion = new Conexion();
        
        if ($contraNueva != $contraConf) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "Las contraseñas no coinciden.";
            echo json_encode($conexion->result);
            return;
        }
        
        if ($this->encriptar($contraAnt) != $this->getPassFromUsername($username)) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "Las contraseña es incorrecta.";
            echo json_encode($conexion->result);
            return;
        }
        
        $encPassword = $this->encriptar($contraNueva);
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Usuario SET Password = '$encPassword' WHERE IdUsuario = ". $idUsuario;
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'Contraseña actualizada.';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "CONTRASEÑA NO MODIFICADA";
        }
        echo json_encode($conexion->result);
    }
    //public function getUsuario(/*$idUsuario*/) {
    public function getUsuario($nombre) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            //$query = "SELECT * FROM Usuario WHERE Activo = 1 AND Eliminado IS NULL AND IdUsuario = ". $idUsuario ." LIMIT 1;";
            $query = "SELECT * FROM Usuario WHERE Nombre LIKE '%". $nombre ."%' AND Activo = 1 AND Eliminado IS NULL LIMIT 5;";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id"=>$row['IdUsuario'], "text"=>$row['Nombre']);
            }
            
            $data = $conexion->utf8_converter($data);
            echo json_encode($data);
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
    }
}