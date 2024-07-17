<?php

//require('../mysql_table.php');

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
    // Recibir ID y agregar página horizontal (L = Landscape)

    $idOrdenCompra = $_GET["id"];
	$mPago = $_GET["mpago"];
	$usoCFDI = $_GET["ucfdi"];
	$formadepago = $_GET["fpago"];
	
	$smPago = 'PUE:Pago en una sola exhibición';
    $susoCFDI = 'G01:Adquisición de Mercancías';
    $sformadepago = '01:Efectivo';

    if($mPago == 2)
        $smPago = 'PPD:Pago parcial diferido';
    
    if($usoCFDI == 2)
        $susoCFDI = 'G03:Gastos en General';
    
    if($formadepago == 2)
        $sformadepago = '03:Transferencia Electrónica';
    else if($formadepago == 3)
       $sformadepago = '04:Tarjeta de Crédito';
    else if($formadepago == 4)
       $sformadepago = '28:Tarjeta de Débito';
    else if($formadepago == 5)
       $sformadepago = '99:Por definir';
?>

<style>
    td, th {
        padding: 3px;
    }

    .header td{
        line-height: 15px;
    }
</style>

<page backtop="75mm" backbottom="15mm" backleft="6mm" backright="6mm">
    <page_header>
        <h4 style="width: 100%; text-align: center">
            Orden de Compra
        </h4>
        <?php 
            $conexion = new Conexion();
            $conexion->abrirBD();
            
            $query = "SELECT * FROM OrdenCompra WHERE OrdenCompra.IdOrdenCompra = " . $_GET['id'];
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $fecha = $result['Creado'];
            $idProv = $result['IdProveedor'];
            $numCot = $result['NumCotizacion'];
            $folioOC = $result['FolioOC'];

            $query = "SELECT Nombre FROM Proveedor WHERE Proveedor.IdProveedor = " . $idProv;
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $proveedor = $result['Nombre'];
            
            $query = "SELECT u1.Nombre AS Genera, u2.Nombre AS Autoriza FROM OrdenCompra INNER JOIN Usuario u1 ON OrdenCompra.IdUsuario = u1.IdUsuario INNER JOIN Usuario u2 ON OrdenCompra.IdUsuarioAutoriza = u2.IdUsuario WHERE IdOrdenCompra = " . $_GET['id'];
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $genera = $result['Genera'];
            $autoriza = $result['Autoriza'];
            
            $query = "SELECT Nombre, Referencia FROM MetodoPago INNER JOIN OrdenCompra ON OrdenCompra.IdMetodoPago = MetodoPago.IdMetodoPago WHERE OrdenCompra.IdOrdenCompra = " . $_GET['id'];
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $metodoPago = $result["Nombre"];
            $ref = $result["Referencia"];
            
            $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $conexion->cerrarBD();
            //$this->Cell(0,8, 'Fecha OC: ' . $this->TransformaFecha($fecha), 0, 1, 'R');
        ?>

        <table style="width: 95%; font-size: 11px" class="header" align="center">
            <tr style="width: 100%;">
                <td style="width: 25%">
                    <img src="../../images/logo/<?php echo $comodin->nombreLogoEmpresa() ?>"  alt="Logo" style="width: 100%"/>
                </td>
                <td style="width: 45%">
                    <?php echo $result['Nombre']?>
                    <br><?php echo 'RFC: ' . $result['RFC']?>
                    <br><?php echo 'Direcci&oacute;n: ' . $result['Direccion']?>
                    <br><?php echo 'Tel&eacute;fono: ' . $result['Telefono']?>
                    <br><?php echo 'Representante: ' . $result['Representante']?>
                    <br><?php echo 'E-mail: ' . $result['Email']?>
                </td>
                <td style="width:30%">
                    <h4 style="font-size: 20px ; margin : 0px !important;"><?php echo 'Folio OC: ' . $folioOC ?></h4>
                    <br><?php echo 'Proveedor: ' . $proveedor?>
                    <br><?php echo 'No. cotizaci&oacute;n: ' . $numCot?>
                    <br><?php echo 'Genera: ' . $genera?>
                    <br><?php echo 'Autoriza: ' . $autoriza?>
                    <br><?php echo 'M&eacute;todo de pago: ' . $smPago?>
                    <br><?php echo 'Uso de CFDI: ' . $susoCFDI?>
					<br><?php echo 'Forma de pago: ' . $sformadepago?>
                </td>
            </tr>
        </table>
        <div style="width: 97%; text-align: right; margin: 15px 0px 5px 0px; font-size: 10px">
            <?php echo 'Fecha OC: ' . TransformaFecha($fecha) ?>
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
                <th style="width: 15%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Cantidad
                </th>
                <th style="width: 45%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Material
                </th>
                <th style="width: 20%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Precio unitario
                </th>
                <th style="width: 20%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Subtotal
                </th>
            </tr>
        </thead>
        <tbody style="width: 100%; border: solid 1px black;">
            <?php
            //for ($i = 0; $i < 5; $i++){
            $conexion = new Conexion();
            $conexion->abrirBD();
            
            $qr = "SELECT IdMaterial, Nombre, SUM(Cantidad) AS Cantidad, PrecioUnitario, SUM(Subtotal) AS Subtotal FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND IdMaterial != -1 GROUP BY IdMaterial
                  UNION SELECT IdMaterial, Nombre, SUM(Cantidad), PrecioUnitario, SUM(Subtotal) FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND IdMaterial = -1 GROUP BY Nombre, PrecioUnitario";
            
            $result = mysqli_query($conexion->mysqli, $qr);
            
            while($row = mysqli_fetch_assoc($result)) {
                $idMat = $row['IdMaterial'];
                $qr = "SELECT Medida FROM Material WHERE IdMaterial = " . $idMat;
                $res = mysqli_query($conexion->mysqli, $qr);
                $res = mysqli_fetch_assoc($res);
                $res = json_decode($res['Medida'], true)[0];
                $nomb = $row['Nombre'];
                //$nomb = $row['Nombre'];
                //$nomb = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
                $prec = $row['PrecioUnitario'];
                
                ?>
                <tr>
                    <td style="width: 15%; text-align: center;">
                        <?php echo $row["Cantidad"] ?>
                    </td>
                    <td style="width: 45%">
                        <?php echo $nomb ?>
                    </td>
                    <td style="width: 20%; text-align: right">
                        <?php echo '$' . $prec ?>
                    </td>
                    <td style="width: 20%; text-align: right">
                        <?php echo '$' . $row['Subtotal'] ?>
                    </td>
                </tr>

                <?php
                if(intval($idMat) == -1)
                {
                    $subQuery = "SELECT Cantidad, UsuarioSolicita, NombreObra FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND Nombre = '" . $nomb .  "' AND PrecioUnitario = " . $prec;
                }
                else
                {
                    $subQuery = "SELECT Cantidad, UsuarioSolicita, NombreObra FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND IdMaterial = " . $idMat;
                }
                //var_dump($subQuery);
                $res2 = mysqli_query($conexion->mysqli, $subQuery);
                
                while($row2 = mysqli_fetch_assoc($res2)) {
                    ?>
                    <tr style="color: rgb(150,150,150); font-style: italic">
                        <td style="width: 15%; text-align: center;">
                            <?php echo $row2['Cantidad'] ?>
                        </td>
                        <td style="width: 45%; text-align: center;">
                            <?php echo $row2['UsuarioSolicita'] ?>
                        </td>
                        <td colspan="2" style="width: 40%; text-align: center;">
                            <?php echo $row2['NombreObra'] ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            //}
            ?>
        </tbody>
    </table>

    <table style="width: 40%; border-collapse: collapse;" align="right">
        <?php 
            $query = "SELECT * FROM OrdenCompra WHERE IdOrdenCompra = " . $idOrdenCompra;
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);        
            $conexion->cerrarBD();
        ?>
        <tr>
            <th style="width: 50%; border: solid 1px black; text-align: right;">
                Subtotal:
            </th>
            <td style="width: 50%; text-align: right; border: solid 1px black;">
                <?php echo '$' . $result['Subtotal']?>
            </td>
        </tr>
        <tr>
            <th style="width: 50%; border: solid 1px black; text-align: right;">
                IVA:
            </th>
            <td style="width: 50%; text-align: right; border: solid 1px black;">
                <?php echo '$' . $result['Iva']?>
            </td>
        </tr>
        <tr>
            <th style="width: 50%; border: solid 1px black; text-align: right;">
                Total:
            </th>
            <td style="width: 50%; text-align: right; border: solid 1px black;">
                <?php echo '$' . $result['Total']?>
            </td>
        </tr>
    </table>

    <?php 
        $conexion = new Conexion();
        $conexion->abrirBD();
        
        $query = "SELECT PoliticasCompra FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $politicas = $result['PoliticasCompra'];
    ?>
    <p><?php echo $politicas ?></p>
    <?php
        $query = "SELECT Descripcion FROM OrdenCompra WHERE OrdenCompra.IdOrdenCompra = " . $_GET['id'];
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $descrip = $result['Descripcion'];
    ?>
    <div style="width: 100%; border: solid 1px black; padding: 3px 5px">
        Observaciones:
        <br>
        <br><?php echo utf8_decode($descrip) ?>
    </div>
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