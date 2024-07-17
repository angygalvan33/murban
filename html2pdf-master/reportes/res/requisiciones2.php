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

$idProyecto = $_GET["id"];
$s = $_GET["s"];
$status = "";
switch($s)
{
    case "1":
        $status="PENDIENTE";
        break;

    case "2":
        $status="PARCIALMENTE ATENDIDA";
        break;

    case "3":
        $status="ATENDIDA";
        break;

    case "4":
        $status="PARCIALMENTE CANCELADA";
        break;    

    case "5":
        $status="CANCELADA";
        break;

    default:
       $status="0";
            break;
}

$wheres = "";
if($status ==="0")
{
    $wheres = " Estado IN('PENDIENTE','PARCIALMENTE ATENDIDA','ATENDIDA','PARCIALMENTE CANCELADA','CANCELADA')";
}

else
{
    $wheres = " Estado = '".$status."'";
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

<page backtop="55mm" backbottom="15mm" backleft="6mm" backright="6mm">
    <page_header>
        <?php 
            $conexion = new Conexion();
            $conexion->abrirBD();
            
            $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $conexion->cerrarBD();
            //$this->Cell(0,8, 'Fecha OC: ' . $this->TransformaFecha($fecha), 0, 1, 'R');
        ?>
        <table style="width: 95%; font-size: 10px" class="header" align="center">
            <tr style="width: 100%;">
                <td style="width: 35%">
                     <h4 style="width: 20%; text-align: center">
                        Requisiciones
                    </h4>
                </td>
                <td style="width: 25%">
                    <img src="../../images/logo/<?php echo $comodin->nombreLogoEmpresa() ?>"  alt="Logo" style="width: 100%"/>
                </td>
                <td style="width: 40%">
                    <?php echo $result['Nombre']?>
                    <br><?php echo 'RFC: ' . $result['RFC']?>
                    <br><?php echo 'Direcci&oacute;n: ' . $result['Direccion']?>
                    <br><?php echo 'Tel&eacute;fono: ' . $result['Telefono']?>
                    <br><?php echo 'Representante: ' . $result['Representante']?>
                    <br><?php echo 'E-mail: ' . $result['Email']?>
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

    <table style="width: 100%; border-collapse: collapse;">
        <thead style="width: 100%; font-size: 11px;">
            <tr>
                <th style="width: 6%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Folio
                </th>
                <th style="width: 18%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Proyecto
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Cantidad solicitada
                </th>
                <th style="width: 9%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Cantidad atendida
                </th>
                <th style="width: 18%; text-align: left; border: solid 1px black; background: rgb(200,200,200)">
                    Material
                </th>
                <th style="width: 7%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Unidad
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Estatus
                </th>
                <th style="width: 12%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Creado
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Fecha Requerida
                </th>
            </tr>
        </thead>
        <tbody style="width: 100%; border: solid 1px black;">
            <?php
            //for ($i = 0; $i < 5; $i++){
            $conexion = new Conexion();
            $conexion->abrirBD();
            
            $qr = "SELECT * FROM VistaRequisicionesConsulta Where ".$wheres;
              
            $result = mysqli_query($conexion->mysqli, $qr);
            
            while($row = mysqli_fetch_assoc($result)) {
                $Folio = $row['IdRequisicion'];
                $Proyecto = $row['Proyecto'];
                $CantidadSolicitada = $row['CantidadSolicitada'];
                $CantidadAtendida = $row['CantidadAtendida'];
                $Material = utf8_decode($row['Material']);
                $Estado = $row['Estado'];
                $Fecha = $row['Fecha'];
                $FechaReq = $row['FechaReq'];
                $Unidad = $row['Unidad'];
                
                $date = new DateTime($Fecha);
                ?>
                <tr>
                    <td style="width: 6%; text-align: center;">
                        <?php echo $Folio ?>
                    </td>
                    <td style="width: 18%; text-align: center;">
                        <?php echo utf8_encode($Proyecto) ?>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        <?php echo $CantidadSolicitada ?>
                    </td>
                    <td style="width: 9%; text-align: center">
                        <?php echo $CantidadAtendida ?>
                    </td>
                    <td style="width: 18%; text-align: left">
                        <?php echo utf8_encode($Material) ?>
                    </td>
                    <td style="width: 7%; text-align: left">
                        <?php echo utf8_encode($Unidad) ?>
                    </td>
                    <td style="width: 10%; text-align: center">
                        <?php echo $Estado ?>
                    </td>
                    <td style="width: 12%; text-align: center">
                        <?php echo $date->format('y-m-d'); ?>
                    </td>
                    <td style="width: 10%; text-align: center">
                        <?php echo $FechaReq ?>
                    </td>
                </tr>

            <?php
            }
            ?>  
        </tbody>
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
</page>
<?php


function TransformaFecha($fecha) {
    $array = explode("-", $fecha);

    return substr($array[2],0,2) . '-' . $array[1] . '-' . $array[0];
}
?>