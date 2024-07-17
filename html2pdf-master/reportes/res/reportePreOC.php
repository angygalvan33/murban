<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../phpseclib');
include_once "Net/SSH2.php";

include "../../config.php";
include_once "../../clases/conexion.php";
include_once '../../clases/permisos.php';
include_once '../../clases/usuario.php'; 
include_once '../../clases/panelAdmin.php';
include_once '../../clases/comodin.php';

$permisos = new Permisos();
$usuario = new Usuario();
$comodin = new Comodin();

if(!isset($_SESSION['username'])){
    header('Location: ../../index.php');
}

if ($permisos->acceso("4096", $usuario->obtenerPermisos($_SESSION['username'])))
{
    $idProveedor = $_GET['idProveedor'];
    if ($idProveedor != 0) {
        $conexion = new Conexion();
        $conexion->abrirBD();
    
        $query = "SELECT * FROM Proveedor WHERE IdProveedor = ". $idProveedor;
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);        
        $proveedor = "PRE ORDEN DE COMPRA - ". $result["Nombre"];
        $conexion->cerrarBD();
    }
    else {
        $proveedor = "PREVIO PRESUPUESTO GENERAL";
    }
?>
<style>
    td, th {
        padding: 3px;
    }

    .header td{
        line-height: 15px;
    }
</style>

<page backtop="50mm" backbottom="15mm" backleft="6mm" backright="6mm">
    <page_header>
        <h4 style="width: 100%; text-align: center">
            <?php echo utf8_decode($proveedor) ?>
        </h4>
        <?php 
            $conexion = new Conexion();
            $conexion->abrirBD();
            
            $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $conexion->cerrarBD();
            $fecha = TransformaFecha(date("j-n-Y"));
            //$this->Cell(0,8, 'Fecha OC: ' . $this->TransformaFecha($fecha), 0, 1, 'R');
        ?>
        <?php
        $files = glob('../../images/logo/*');
        $file = $files[0];
        ?>
        <table style="width: 95%; font-size: 11px" class="header" align="center">
            <tr style="width: 100%;">
                <td style="width: 15%">
                    <img src="../../images/logo/<?php echo $comodin->nombreLogoEmpresa() ?>"  alt="Logo" style="width: 100%"/>
                </td>
                <td style="width: 85%">
                    <?php echo $result['Nombre']?>
                    <br><?php echo 'RFC: ' . $result['RFC']?>
                    <br><?php echo 'Direcci&oacute;n: ' . $result['Direccion']?>
                    <br><?php echo 'Tel&eacute;fono: ' . $result['Telefono']?>
                    <br><?php echo 'Compras: Yolanda Rivera' ?>
                    <br><?php echo 'E-mail: compras@murban.com.mx' ?>
                </td>
            </tr>
        </table>
        <div style="width: 97%; text-align: right; margin: 15px 0px 5px 0px; font-size: 10px">
            <?php echo 'Fecha de consulta: ' . TransformaFecha(date("j-n-Y")) ?>
        </div>
    </page_header>
    <page_footer>
        <table style="width: 90%" align="center">
            <tr>
                <td style="width: 25%"><i>Power By Bunraku</i></td>
                <td style="text-align: center; width: 50%">Página [[page_cu]]/[[page_nb]]</td>
                <td style="width: 25%"></td>
            </tr>
        </table>
    </page_footer>

    <table style="width: 100%; border-collapse: collapse;">
        <thead style="width: 100%; font-size: 15px;">
            <tr>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Cantidad
                </th>
                <th style="width: 25%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Material
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Precio Unitario
                </th>
                <th style="width: 15%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Fecha de cotización
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    SubTotal
                </th>
                <th style="width: 15%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Proyecto
                </th>
                <th style="width: 15%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Genera
                </th>
            </tr>
        </thead>
        <tbody style="width: 100%; border: solid 1px black;">
            <?php
            	$conexion = new Conexion();
            	$conexion->abrirBD();
                
                if ($idProveedor != 0)
            	   $query = "SELECT * FROM VistaRequisicionesPreOC WHERE IdProveedor = ". $idProveedor ." AND Seleccionada = 1";
                else
                    $query = "SELECT * FROM VistaRequisicionesPreOC AND Seleccionada = 1";
                
            	$result = mysqli_query($conexion->mysqli, $query);
            
            	while($row = mysqli_fetch_assoc($result)) {
	                $cantidad = $row['CantidadPedida'];
	                $material = $row['Material'];
	                $proveedor = $row['Proveedor'];
                    $precioUnitario = $row['PrecioUnitario'];
	                $subtotal = $row['PrecioUnitario']*$row['CantidadPedida'];
	                $proyecto = $row['Proyecto'];
	                $genera = $row['Genera'];
                    $f_cotizacion = $row['FechaCotizacion'];
            ?>
                <tr>
                    <td style="width: 10%; text-align: center;">
                        <?php echo $cantidad ?>
                    </td>
                    <td style="width: 25%">
                        <?php echo $material ?>
                    </td>
                    <td style="width: 10%; text-align: right">
                        <?php echo '$'. number_format($precioUnitario, 2) ?>
                    </td>
                    <td style="width: 15%; text-align: right">
                        <?php 
                            $date = new DateTime($f_cotizacion);
                            if ($idProveedor != 0)
                                echo $date->format('Y-m-d');
                            else
                                echo $date->format('Y-m-d') ." ". $proveedor;
                        ?>
                    </td>
                    <td style="width: 10%">
                        <?php echo '$'. number_format($subtotal, 2) ?>
                    </td>
                    <td style="width: 15%; text-align: right">
                        <?php echo utf8_encode($proyecto) ?>
                    </td>
                    <td style="width: 15%; text-align: right">
                        <?php echo utf8_decode($genera) ?>
                    </td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>

    <table style="width: 40%; border-collapse: collapse;" align="right">
        <?php
            if ($idProveedor != 0)
                $query = "SELECT SUM(PrecioUnitario * CantidadPedida) as Total FROM VistaRequisicionesPreOC WHERE IdProveedor = ". $idProveedor ." AND Seleccionada = 1";
            else
                $query = "SELECT SUM(PrecioUnitario * CantidadPedida) as Total FROM VistaRequisicionesPreOC AND Seleccionada = 1";

            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);        
            $conexion->cerrarBD();
        ?>
        <tr>
            <th style="width: 50%; border: solid 1px black; text-align: right;">
                Total:
            </th>
            <td style="width: 50%; text-align: right; border: solid 1px black;">
                <?php echo '$' . number_format($result['Total'], 2)?>
            </td>
        </tr>
    </table>
</page>
<?php
}
else {
    echo "Acceso denegado.";
}

function TransformaFecha($fecha) {
    $array = explode("-", $fecha);

    return substr($array[2],0,2) . '-' . $array[1] . '-' . $array[0];
}
?>