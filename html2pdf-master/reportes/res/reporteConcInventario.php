<?php
set_include_path(get_include_path() . PATH_SEPARATOR .'../../phpseclib');
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

if (!isset($_SESSION['username'])) {
    header('Location: ../../index.php');
}

if ($permisos->acceso("4096", $usuario->obtenerPermisos($_SESSION['username']))) {
    $idUbicacion = $_GET["ubicacion"];
    $fecha = date("d-m-Y");
?>
<style>
    td, th {
        padding: 3px;
    }

    .header td {
        line-height: 15px;
    }
</style>

<page backtop="45mm" backbottom="15mm" backleft="6mm" backright="6mm">
    <page_header>
        <h4 style="width: 100%; text-align: center">
            Reporte Conciliación de Inventario
        </h4>
        <?php
            $conexion = new Conexion();
            $conexion->abrirBD();

            $query = "SELECT Nombre FROM Ubicacion WHERE Ubicacion.IdUbicacion = ". $idUbicacion;
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);

            $ubicacion = $result['Nombre'];
            
            $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);

            $conexion->cerrarBD();
        ?>
        <table style="width: 95%; font-size: 11px" class="header" align="center">
            <tr style="width: 100%;">
                <td style="width: 10%">
                    <img src="../../images/logo/<?php echo $comodin->nombreLogoEmpresa() ?>" alt="Logo" style="width: 100%"/>
                </td>
                <td style="width: 60%">
                </td>
                <td style="width:25%">
                    <h5 style="font-size: 15px; margin: 0px !important;"><?php echo 'Fecha: '. $fecha ?></h5>
                    <br>
                    <h5 style="font-size: 15px; margin: 0px !important;"><?php echo 'Ubicación: '. $ubicacion ?></h5>
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
        <thead style="width: 100%; font-size: 15px;">
            <tr>
                <th style="width: 15%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Material
                </th>
                <th style="width: 15%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Proyecto
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Cantidad teórica
                </th>
                <th style="width: 10%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Cantidad real
                </th>
                <th style="width: 50%; text-align: center; border: solid 1px black; background: rgb(200,200,200)">
                    Notas
                </th>
            </tr>
        </thead>
        <tbody style="width: 100%; border: solid 1px black;">
            <?php
            $conexion = new Conexion();
            $conexion->abrirBD();
            
            if ($idUbicacion == 0)
                $query = "SELECT * FROM VistaInventarioUbicacionMaterial ORDER BY Categoria, Material";
            else
                $query = "SELECT * FROM VistaInventarioUbicacionMaterial WHERE VistaInventarioUbicacionMaterial.IdUbicacion = ". $idUbicacion ." ORDER BY Categoria, Material";

            $result = mysqli_query($conexion->mysqli, $query);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $id_act = $row["IdMaterial"];
                if ($id_ant != $id_act) {
                    $id_ant = $row["IdMaterial"];
                ?>
                <tr>
                    <td style="text-align: left;" colspan="5">
                        <br><b>Total de <?php echo $row["Material"] .' = '. $row["Total"]?></b>
                        <br><b>CATEGORIA: <?php echo $row["Categoria"]?></b>
                    </td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td style="width: 15%; text-align: left;">
                        <?php echo $row["Material"] ?>
                    </td>
                    <td style="width: 15%; text-align: left;">
                        <?php echo $row["Proyecto"] ?>
                    </td>
                    <td style="width: 10%; text-align: right">
                        <?php echo $row["Cantidad"] ?>
                    </td>
                    <td style="width: 10%; text-align: center;">
                        ________
                    </td>
                    <td style="width: 50%">
                        _____________________________________________________________________
                    </td>
                </tr>
                <?php
                $id_ant = $row["IdMaterial"];
            }
            ?>
        </tbody>
    </table>
    <br><br>
</page>
<?php
}
else {
    echo "Acceso denegado.";
}
?>