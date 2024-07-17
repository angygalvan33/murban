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
            Reporte de pagos
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
                <th style="width: 14%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Fecha de Pago
                </th>
                <th style="width: 30%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Proveedor
                </th>
                <th style="width: 14%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Folio de Factura
                </th>
                <th style="width: 12%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Tipo de Pago
                </th>
                <th style="width: 20%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Método de Pago
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Monto
                </th>
            </tr>
        </thead>
        <tbody style="width: 100%; border: solid 1px black;">
            <?php
            $conexion2 = new Conexion();
               $conexion2->abrirBD();
           
                $condicion = '';
                if ($_GET['fechas'] != ''){
                    $fechas = explode(" - ", $_GET['fechas']);
                    $f1 = GetFecha($fechas[0]);
                    $f2 = GetFecha($fechas[1]);
                    $condicion = "WHERE Creado BETWEEN '" . $f1 . "' AND '" . $f2 . "'";
                }
            
                $qr = "SELECT * FROM VistaDetallePagos " . $condicion . "ORDER BY Creado";
            
                $result2 = mysqli_query($conexion2->mysqli, $qr);
                //$row = mysqli_fetch_array($result2);
                
                while($row = mysqli_fetch_array($result2))
                {
            ?>
           <tr>
                <td style="border: solid 1px black; text-align: center;width: 14%;">
                    <?php echo $row['Creado'] ?>
                </td> 
                <td style="border: solid 1px black;width: 30%;">
                    <?php echo $row['NombreProveedor'] ?>
                </td>
                <td style="border: solid 1px black; text-align: center;width: 14%;">
                    <?php echo $row['FolioFactura'] ?>
                </td>
                <td style="border: solid 1px black;;width: 12%;">
                    <?php echo $row['TipoDP'] ?>
                </td>
                <td style="border: solid 1px black;;width: 20%;">
                    <?php echo utf8_decode($row['NombreMetodoPago']) ?>
                </td>
                <td style="border: solid 1px black; text-align: right;width: 10%;">
                    <?php echo '$' . $row['Monto'] ?>
                </td>  
            </tr>
            <?php
            
                }
                $conexion2->cerrarBD();
            ?>
        </tbody>
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

function GetFecha($fecha){
    $fechas = explode("/", $fecha);
    return $fechas[2] . "-" . $fechas[0] . "-" . $fechas[1];
}

function getData($conexion,$sql){
        $conexion->abrirBD();
        $data = array();
        
        if($this->conexion->mysqli!=NULL)
        {
            $query = mysqli_query($conexion->mysqli, $sql) OR DIE ("Can't get Data from DB , check your SQL Queryy ".$sql);
            $conexion->cerrarBD();
        
            foreach ($query as $row ) {
                $data[] = $row ;
            }
        }
        //var_dump($data);
        return $data;
    }
?>