<?php
include_once 'config.php';

if(isset($_SESSION['username'])){
    header('Location: home.php');
}

if(isset($_POST['inicioSesion'])){

    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];
    
    if ($username != "" && $password != ""){
        
//        include 'clases/conexion.php';
        
        $con = new Conexion();
        
        $result = $con->iniciarSesion($username, $password);
        
        switch ($result)
        {
            case 0:
                $_SESSION['username'] = $username;
                header('Location: home.php');
                break;
            case 1:
                session_destroy();
                echo "Error de conexión.";
                break;
            case 2:
                session_destroy();
                echo "Nombre de usuario incorrecto.";
                break;
            case 3:
                session_destroy();
                echo "Contraseña incorrecta.";
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inicio de sesión</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>
<body class="hold-transition login-page">
    <div class="login-box">
      <!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Inicia sesión para comenzar</p>

        <form method="post" action="">
          <div class="form-group has-feedback">
              <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario" value="">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña" value="">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-md-6">
              <button type="submit" id="inicioSesion" name="inicioSesion" class="btn btn-primary btn-block btn-flat">Iniciar sesión</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!--<a href="#">Olvidé mi contraseña</a><br>-->
      </div>
      <!-- /.login-box-body -->
    </div>

</body>
</html>
