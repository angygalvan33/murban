<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../../phpseclib');
include_once "Net/SSH2.php";

include "../../config.php";
include_once "../../clases/conexion.php";
include_once '../../clases/permisos.php';
include_once '../../clases/usuario.php';
include_once '../../clases/comodin.php';
include_once '../../clases/conversion.php';

$permisos = new Permisos();
$usuario = new Usuario();
$comodin = new Comodin();
$conversion = new Conversion();

if(!isset($_SESSION['username'])){
    header('Location: ../../index.php');
}

if ($permisos->acceso("4096", $usuario->obtenerPermisos($_SESSION['username'])))
{
    $idCajaChicaDetalle = $_GET['idCajaChicaDetalle'];
?>

<style>
    td, th {
        padding: 3px;
    }

    .header td{
        line-height: 15px;
    }
</style>

<page backtop="65mm" backbottom="15mm" backleft="6mm" backright="6mm">
    <page_header>
        <?php 
            $conexion = new Conexion();
            $conexion->abrirBD();
            
            $query = "SELECT * FROM VistaCajaChicaUsuario WHERE IdCajaChicaDetalle = ". $idCajaChicaDetalle;
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $cantidad = $result['Total'];
            $folioFactura = $result['FolioFactura'];
            $fecha = $result['Creado'];
            $usuario = $result['Usuario'];
            $obra = $result['Obra'];
            $material = $result['Material'];
            $proveedor = $result['Proveedor'];
            $cantidad_letra = $conversion->convertirNumeroLetra($cantidad);
        ?>

        <table style="width: 95%; margin-top:50px" class="header" align="center">
            <tr style="width: 100%;">
                <td style="width: 40%">
                    <img src='../../images/logo_reporte.jpg' alt="Logo" style="width: 100%"/>
                </td>
                <td style="width: 30%">     
                    <h2 style="width: 100%; text-align: center; color: darkblue;">
                        Recibo de pago
                    </h2>
                </td>
                <td style="width: 30%">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Folio: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $folioFactura; ?></label>
                    </p>
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Serie: </label>
                        <label style="width: 50%; font-size:15px"> </label>
                    </p>
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Fecha: </label>
                        <label style="width: 50%; font-size:15px"><?php echo TransformaFecha($fecha); ?></label>
                    </p>                    
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Recibí de: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $usuario; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Cantidad: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $cantidad ." ".$cantidad_letra; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Proyecto: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $obra; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Concepto: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $material." ".$proveedor; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h4 style="width: 100%; text-align: center;">
                        RECIBIDO POR:
                    </h4>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h4 style="width: 100%; text-align: center;">
                        _________________________________________
                    </h4>
                </td>
            </tr>
        </table>
        <table style="width: 95%; margin-top:80px" class="header" align="center">
            <tr style="width: 100%;">
                <td style="width: 40%">
                    <img src='../../images/logo_reporte.jpg' alt="Logo" style="width: 100%"/>
                </td>
                <td style="width: 30%">     
                    <h2 style="width: 100%; text-align: center; color: darkblue;">
                        Recibo de pago
                    </h2>
                </td>
                <td style="width: 30%">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Folio: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $folioFactura; ?></label>
                    </p>
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Serie: </label>
                        <label style="width: 50%; font-size:15px"> </label>
                    </p>
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Fecha: </label>
                        <label style="width: 50%; font-size:15px"><?php echo TransformaFecha($fecha); ?></label>
                    </p>                    
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Recibí de: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $usuario; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Cantidad: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $cantidad ." ".$cantidad_letra; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Proyecto: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $obra; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        <label style="width: 50%; font-size:15px; font-weight: bold;">Concepto: </label>
                        <label style="width: 50%; font-size:15px"><?php echo $material." ".$proveedor; ?></label>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h4 style="width: 100%; text-align: center;">
                        RECIBIDO POR:
                    </h4>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h4 style="width: 100%; text-align: center;">
                        _________________________________________
                    </h4>
                </td>
            </tr>
        </table>
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