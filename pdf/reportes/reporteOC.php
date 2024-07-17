<?php

require('../mysql_table.php');

set_include_path(get_include_path() . PATH_SEPARATOR . '../../phpseclib');
include_once "Net/SSH2.php";

include "../../config.php";
include_once "../../clases/conexion.php";
include_once '../../clases/permisos.php';
include_once '../../clases/usuario.php'; 
include_once '../../clases/panelAdmin.php';

$permisos = new Permisos();
$usuario = new Usuario();

//cantidad de caracteres que caben aproximadamente en la multicelda
$maxC = 40; //En Vertical y 75 en horizontal
//190mm en vertical y 265 en horizontal


class PDF extends PDF_MySQL_Table
{
    function Header()
    {
        // Título
        
        $this->SetFont('Arial','',14);
        $files = glob('../../images/logo/*');
        $file = $files[0];
        $this->Image($file,10,19,40,30);
        $this->SetFillColor(0,0,0);
        $this->SetTextColor(0,0,0);
        $this->SetLineWidth(0.8);
        $title = 'Orden de Compra';
        //$this->Cell(50,6);
        $this->Cell(190,6, urldecode($title),0,0,'C');
        $this->Ln(10);
        
        // Obtener info. del proveedor
        
        $conexion = new Conexion();
        $conexion->abrirBD();
        
        $query = "SELECT * FROM OrdenCompra WHERE OrdenCompra.IdOrdenCompra = " . $_GET['id'];
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $fecha = $result['Creado'];
        $idProv = $result['IdProveedor'];
        $numCot = $result['NumCotizacion'];

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
        
        // Info. del proveedor
        
        $this->SetFont('Arial','',8);
        
        $this->Cell(40);
        $this->Cell(85,5, utf8_decode($result['Nombre']));
        $this->SetFont('Arial','',20);
        $this->Cell(0,5, 'Folio OC: ' . $_GET['id'], 0, 1);
        $this->SetFont('Arial','',8);
        
        $this->Cell(40);
        $this->Cell(85,5, 'RFC: ' . utf8_decode($result['RFC']));
        $this->Cell(0,5, 'Proveedor: ' . utf8_decode($proveedor), 0, 1);
        
        $this->Cell(40);
        $dirTxt = 'Direcci%F3n: ';
        $this->Cell(85,5, urldecode($dirTxt) . utf8_decode($result['Direccion']));
        $cotTxt = 'No. cotizaci%F3n: ' . $numCot;
        $this->Cell(0,5, urldecode($cotTxt), 0, 1);
        
        $this->Cell(40);
        $telTxt = 'Tel%E9fono: ';
        $this->Cell(85,5, urldecode($telTxt) . utf8_decode($result['Telefono']));
        $this->Cell(0,5, 'Genera: ' . utf8_decode($genera), 0, 1);
        
        $this->Cell(40);
        $this->Cell(85,5, 'Representante: ' . utf8_decode($result['Representante']));
        $this->Cell(0,5, 'Autoriza: ' . utf8_decode($autoriza), 0, 1);
        
        $this->Cell(40);
        $this->Cell(85,5, 'e-mail: ' . $result['Email']);
        $metText = 'M%E9todo de Pago: ';
        $this->Cell(50,5, urldecode($metText) . utf8_decode($metodoPago),0,1);

        $this->Cell(125,5,' ');
        $this->Cell(0,5, 'Referencia: ' . utf8_decode($ref), 0, 1);
        
        $this->Ln(8);

        $this->Cell(150,5,' ');
        $this->Cell(0,8, 'Fecha OC: ' . $this->TransformaFecha($fecha), 0, 1, 'R');

        $this->SetFont('Arial','',12);
        
        // Ensure table header is printed
        parent::Header();
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(45,6,"Power By Bunraku");
        $this->Cell(100,6,urldecode('P%E1gina ').$this->PageNo(),0,0,'C');
        $this->Cell(45,6,"");
        parent::Footer();
    }

    function TransformaFecha($fecha) {
        $array = explode("-", $fecha);

        return substr($array[2],0,2) . '-' . $array[1] . '-' . $array[0];
    }
}

if(!isset($_SESSION['username'])){
    header('Location: ../../index.php');
}

    if ($permisos->acceso("4096", $usuario->obtenerPermisos($_SESSION['username'])))
    {
        // Recibir ID y agregar página horizontal (L = Landscape)

        $idOrdenCompra = $_GET["id"];

        $pdf = new PDF();
        $pdf->AddPage('p');

        // Tabla de detalle de la OC

        //$pdf->Cell(15);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->Cell(30, 6, 'Cantidad', 1, 0, 'C',true);
        $pdf->Cell(80, 6, 'Material', 1, 0, 'C',true);
        $pdf->Cell(40, 6, 'Precio Unitario', 1, 0, 'C',true);
        $pdf->Cell(40, 6, 'Subtotal', 1, 1, 'C',true);

        $conexion = new Conexion();
        $conexion->abrirBD();
        
        $qr = "SELECT IdMaterial, Nombre, SUM(Cantidad) AS Cantidad, PrecioUnitario, SUM(Subtotal) AS Subtotal FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND IdMaterial != -1 GROUP BY IdMaterial
              UNION SELECT IdMaterial, Nombre, SUM(Cantidad), PrecioUnitario, SUM(Subtotal) FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND IdMaterial = -1 GROUP BY Nombre, PrecioUnitario";
        
        $result = mysqli_query($conexion->mysqli, $qr);
        while($row = mysqli_fetch_assoc($result)) {
            $pdf->SetFont('Arial','',8);
            $idMat = $row['IdMaterial'];
            $qr = "SELECT Medida FROM Material WHERE IdMaterial = " . $idMat;
            $res = mysqli_query($conexion->mysqli, $qr);
            $res = mysqli_fetch_assoc($res);
            //$res = substr($res['Medida'], 1, -1);
            $res = json_decode($res['Medida'], true)[0];
            $nomb = $row['Nombre'];
            //$nomb = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
            $prec = $row['PrecioUnitario'];
            
            //Obtiene el alto de las otras celdas
            //$h = ceil(strlen($nomb) / $maxC);
            //$pdf->Cell(15);
            
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            $pdf->SetXY($x + 30,$y);
            $h = $pdf->MultiCell(80, 6, utf8_decode($nomb), 1, 'L');
            $pdf->SetXY($x,$y);
            $pdf->Cell(30, $h * 6, $row["Cantidad"] . " (" . $res["unidad"] . ")", 1, 0, 'C');
            $pdf->SetXY($x += 110,$y);
            $pdf->Cell(40, $h * 6, '$' . $prec, 1, 0, 'R');
            $pdf->SetXY($x += 40,$y);
            $pdf->Cell(40, $h * 6, '$' . $row['Subtotal'], 1, 1, 'R');
            
            if(intval($idMat) == -1)
            {
                $subQuery = "SELECT Cantidad, Solicita, NombreObra FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND Nombre = '" . $nomb .  "' AND PrecioUnitario = " . $prec;
            }
            else
            {
                $subQuery = "SELECT Cantidad, Solicita, NombreObra FROM VistaDetalleMaterial WHERE IdOrdenCompra = " . $idOrdenCompra . " AND IdMaterial = " . $idMat;
            }
            
            $res2 = mysqli_query($conexion->mysqli, $subQuery);
            
            //$pdf->SetFont('Arial','',8);
            $pdf->SetTextColor(192,192,192);
            while($row2 = mysqli_fetch_assoc($res2)) {
                $pdf->Cell(30);
                $pdf->Cell(20, 6, $row2['Cantidad'], 0, 0, 'R');
                $pdf->Cell(50, 6, utf8_decode($row2['Solicita']), 0, 0, 'L');
                $pdf->Cell(50, 6, utf8_decode($row2['NombreObra']), 0, 1, 'R');
            }
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',12);
        }
        
        // Totales

        $query = "SELECT * FROM OrdenCompra WHERE IdOrdenCompra = " . $idOrdenCompra;
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);

        $conexion->cerrarBD();
        
        $pdf->Cell(110);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40, 6, 'Subtotal:', 1, 0, 'R');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40, 6, '$' . $result['Subtotal'], 1, 1, 'R');
        
        $pdf->Cell(110);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40, 6, 'IVA:', 1, 0, 'R');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40, 6, '$' . $result['Iva'], 1, 1, 'R');
        
        $pdf->Cell(110);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40, 6, 'Total Estimado:', 1, 0, 'R');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40, 6, '$' . $result['Total'], 1, 1, 'R');

        $conexion = new Conexion();
        $conexion->abrirBD();
        
        $query = "SELECT PoliticasCompra FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $politicas = $result['PoliticasCompra'];

        $pdf->Ln(8);
        $pdf->MultiCell(0, 6, utf8_decode($politicas));

        $query = "SELECT Descripcion FROM OrdenCompra WHERE OrdenCompra.IdOrdenCompra = " . $_GET['id'];
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $descrip = $result['Descripcion'];

        $pdf->Ln(10);
        $pdf->Cell(190, 6, "Observaciones:", 0, 1);
        $pdf->MultiCell(0, 6, utf8_decode($descrip), 1);

        $pdf->Output('','OC' . $_GET['id'] . '.pdf');
    }
    
 else {
    echo "Acceso denegado.";
}