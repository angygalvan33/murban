<?php

require('../mysql_table.php');

set_include_path(get_include_path() . PATH_SEPARATOR . '../../phpseclib');
include_once "Net/SSH2.php";

include "../../config.php";
include_once "../../clases/conexion.php";
include_once '../../clases/permisos.php';
include_once '../../clases/usuario.php';  
$permisos = new Permisos();
$usuario = new Usuario();
date_default_timezone_set('America/Mexico_City');
//190mm en vertical y 277 en horizontal
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
        $title = 'Reporte de propuestas';
        //$this->Cell(50,6);
        $this->Cell(277,6, urldecode($title),0,0,'C');
        $this->Ln(10);
        
        // Obtener info. del proveedor
        
        $conexion = new Conexion();
        $conexion->abrirBD();
        
        $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $conexion->cerrarBD();
        
        // Info. del proveedor
        
        $this->SetFont('Arial','',8);
        
        $this->Cell(40);
        $this->Cell(85,5, utf8_decode($result['Nombre']), 0, 1);
        $this->Cell(40);
        $this->Cell(85,5, 'RFC: ' . utf8_decode($result['RFC']), 0, 1);
        $this->Cell(40);
        $dirTxt = 'Direcci%F3n: ';
        $this->Cell(85,5, urldecode($dirTxt) . utf8_decode($result['Direccion']), 0, 1);
        $this->Cell(40);
        $telTxt = 'Tel%E9fono: ';
        $this->Cell(85,5, urldecode($telTxt) . utf8_decode($result['Telefono']), 0, 1);
        $this->Cell(40);
        $this->Cell(85,5, 'Representante: ' . utf8_decode($result['Representante']), 0, 1);
        $this->Cell(40);
        $this->Cell(85,5, 'e-mail: ' . $result['Email'], 0, 1);
        
        $this->Ln(8);

        $this->Cell(150,8,' ');
        $fecha = date("j-n-Y");
        $this->Cell(127,8, 'Fecha: ' . $this->TransformaFecha($fecha), 0, 1, 'R');

        $this->SetFont('Arial','',12);
        
        // Ensure table header is printed
        parent::Header();
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(65,6,"Power By Bunraku");
        $this->Cell(147,6,urldecode('P%E1gina ').$this->PageNo(),0,0,'C');
        $this->Cell(65,6,"");
        parent::Footer();
    }
    function TransformaFecha($fecha) {
        $array = explode("-", $fecha);

        if (strlen($array[0]) == 1)
            $array[0] = '0' . $array[0];

        if (strlen($array[1]) == 1)
            $array[1] = '0' . $array[1];

        return $array[0]. '-' . $array[1] . '-' . $array[2];
    }
}

if(!isset($_SESSION['username'])){
    header('Location: ../../index.php');
}

    $pdf = new PDF();
    $pdf->AddPage('L');

    // Obtener info. de BD
    
    $conexion = new Conexion();
    $conexion->abrirBD();

    $query = "SELECT SUM(Deuda) AS DeudaTotal FROM VistaCuentasPorPagar WHERE Proponer = 1";
    $result = mysqli_query($conexion->mysqli, $query);
    $result = mysqli_fetch_assoc($result);

    $conexion->cerrarBD();
    
    // Tabla de detalle de las cuentas propuestas

    // $pdf->AddCol('Proveedor',60,'Proveedor', 'L');
    // $pdf->AddCol('FolioFactura',25,'Folio','R');
    // $fechaTxt = 'Fecha de Facturaci%F3n';
    // $pdf->AddCol('FechaFacturacion',30,urldecode($fechaTxt),'C');
    // $diasCredTxt = 'D%EDas de Cr%E9dito';
    // $pdf->AddCol('DiasCreditoRestantes',30,urldecode($diasCredTxt),'C');
    // $pdf->AddCol('CreditoDisponible',40,urldecode('Cr%E9dito Disponible'),'R');
    // $pdf->AddCol('Deuda',40,'Monto','R');

    $pdf->SetFillColor(192, 192, 192);
    $pdf->Cell(20, 12, 'Aprobar', 1, 0, 'C', true);
    $pdf->Cell(117, 12, 'Proveedor', 1, 0, 'C',true);
    $pdf->Cell(30, 12, 'Folio', 1, 0, 'C',true);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(30, 6, urldecode('Fecha de Facturaci%F3n'), 1, 'C',true);
    $pdf->SetXY($x += 30,$y);
    $pdf->MultiCell(20, 6, urldecode('D%EDas de Cr%E9dito'), 1, 'C',true);
    $pdf->SetXY($x += 20,$y);
    $pdf->MultiCell(25, 6, urldecode('Excede cr%E9dito'), 1, 'C', true);
    $pdf->SetXY($x += 25,$y);
    //$pdf->Cell(40, 12, urldecode('Cr%E9dito Disponible'), 1, 0, 'C',true);
    //$pdf->SetXY($x += 35,$y);
    $pdf->Cell(35, 12, urldecode('Monto'), 1, 1, 'C',true);

    $conexion = new Conexion();
    $conexion->abrirBD();
    $qr = "SELECT * FROM VistaCuentasPorPagar WHERE Proponer = 1";
    //$pdf->Table($conexion->mysqli, $qr);
    $result2 = mysqli_query($conexion->mysqli, $qr);
    while($row = mysqli_fetch_assoc($result2))
    {
        $pdf->Cell(20, 8, '', 1);
        $pdf->Cell(117, 8, utf8_decode($row['Proveedor']), 1);
        $pdf->Cell(30, 8, $row['FolioFactura'], 1, 0, 'C');
        $pdf->Cell(30, 8, $row['FechaFacturacion'], 1, 0, 'C');
        $pdf->Cell(20, 8, $row['DiasCreditoRestantes'], 1, 0, 'C');

        $qr = "SELECT SUM(Deuda) AS TotDeuda FROM VistaCuentasPorPagar WHERE IdProveedor = " . $row['IdProveedor'];
        $result3 = mysqli_query($conexion->mysqli, $qr);
        $result3 = mysqli_fetch_assoc($result3);
        $totalDeuda = (int)$result3['TotDeuda'];

        $qr = "SELECT LimiteCredito FROM Proveedor WHERE IdProveedor = " . $row['IdProveedor'];
        $result3 = mysqli_query($conexion->mysqli, $qr);
        $result3 = mysqli_fetch_assoc($result3);
        $credito = (int)$result3['LimiteCredito'];

        if ($totalDeuda > $credito)
            $pdf->Cell(25, 8, 'Si', 1, 0, 'C');
        else
            $pdf->Cell(25, 8, 'No', 1, 0, 'C');

        //$pdf->Cell(40, 8, '$' . $row['CreditoDisponible'], 1, 0, 'R');
        $pdf->Cell(35, 8, '$' . $row['Deuda'], 1, 1, 'R');
    }
    $conexion->cerrarBD();

    // Totales
    
    $pdf->Cell(197);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(45, 6, 'Total Propuesto:', 1, 0, 'C');
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(35, 6, '$' . $result['DeudaTotal'], 1, 1, 'R');

    $pdf->Output('','Reporte_propuestas_' . $pdf->TransformaFecha(date("j-n-Y") . '.pdf'), true);
