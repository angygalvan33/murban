<?php 
 $usr = $_SESSION['username'];
 $foto = "images/avatar.png";
 $prueba = 0;
?>

<script src="Menu/TopMenu/topMenuScript.js" type="text/javascript"></script>

<header class="main-header"><meta charset="gb18030">
    <a href="index.php" class="logo">
      <span class="logo-lg"><img src="images/bunrakuerp.png" style="width:25%"></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo $foto ?>" class="user-image" alt="User Image">
            <span class="hidden-xs"><?php echo $usr ?> </span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?php echo $foto ?>" class="img-circle" alt="User Image">
                <p> <?php echo $usr?>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-right">
                    <button class="btn btn-warning btn-flat" name="cambiarContra" id="cambiarContra" style="margin-bottom: 10px">Cambiar Contraseña</button>
                    <form method="post" action="">
                        <input type="submit" name="cerrarSesion" class="btn btn-default btn-flat" style="float: right" value="Cerrar sesión">
                    </form>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
</header>

<!--success modal-->
<div id="successModal" class="modal modal-success fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">REGISTRO</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button id="aceptar" type="button" class="btn btn-outline" data-dismiss="modal">Aceptar</button>
              </div>
        </div>
    </div>
</div>

<!--error modal-->
<div id="errorModal" class="modal modal-danger fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminación</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                <button id="aceptar" type="button" class="btn btn-outline" data-dismiss="modal">Aceptar</button>
              </div>
        </div>
    </div>
</div>

<!--avisos modal-->
<div id="avisosModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                
                <h4 class="modal-title"></h4>
                
            </div>
            
            <div class="modal-body"></div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Aceptar</button>
              </div>
        </div>
    </div>
</div>

<?php 
    include 'cambiarContrasena.php'; 
    //include '../../commonModals.html';
?>

<script type="text/javascript">
    $( document ).ready(function() {
        
        $( document ).on( 'click', 'button', function () {
            
            switch($(this).attr("id"))
            {
                case "cambiarContra":
                    loadCambiarContra(<?php echo "'" . $usr . "'"; ?>);
                    break;
            }

        });
        
    });
    
</script>