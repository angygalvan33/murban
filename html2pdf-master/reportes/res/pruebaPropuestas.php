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

if ($permisos->acceso("4194304", $usuario->obtenerPermisos($_SESSION['username'])))
{
    // Recibir ID y agregar página horizontal (L = Landscape)
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
            Reporte de propuestas
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
                    <br><?php echo 'Representante: ' . $result['Representante']?>
                    <br><?php echo 'E-mail: ' . $result['Email']?>
                </td>
            </tr>
        </table>
        <div style="width: 97%; text-align: right; margin: 15px 0px 5px 0px; font-size: 10px">
            <?php echo 'Fecha: ' . $fecha ?>
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
                <th style="width: 7%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Aprobar
                </th>
                <th style="width: 40%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Proveedor
                </th>
                <th style="width: 13%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Folio
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Fecha de facturación
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Días de crédito
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Excede crédito
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Monto
                </th>
            </tr>
        </thead>
        <tbody style="width: 100%; border: solid 1px black;">
            <?php
               $conexion = new Conexion();
               $conexion->abrirBD();
               $qr = "SELECT * FROM VistaCuentasPorPagar WHERE Proponer = 1";
               //$pdf->Table($conexion->mysqli, $qr);
               $result2 = mysqli_query($conexion->mysqli, $qr);
               while($row = mysqli_fetch_assoc($result2))
               { 
            ?>
            <tr>
                <td style="border: solid 1px black; text-align: center; width: 7%;">
                    <?php echo '' ?>
                </td> 
                <td style="border: solid 1px black; width: 40%;">
                    <?php echo $row['Proveedor'] ?>
                </td>
                <td style="border: solid 1px black; text-align: center; width: 13%;">
                    <?php echo $row['FolioFactura'] ?>
                </td>
                <td style="border: solid 1px black; text-align: center; width: 10%;">
                    <?php echo $row['FechaFacturacion'] ?>
                </td>
                <td style="border: solid 1px black; text-align: center; width: 10%;">
                    <?php echo $row['DiasCreditoRestantes'] ?>
                </td>
                <td style="border: solid 1px black; text-align: center; width: 10%;">
                    <?php 
                        $qr = "SELECT SUM(Deuda) AS TotDeuda FROM VistaCuentasPorPagar WHERE IdProveedor = " . $row['IdProveedor'];
                        $result3 = mysqli_query($conexion->mysqli, $qr);
                        $result3 = mysqli_fetch_assoc($result3);
                        $totalDeuda = (int)$result3['TotDeuda'];
                
                        $qr = "SELECT LimiteCredito FROM Proveedor WHERE IdProveedor = " . $row['IdProveedor'];
                        $result3 = mysqli_query($conexion->mysqli, $qr);
                        $result3 = mysqli_fetch_assoc($result3);
                        $credito = (int)$result3['LimiteCredito'];
                
                        if ($totalDeuda > $credito)
                            echo 'Si' ;
                        else
                            echo 'No' ;
                    ?>
                </td>
                <td style="border: solid 1px black; text-align: right; width: 10%;">
                    <?php echo '$' . $row['Deuda'] ?>
                </td>  
            </tr>
            <?php
                }
                $conexion->cerrarBD();
            ?>
            
        </tbody>
    </table>
    
    <table style="width: 30%; border-collapse: collapse;" align="right">
        <tr>
            <th style="width: 66.66%; text-align: center; border: solid 1px black; ">
                Total propuesto:
            </th>
            <td style="width: 33.33%; text-align: right; border: solid 1px black; ">
                <?php 
                    $conexion = new Conexion();
                    $conexion->abrirBD();
                
                    $query = "SELECT SUM(Deuda) AS DeudaTotal FROM VistaCuentasPorPagar WHERE Proponer = 1";
                    $result = mysqli_query($conexion->mysqli, $query);
                    $result = mysqli_fetch_assoc($result);
                
                    echo '$' . $result['DeudaTotal'];
                    $conexion->cerrarBD();
                ?>
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

    if (strlen($array[0]) == 1)
        $array[0] = '0' . $array[0];

    if (strlen($array[1]) == 1)
        $array[1] = '0' . $array[1];

    return $array[0]. '-' . $array[1] . '-' . $array[2];
}
?>