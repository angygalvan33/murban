<?php
include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$fechaIni = $_GET["fIni"];
$fechaFin = $_GET["fFin"];
$idProveedor = $_GET['idProveedor'];

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
    ->setLastModifiedBy('CxC') //última vez modificado por
    ->setTitle('Bitacora de Materiales');

$nombreDelDocumento = "BitacoraMateriales.xlsx";
$files = glob('../../images/logo/*.*');

if($files[0] != null) {
  $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Logo');
  $drawing->setDescription('Logo');
  $drawing->setPath($files[0]);
  $drawing->setHeight(100);
  $drawing->setCoordinates('A1');
  $drawing->setWorksheet($documento->getActiveSheet());
}

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Bitácora de Materiales");

if($fechaIni != -1)
    $hoja->setCellValueByColumnAndRow(3, 2, urldecode('Fecha Inicio:').': ' .  $fechaIni . ' Fecha Fin: ' . $fechaFin);
$hoja->mergeCells('D4:D4');
//----------------Datos de prueba
$reporte = new ReporteExcel();
$datos = $reporte->getBitacoraMateriales($fechaIni, $fechaFin, $idProveedor);

$hoja->setCellValueByColumnAndRow(1, 7, "FOLIO REQUISICIÓN");
$hoja->setCellValueByColumnAndRow(2, 7, "REQUERIDA PARA");
$hoja->setCellValueByColumnAndRow(3, 7, "MATERIAL");
$hoja->setCellValueByColumnAndRow(4, 7, "CANTIDAD FALTANTE");
$hoja->setCellValueByColumnAndRow(5, 7, "PIEZAS");
$hoja->setCellValueByColumnAndRow(6, 7, "CANTIDAD ATENDIDA");
$hoja->setCellValueByColumnAndRow(7, 7, "FOLIO OC");
$hoja->setCellValueByColumnAndRow(8, 7, "FECHA CREACIÓN");
$hoja->setCellValueByColumnAndRow(9, 7, "PROVEEDOR");
$hoja->setCellValueByColumnAndRow(10, 7, "COMPRADOR");
$hoja->setCellValueByColumnAndRow(11, 7, "FECHA DE INGRESO");

$documento->getActiveSheet()->getStyle('A7:K7')->applyFromArray($styleArray);

foreach(range('A','K') as $columnID) {
    if($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else if($columnID == 'C')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(40);
    else if($columnID == 'D')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'E')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(10);
    else if($columnID == 'F')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'H')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'I')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(30);
    else if($columnID == 'J')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'K')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else
        $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$longitud = count($datos);
//Recorro todos los elementos
for($i = 0; $i < $longitud; $i++) {
    $hoja->setCellValueByColumnAndRow(1, $i+8, $datos[$i]["FolioRequi"]);
    $hoja->setCellValueByColumnAndRow(2, $i+8, $datos[$i]["FechaReq"]);
    $hoja->setCellValueByColumnAndRow(3, $i+8, $datos[$i]["Material"]);
    $hoja->setCellValueByColumnAndRow(4, $i+8, $datos[$i]["CantidadSolicitada"]);
    $hoja->setCellValueByColumnAndRow(5, $i+8, $datos[$i]["Piezas"]);
    $hoja->setCellValueByColumnAndRow(6, $i+8, $datos[$i]["CantidadAtendida"]);
    $hoja->setCellValueByColumnAndRow(7, $i+8, $datos[$i]["FolioOC"]);
    $hoja->setCellValueByColumnAndRow(8, $i+8, $datos[$i]["Fecha"]);
    $hoja->setCellValueByColumnAndRow(9, $i+8, $datos[$i]["Proveedor"]);
    $hoja->setCellValueByColumnAndRow(10, $i+8, $datos[$i]["Comprador"]);
    $hoja->setCellValueByColumnAndRow(11, $i+8, $datos[$i]["FechaIngr"]);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $nombreDelDocumento .'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;