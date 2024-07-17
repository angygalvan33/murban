<?php
include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$fechaIni = $_GET["fIni"];
$fechaFin = $_GET["fFin"];
$hoyf = date('_Y_m_d');

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
    ->setTitle('Reporte de Pagos');

$nombreDelDocumento = "ReporteDePagos".$hoyf.".xlsx";
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
$hoja->setTitle("Reporte de Pagos");

$hoja->mergeCells('C2:E2');
$documento->getActiveSheet()->getStyle('C2:E2')->applyFromArray($headerStyle);

if ($fechaIni != -1)
    $hoja->setCellValueByColumnAndRow(3, 2, urldecode('Reporte del:').  $fechaIni .' al: '. $fechaFin);
$hoja->mergeCells('A6:F6');
//----------------Datos de prueba
$reporte = new ReporteExcel();
$datos = $reporte->getReporteDePagos($fechaIni, $fechaFin);

$hoja->setCellValueByColumnAndRow(1, 7, "FOLIO OC");
$hoja->setCellValueByColumnAndRow(2, 7, "FECHA DE PAGO");
$hoja->setCellValueByColumnAndRow(3, 7, "PROVEEDOR");
$hoja->setCellValueByColumnAndRow(4, 7, "FOLIO DE FACTURA");
$hoja->setCellValueByColumnAndRow(5, 7, "TIPO DE PAGO");
$hoja->setCellValueByColumnAndRow(6, 7, "METODO DE PAGO");
$hoja->setCellValueByColumnAndRow(7, 7, "MONTO");

$documento->getActiveSheet()->getStyle('A7:F7')->applyFromArray($styleArray);

foreach (range('A', 'G') as $columnID) {
    if ($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else if ($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else if ($columnID == 'C')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(50);
    else if ($columnID == 'D')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if ($columnID == 'E')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else if ($columnID == 'F')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(25);
    else if ($columnID == 'G')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(10);
    else        
        $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$longitud = count($datos);

//Recorro todos los elementos
for ($i = 0; $i < $longitud; $i++) {
    $hoja->setCellValueByColumnAndRow(1, $i + 8, $datos[$i]["FolioOC"]);
    $hoja->setCellValueByColumnAndRow(2, $i + 8, $datos[$i]["Creado"]);
    $hoja->setCellValueByColumnAndRow(3, $i + 8, $datos[$i]["NombreProveedor"]);
    $hoja->setCellValueByColumnAndRow(4, $i + 8, $datos[$i]["FolioFactura"]);
    $hoja->setCellValueByColumnAndRow(5, $i + 8, utf8_encode($datos[$i]["TipoDP"]));
    $hoja->setCellValueByColumnAndRow(6, $i + 8, utf8_decode($datos[$i]["NombreMetodoPago"]));
    $hoja->setCellValueByColumnAndRow(7, $i + 8, $datos[$i]["Monto"]);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $nombreDelDocumento .'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');

exit;