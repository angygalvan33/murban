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
        $title = 'Reporte de pagos';
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

    function GetFecha($fecha){
        $fechas = explode("/", $fecha);
        return $fechas[2] . "-" . $fechas[0] . "-" . $fechas[1];
    }
}

if(!isset($_SESSION['username'])){
    header('Location: ../../index.php');
}

    $pdf = new PDF();
    $pdf->AddPage('L');

    $pdf->SetFillColor(192, 192, 192);
    $pdf->Cell(40, 6, 'Fecha de Pago', 1, 0, 'C', true);
    $pdf->Cell(80, 6, 'Proveedor', 1, 0, 'C',true);
    $pdf->Cell(40, 6, 'Folio de Factura', 1, 0, 'C',true);
    $pdf->Cell(30, 6, 'Tipo de Pago', 1, 0, 'C',true);
    $pdf->Cell(60, 6, utf8_decode('Método de Pago'), 1, 0, 'C',true);
    $pdf->Cell(27, 6, 'Monto', 1, 1, 'C',true);

    $conexion = new Conexion();
    $conexion->abrirBD();
    $condicion = '';
    if ($_GET['fechas'] != ''){
        $fechas = explode(" - ", $_GET['fechas']);
        $f1 = $pdf->GetFecha($fechas[0]);
        $f2 = $pdf->GetFecha($fechas[1]);
        // $pdf->Cell(0, 6, $f1 . " " . $f2, 1, 1, 'C',true);
        $condicion = "WHERE Creado BETWEEN '" . $f1 . "' AND '" . $f2 . "'";
    }

    $qr = "SELECT * FROM VistaDetallePagos " . $condicion . "ORDER BY Creado";

    $result2 = mysqli_query($conexion->mysqli, $qr);
    
    while($row = mysqli_fetch_assoc($result2))
    {
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x+40,$y);
        $h = $pdf->MultiCell(80, 6, utf8_decode($row['NombreProveedor']), 1);
        $pdf->SetXY($x,$y);
        $pdf->Cell(40, $h*6, $row['Creado'], 1, 0, 'C');
        $pdf->SetXY($x+=120,$y);
        $pdf->Cell(40, $h * 6, $row['FolioFactura'], 1, 0, 'C');
        $pdf->SetXY($x+=40,$y);
        $pdf->Cell(30, $h * 6, utf8_decode($row['TipoDP']), 1);
        $pdf->SetXY($x+=30,$y);
        $pdf->Cell(60, $h * 6, utf8_decode($row['NombreMetodoPago']), 1);
        $pdf->SetXY($x+=60,$y);
        $pdf->Cell(27, $h * 6, '$' . $row['Monto'], 1, 1, 'R');
        
//        $x=$pdf->GetX();
//        $pdf->SetXY($x,$y);
//        echo $x.' '.$y;
//        echo $x.' '.$y+($h*6);
//break;
    }
    
    $conexion->cerrarBD();

    $pdf->Output('','Reporte_pagos_' . $pdf->TransformaFecha(date("j-n-Y") . '.pdf'));
