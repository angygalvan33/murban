<?php
    include_once './clases/permisos.php';
    include_once './clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<!-- Menú izquierdo -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">CUENTAS POR PAGAR</li>
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Configuración</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if ($permisos->acceso("17179869184", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="panelAdmin"><i class="fa fa-unlock-alt"></i> General </a></li>
                    <?php endif; ?>
                    <?php if ($permisos->acceso("1", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="usuarios"><i class="fa fa-child"></i> Usuarios </a></li>
                    <?php endif; ?>
    			    <?php if ($permisos->acceso("16", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="metodosPago"><i class="fa fa-credit-card-alt"></i> Métodos de Pago </a></li>
                    <?php endif; ?>
    			    <?php if ($permisos->acceso("256", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="categorias"><i class="fa fa-object-group"></i> Categorías de Materiales </a></li>
                    <?php endif; ?>
                    <?php if($permisos->acceso("4294967296", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="personal"><i class="fa fa-user-o"></i> Personal </a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Administración</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
    		        <?php if ($permisos->acceso("274877906944", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="clientes"><i class="fa fa-user-o"></i> Clientes </a></li>
                    <?php endif; ?>
			        <?php if ($permisos->acceso("1024", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="obras"><i class="fa fa-home"></i> Proyectos </a></li>
                    <?php endif; ?>
			        <?php if ($permisos->acceso("2199023255552", $usuario->obtenerPermisos($_SESSION['username']))): ?>
			            <li><a href="#" id="cuentasPorCobrar"><i class="fa fa-money"></i> Cuentas por cobrar </a></li>
			        <?php endif; ?>
			        <?php if ($permisos->acceso("4194304", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="cxpagar"><i class="fa fa-calculator"></i> Cuentas por pagar </a></li>
                    <?php endif; ?>
			        <?php if ($permisos->acceso("268435456", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="detallepagos"><i class="fa fa-folder-open-o"></i> Detalle de pagos </a></li>
                    <?php endif; ?>
		        </ul>
		    </li>
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-usd"></i>
                    <span>Compras</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
			        <?php if ($permisos->acceso("2", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="proveedores"><i class="fa fa-address-book"></i> Proveedores </a></li>
                    <?php endif; ?>
                    <?php if ($permisos->acceso("64", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="materiales"><i class="fa fa-cubes"></i> Materiales </a></li>
                    <?php endif; ?>
                    <?php if ($permisos->acceso("4096", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="ocompra"><i class="fa fa-list-ol"></i> Órdenes de compra </a></li>
                    <?php endif; ?>
                    <?php if ($permisos->acceso("549755813888", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="concentradoOC"><i class="fa fa-history"></i> Reportes </a></li>
			        <?php endif; ?>
                    <?php if ($permisos->acceso("64", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="articulos"><i class="fa fa-linode"></i> Productos </a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-usd"></i>
                    <span>Requisiciones</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
		            <?php if ($permisos->acceso("1099511627776", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="requisiciones"><i class="fa fa-file-text-o"></i> Requisiciones </a></li>
			        <?php endif; ?>
		        </ul>
            </li>
            <?php if ($permisos->acceso("4398046511104", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-cubes"></i>
                        <span>Almacén</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($permisos->acceso("8796093022208", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="configuracion"><i class="fa fa-cogs"></i> Configuración </a></li>
                        <?php endif; ?>
                        <li><a href="#" id="almacen"><i class="fa fa-cart-arrow-down"></i> Movimientos </a></li>
                        <li><a href="#" id="materialesEnPrestamo"><i class="fa fa-arrows-h"></i> Préstamo y Resguardo </a></li>
                        <li><a href="#" id="ubicaciones"><i class="fa fa-map-marker"></i> Ubicaciones </a></li>
                    </ul>
                </li>
		    <?php endif; ?>
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-usd"></i>
                    <span>Caja Chica</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
		            <?php if ($permisos->acceso("536870912", $usuario->obtenerPermisos($_SESSION['username']))): ?>
                        <li><a href="#" id="cajaChica"><i class="fa fa-object-group"></i> Caja chica </a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#panelAdmin").click( function() {
            loadPanelAdmin();
        });
        
        $("#usuarios").click( function() {
            loadUsuarios();
        });
        
        $("#ocompra").click( function() {
            loadOrdenesCompra();
        });
        
        $("#proveedores").click( function() {
            loadProveedores();
        });
        
        $("#metodosPago").click( function() {
            loadMetodosPago();
        });
        
        $("#materiales").click( function() {
            loadMateriales();
        });
        
        $("#categorias").click( function() {
            loadCategorias();
        });
        
        $("#obras").click( function() {
            loadObras();
        });
        
        $("#cxpagar").click( function() {
            loadCuentasPorPagar();
        });
        
        $("#detallepagos").click( function() {
            loadDetallePagos();
        });
        
        $("#cajaChica").click( function() {
            loadCajaChica();
        });
        
        $("#requisiciones").click( function() {
            loadRequisiciones();
        });
        
        $("#personal").click( function() {
            loadPersonal();
        });
        //movimientos
        $("#almacen").click( function() {
            loadAlmacen();
        });
        
        $("#articulos").click( function() {
            loadArticulos();
        });
        
        $("#materialesEnPrestamo").click( function() {
            loadMaterialesEnPrestamo();
        });
        
        $("#ubicaciones").click( function() {
            loadUbicaciones();
        });
        
        $("#configuracion").click( function() {
            loadConfiguracion();
        });
        
        $("#concentradoOC").click( function() {
            loadConcentradoOrdenesCompra();
        });
        
        $("#clientes").click( function() {
            loadClientes();
        });
        
        $("#cuentasPorCobrar").click( function() {
            loadCuentasPorCobrar();
        });

        /*$("#products").click( function() {
            loadProducts();
        });*/
    });
</script>