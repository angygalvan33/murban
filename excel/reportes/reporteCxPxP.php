<?php
include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//$total = $_GET['total'];
$hoyf = date('_Y_m_d');

$conexion = new Conexion();
$conexion->abrirBD();
$query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
$result = mysqli_query($conexion->mysqli, $query);
$result = mysqli_fetch_assoc($result);

$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ]
];

$styleArray = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
];

$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setCreator("CxC")
    ->setLastModifiedBy('CxC') // última vez modificado por
    ->setTitle('Cuentas por pagar por proveedor');

$nombreDelDocumento = "CxPxP".$hoyf.".xlsx";
$files = glob('../../images/logo/*.*');

if($files[0] != null)
{
  $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Logo');
  $drawing->setDescription('Logo');
  $drawing->setPath($files[0]);
  $drawing->setHeight(100);
  $drawing->setCoordinates('A2');
  $drawing->setWorksheet($documento->getActiveSheet());
}

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Cuentas por pagar");

$hoja->mergeCells('D3:F3');
$documento->getActiveSheet()->getStyle('D3:F3')->applyFromArray($headerStyle);
$hoja->mergeCells('G3:H3');
$documento->getActiveSheet()->getStyle('G3:H3')->applyFromArray($headerStyle);

//$hoja->setCellValueByColumnAndRow(4, 3, urldecode('Proveedor').': ' . urldecode($result1['Nombre']));
//$hoja->mergeCells('D2:E2');

//if($fechaIni != -1)
//    $hoja->setCellValueByColumnAndRow(4, 4, urldecode('Fecha Inicio:') .': '.  $fechaIni .' Fecha Fin: '. $fechaFin);
//$hoja->mergeCells('D3:E3');
//$hoja->setCellValueByColumnAndRow(7, 3, 'Total: '. $total );

//----------------Datos de prueba
$reporte = new ReporteExcel();

$datos = $reporte->getReporteCuentasxPagar();

$hoja->setCellValueByColumnAndRow(1, 7, "PROVEEDOR");
$hoja->setCellValueByColumnAndRow(2, 7, "DEUDA TOTAL");
$hoja->setCellValueByColumnAndRow(3, 7, "FOLIO DE FACTURA");
$hoja->setCellValueByColumnAndRow(4, 7, "FECHA FACTURACION");
$hoja->setCellValueByColumnAndRow(5, 7, "MONTO FACTURA");
$hoja->setCellValueByColumnAndRow(6, 7, "DEUDA FACTURA");

$documento->getActiveSheet()->getStyle('A7:F7')->applyFromArray($styleArray);

foreach(range('A','F') as $columnID) {
    if($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(45);
    else if($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else if($columnID == 'C')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'D')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'E')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'F')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else
        $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$longitud = count($datos);
$procant = null;
$facant = null;
//var_dump($datos);
//Recorro todos los elementos
for($i = 0; $i < $longitud; $i++)
{
    if($i == 0)
        $hoja->setCellValueByColumnAndRow(2, $i + 8, "$". $datos[$i]["Deuda"]);
    else if($ocant != null && $ocant != $datos[$i]["IdProveedor"]){
        if($facant != $datos[$i]["FolioFactura"]){
            $ocant = $datos[$i]["IdOC"];
            $hoja->setCellValueByColumnAndRow(2, $i + 8, "$". $datos[$i]["Deuda"]);
        }
        else{
            $hoja->setCellValueByColumnAndRow(2, $i + 8, "$0.00");
        }
    }
    else{
        $hoja->setCellValueByColumnAndRow(2, $i + 8, "$0.00");
    }

    $hoja->setCellValueByColumnAndRow(1, $i+8, $datos[$i]["Proveedor"]);
    //$hoja->setCellValueByColumnAndRow(2, $i+8, $datos[$i]["Deuda"]);
    $hoja->setCellValueByColumnAndRow(3, $i+8, $datos[$i]["FolioFactura"]);
    $hoja->setCellValueByColumnAndRow(4, $i+8, $datos[$i]["FechaFacturacion"]);
    $hoja->setCellValueByColumnAndRow(5, $i+8, $datos[$i]["ValorFactura"]);
    $hoja->setCellValueByColumnAndRow(6, $i+8, $datos[$i]["Deuda"]);
    /*$hoja->setCellValueByColumnAndRow(6, $i+8, number_format(($datos[$i]["Pago"] ? $datos[$i]["Pago"]:'0'),2));
    $hoja->setCellValueByColumnAndRow(7, $i+8, $datos[$i]["FechaPago"]);
    $hoja->setCellValueByColumnAndRow(8, $i+8, number_format(($datos[$i]["Total"] - $datos[$i]["TotalPagos"]),2));
    $hoja->setCellValueByColumnAndRow(9, $i+8, $datos[$i]["Descripcion"]);
    $hoja->setCellValueByColumnAndRow(10, $i+8, $datos[$i]["Genera"]);
    $hoja->setCellValueByColumnAndRow(11, $i+8, $datos[$i]["Autoriza"]);*/
    
    $ocant = $datos[$i]["IdProveedor"];
    $facant = $datos[$i]["FolioFactura"];
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;