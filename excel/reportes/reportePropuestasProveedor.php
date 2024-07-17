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
$hoja->setCellValueByColumnAndRow(3, 2, urldecode('Fecha:') .': '. $fecha);
$hoja->mergeCells('A6:H6');

//----------------Datos de prueba
$reporte = new ReporteExcel();
$datos = $reporte->getReporteCuentasxPagarxProv();

$hoja->setCellValueByColumnAndRow(1, 7, "PROVEEDOR");
$hoja->setCellValueByColumnAndRow(2, 7, "LIMITE DE CREDITO");
$hoja->setCellValueByColumnAndRow(3, 7, "DEUDA");
$hoja->setCellValueByColumnAndRow(4, 7, "TOTAL PROPUESTA");

$documento->getActiveSheet()->getStyle('A7:E7')->applyFromArray($styleArray);

foreach(range('A','E') as $columnID) {
    if($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(50);
    else if($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(30);
    else if($columnID == 'C')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(30);
    else if($columnID == 'D')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(30);
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
    $hoja->setCellValueByColumnAndRow(1, $i + 8, $datos[$i]["Proveedor"]);
    $hoja->setCellValueByColumnAndRow(2, $i + 8, '$'.number_format($datos[$i]['LimiteCredito'], 2, '.', ','));
    $hoja->setCellValueByColumnAndRow(3, $i + 8, '$'.number_format($datos[$i]["Deuda"], 2, '.', ','));
    $hoja->setCellValueByColumnAndRow(4, $i + 8, '$'.number_format($datos[$i]["TotalPropuesto"], 2, '.', ','));
}

$conexion->cerrarBD();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $nombreDelDocumento .'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;