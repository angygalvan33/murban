<?php

include_once 'conexion.php';

class Correo {
    private $email;
    private $pass;
    private $idUs;
    private $usuario;

    public function __construct($idUs_) 
    {
        $this->idUs = $idUs_;
        $this->datosUsuario();
    }
    
    public function datosUsuario()
    {
        $conexion = new Conexion();
        
        if( $conexion->abrirBD()!=NULL)
        {
            $query = "SELECT * 
            FROM Usuario
            WHERE IdUsuario = ".$this->idUs;

            $result = mysqli_query($conexion->mysqli, $query);
           
            $fila = $result->fetch_assoc();
            $this->usuario = $fila['Usuario'];
            $this->email = $fila['Email'];
            $this->pass = substr( md5(microtime()), 1, 8);
            
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
    }
    
    public function enviarCorreo()
    {
        
        $to = $this->email;
        $subject = "Bienvenido al sistema";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $message = "
        <html>
            <head>
                <title>Bienvenido</title>
            </head>
            <body>
                <div>
                    <div style='float: left;'><img src='../images/logo_empresa.png' alt='' width='85%'></div>
                    <div>
                        <table>
                            <tr><td colspan='2'>Bienvenido al Sistema Bunraku Murban</td></tr>
                            <tr><td colspan='2'>Tus credenciales son:</td></tr>
                            <tr>
                                <td>Usuario:</td>
                                <td>$this->usuario</td>
                            </tr>
                            <tr>
                                <td>Pass:</td>
                                <td>$this->pass</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </body>
        </html>";

        mail($to, $subject, $message, $headers);
        
        $this->restablecerPassword();
    }
    
    public function restablecerPassword()
    {
        include_once 'usuario.php';
        $usr = new Usuario();
        $encPassword = $usr->encriptar($this->pass);
        
        $conexion = new Conexion();
        try 
        {
            if( $conexion->abrirBD()!=NULL)
            {
                $query = "UPDATE Usuario 
                    SET Password = '$encPassword' 
                    WHERE IdUsuario = ".$this->idUs;
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
    
}
