<?php
include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$hoyf = date('_Y_m_d');
$fecha = date('Y-m-d');

$conexion = new Conexion();
$conexion->abrirBD();
$query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
$result = mysqli_query($conexion->mysqli, $query);
$result = mysqli_fetch_assoc($result);
$conexion->cerrarBD();

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
    ->setTitle('ReportePropuestas');

$nombreDelDocumento = "ReportePropuestas_".$hoyf.".xlsx";
$files = glob('../../images/logo/*.*');

if ($files[0] != null) {
  $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Logo');
  $drawing->setDescription('Logo');
  $drawing->setPath($files[0]);
  $drawing->setHeight(100);
  $drawing->setCoordinates('A1');
  $drawing->setWorksheet($documento->getActiveSheet());
}

$hoja = $documento->getActiveSheet();
$hoja->setTitle("ReportePropuestas");

$hoja->mergeCells('D1:F1');
$documento->getActiveSheet()->getStyle('C1:F1')->applyFromArray($headerStyle);
$hoja->mergeCells('D2:F2');
$documento->getActiveSheet()->getStyle('C2:F2')->applyFromArray($headerStyle);
$hoja->mergeCells('D3:F3');
$documento->getActiveSheet()->getStyle('C3:F3')->applyFromArray($headerStyle);
$hoja->mergeCells('D4:F4');
$documento->getActiveSheet()->getStyle('C4:F4')->applyFromArray($headerStyle);
$hoja->mergeCells('D5:F5');
$documento->getActiveSheet()->getStyle('C5:F5')->applyFromArray($headerStyle);
$hoja->setCellValueByColumnAndRow(3, 2, urldecode('Fecha:') . $fecha);
$hoja->mergeCells('A6:H6');

//----------------Datos de prueba
$reporte = new ReporteExcel();
$datos = $reporte->getReporteCuentasxPagar();

$hoja->setCellValueByColumnAndRow(1, 7, "APROBAR");
$hoja->setCellValueByColumnAndRow(2, 7, "PROVEEDOR");
$hoja->setCellValueByColumnAndRow(3, 7, "FOLIO");
$hoja->setCellValueByColumnAndRow(4, 7, "FECHA DE FACTURACIÓN");
$hoja->setCellValueByColumnAndRow(5, 7, "DÍAS DE CRÉDITO");
$hoja->setCellValueByColumnAndRow(6, 7, "EXCEDE CRÉDITO");
$hoja->setCellValueByColumnAndRow(7, 7, "MONTO");

$documento->getActiveSheet()->getStyle('A7:H7')->applyFromArray($styleArray);

foreach(range('A','H') as $columnID) {
    if($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(40);
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
    else if($columnID == 'G')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else
        $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$longitud = count($datos);
$conexion = new Conexion();
$conexion->abrirBD();
//Recorro todos los elementos
$totalDeuda = 0;
for ($i = 0; $i < $longitud; $i++) {
    $hoja->setCellValueByColumnAndRow(2, $i + 8, $datos[$i]["Proveedor"]);
    $hoja->setCellValueByColumnAndRow(3, $i + 8, $datos[$i]["FolioFactura"]);
    $hoja->setCellValueByColumnAndRow(4, $i + 8, $datos[$i]["FechaFacturacion"]);
    $hoja->setCellValueByColumnAndRow(5, $i + 8, $datos[$i]["DiasCreditoRestantes"]);

    $qr = "SELECT SUM(Deuda) AS TotDeuda FROM VistaCuentasPorPagar WHERE IdProveedor = ". $datos[$i]['IdProveedor'];
    $result3 = mysqli_query($conexion->mysqli, $qr);
    $result3 = mysqli_fetch_assoc($result3);
    $totalDeuda += (int)$result3['TotDeuda'];

    $qr = "SELECT LimiteCredito FROM Proveedor WHERE IdProveedor = " . $datos[$i]['IdProveedor'];
    $result3 = mysqli_query($conexion->mysqli, $qr);
    $result3 = mysqli_fetch_assoc($result3);
    $credito = (int)$result3['LimiteCredito'];

    if ($totalDeuda > $credito) //si
    	$hoja->setCellValueByColumnAndRow(6, $i + 8, "Sí");
    else //no
    	$hoja->setCellValueByColumnAndRow(6, $i + 8, "No");
    
    $hoja->setCellValueByColumnAndRow(7, $i + 8, "$".number_format($datos[$i]["Deuda"], 2, '.', ','));
}
$query = "SELECT SUM(Deuda) AS DeudaTotal FROM VistaCuentasPorPagar WHERE Proponer = 1";
$result = mysqli_query($conexion->mysqli, $query);
$result = mysqli_fetch_assoc($result);

$hoja->setCellValueByColumnAndRow(6, $i + 8, "Total propuesto");
$documento->getActiveSheet()->getStyle('F'. ($i + 8) .':F'. ($i + 8))->applyFromArray($headerStyle);
$hoja->setCellValueByColumnAndRow(7, $i + 8, '$'. number_format($result['DeudaTotal'], 2, '.', ','));

$conexion->cerrarBD();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $nombreDelDocumento .'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;