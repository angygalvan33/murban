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
    ->setCreator("CP")
    ->setLastModifiedBy('CP') // última vez modificado por
    ->setTitle('Catalogo de proveedores');

$nombreDelDocumento = "CatalogoProveedores".$hoyf.".xlsx";

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Catalogo de proveedores");
//----------------Datos de prueba
$reporte = new ReporteExcel();

$datos = $reporte->getReporteCatalogoProveedores();
//VistaMaterialMedidaCategoria

$hoja->setCellValueByColumnAndRow(1, 1, "ID");
$hoja->setCellValueByColumnAndRow(2, 1, "NOMBRE");
$hoja->setCellValueByColumnAndRow(3, 1, "DIRECCION");
$hoja->setCellValueByColumnAndRow(4, 1, "TELEFONO");
$hoja->setCellValueByColumnAndRow(5, 1, "REPRESENTANTE");
$hoja->setCellValueByColumnAndRow(6, 1, "EMAIL");
$hoja->setCellValueByColumnAndRow(7, 1, "RFC");
$hoja->setCellValueByColumnAndRow(8, 1, "DIAS CREDITO");
$hoja->setCellValueByColumnAndRow(9, 1, "LIMITE CREDITO");
$hoja->setCellValueByColumnAndRow(10, 1, "TOTAL PROPUESTO");
$hoja->setCellValueByColumnAndRow(11, 1, "TOTAL AUTORIZADO");
$hoja->setCellValueByColumnAndRow(12, 1, "ELIMINADO");
$documento->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleArray);

foreach(range('A','L') as $columnID) {
    if($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(45);
    else if($columnID == 'C')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(30);
    else if($columnID == 'D')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'E')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(50);
    else if($columnID == 'F')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'G')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(25);
    else if($columnID == 'H')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'I')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'J')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'K')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'L')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else
        $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$longitud = count($datos);
//var_dump($datos);
//Recorro todos los elementos
for($i = 0; $i < $longitud; $i++) {
    $hoja->setCellValueByColumnAndRow(1, $i + 2, $datos[$i]["IdProveedor"]);
    $hoja->setCellValueByColumnAndRow(2, $i + 2, $datos[$i]["Nombre"]);
    $hoja->setCellValueByColumnAndRow(3, $i + 2, $datos[$i]["Direccion"]);
    $hoja->setCellValueByColumnAndRow(4, $i + 2, $datos[$i]["Telefono"]);
    $hoja->setCellValueByColumnAndRow(5, $i + 2, $datos[$i]["Representante"]);
    $hoja->setCellValueByColumnAndRow(6, $i + 2, $datos[$i]["Email"]);
    $hoja->setCellValueByColumnAndRow(7, $i + 2, $datos[$i]["Rfc"]);
    $hoja->setCellValueByColumnAndRow(8, $i + 2, $datos[$i]["DiasCredito"]);
    $hoja->setCellValueByColumnAndRow(9, $i + 2, $datos[$i]["LimiteCredito"]);
    $hoja->setCellValueByColumnAndRow(10, $i + 2, $datos[$i]["TotalPropuesto"]);
    $hoja->setCellValueByColumnAndRow(11, $i + 2, $datos[$i]["TotalAutorizado"]);
    $hoja->setCellValueByColumnAndRow(12, $i + 2, $datos[$i]["Eliminado"]);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;