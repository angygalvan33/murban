<?php

include "config.php";

// Check user login or not
if(!isset($_SESSION['username'])){
    header('Location: index.php');
}

if(isset($_POST['cerrarSesion'])){
    session_destroy();
    header('Location: index.php');
}

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include_once "Net/SSH2.php";
include_once 'Math/BigInteger.php';

?>

<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>CUENTAS POR PAGAR</title>
      
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
      <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
      <link rel="stylesheet" href="dist/css/skins/skin-green-light.min.css">
      <!-- Google Font -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
      <!-- jQuery 3 -->
      <script src="bower_components/jquery/dist/jquery.min.js"></script>
      <!-- Bootstrap 3.3.7 -->
      <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
      <!-- AdminLTE App -->
      <script src="dist/js/adminlte.min.js"></script>
      
      <link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
    <script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>
    <script src="bower_components/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <link href="bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <script src="plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <script src="plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
    <link href="plugins/iCheck/all.css" rel="stylesheet" type="text/css"/>
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link href="bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>
    <script src="bower_components/moment/min/moment.min.js"></script>
    <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
    
    <?php 
        include 'commonModals.html';   
    ?>
      
      
      <script src="Menu/LeftMenu/leftMenuScript.js" type="text/javascript"></script>
      <link href="estilosComunes.css" rel="stylesheet" type="text/css"/>
    </head>
    
    <body class="hold-transition skin-green-light sidebar-mini">
        <div class="wrapper">

            <?php 
                include_once 'Menu/TopMenu/topMenu.php'; 
                include_once 'Menu/LeftMenu/leftMenu.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <h3>BIENVENIDO AL SISTEMA</h3>

                    <?php include_once 'Inicio/inicio.php' ?>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <?php include_once 'footer.php' ?>
        </div>
        <!-- ./wrapper -->
    </body>
    
    <div id="loading" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-body" style="text-align: center">
                <img src="images/ajax-loader.gif" alt=""/>
                <p>Espera un momento por favor</p>
            </div>
          </div>
        </div>
    </div>
    
</html>

